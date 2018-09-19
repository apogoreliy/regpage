<?php
/*************************************************************************
    tickets.php

    Handles all tickets related actions.

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2006-2013 osTicket
    http://www.osticket.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/

require('staff.inc.php');
require_once(INCLUDE_DIR.'class.ticket.php');
require_once(INCLUDE_DIR.'class.dept.php');
require_once(INCLUDE_DIR.'class.filter.php');
require_once(INCLUDE_DIR.'class.canned.php');


$page='';
$ticket=null; //clean start.
//LOCKDOWN...See if the id provided is actually valid and if the user has access.
if($_REQUEST['id']) {
    if(!($ticket=Ticket::lookup($_REQUEST['id'])))
         $errors['err']='Неизвестный или ошибочный номер заявки';
    elseif(!$ticket->checkStaffAccess($thisstaff)) {
        $errors['err']='Доступ запрещен. Свяжитесь с администратором.';
        $ticket=null; //Clear ticket obj.
    }
}
//At this stage we know the access status. we can process the post.
if($_POST && !$errors):

    if($ticket && $ticket->getId()) {
        //More coffee please.
        $errors=array();
        $lock=$ticket->getLock(); //Ticket lock if any
        $statusKeys=array('open'=>'Open','Reopen'=>'Open','Close'=>'Closed');
        switch(strtolower($_POST['a'])):
        case 'reply':
            if(!$thisstaff->canPostReply())
                $errors['err'] = 'Данное действие запрещено. Свяжитесь с администратором для предоставления доступа';
            else {

                if(!$_POST['response'])
                    $errors['response']='Введите ответ';
                //Use locks to avoid double replies
                if($lock && $lock->getStaffId()!=$thisstaff->getId())
                    $errors['err']='Данное действие запрещено. Заявка заблокирована!';

                //Make sure the email is not banned
                if(!$errors['err'] && TicketFilter::isBanned($ticket->getEmail()))
                    $errors['err']='Email находится в банлисте. Удалите из банлиста для ответа.';
            }

            $wasOpen =($ticket->isOpen());

            //If no error...do the do.
            $vars = $_POST;
            if(!$errors && $_FILES['attachments'])
                $vars['files'] = AttachmentFile::format($_FILES['attachments']);

            if(!$errors && ($response=$ticket->postReply($vars, $errors, isset($_POST['emailreply'])))) {
                $msg='Вы успешно ответили на сообщение';
                $ticket->reload();
                if($ticket->isClosed() && $wasOpen)
                    $ticket=null;

            } elseif(!$errors['err']) {
                $errors['err']='Невозможно добавить ответ. Исправьте ошибки и попробуйте еще раз!';
            }
            break;
        case 'transfer': /** Transfer ticket **/
            //Check permission
            if(!$thisstaff->canTransferTickets())
                $errors['err']=$errors['transfer'] = 'Данное действие запрещено. У вас нет доступа на перенос заявки.';
            else {

                //Check target dept.
                if(!$_POST['deptId'])
                    $errors['deptId'] = 'Выберите отдел';
                elseif($_POST['deptId']==$ticket->getDeptId())
                    $errors['deptId'] = 'Данная заявка уже находится в этом отделе';
                elseif(!($dept=Dept::lookup($_POST['deptId'])))
                    $errors['deptId'] = 'Неизвестный или неверный отдел';

                //Transfer message - required.
                if(!$_POST['transfer_comments'])
                    $errors['transfer_comments'] = 'Введите комментарий';
                elseif(strlen($_POST['transfer_comments'])<5)
                    $errors['transfer_comments'] = 'Комментарий должен быть более 5 символов!';

                //If no errors - them attempt the transfer.
                if(!$errors && $ticket->transfer($_POST['deptId'], $_POST['transfer_comments'])) {
                    $msg = 'Заявка успешно перенесена в '.$ticket->getDeptName();
                    //Check to make sure the staff still has access to the ticket
                    if(!$ticket->checkStaffAccess($thisstaff))
                        $ticket=null;

                } elseif(!$errors['transfer']) {
                    $errors['err'] = 'Невозможно завершить перенос заявки';
                    $errors['transfer']='Исправьте ошибки и попробуйте еще раз!';
                }
            }
            break;
        case 'assign':

             if(!$thisstaff->canAssignTickets())
                 $errors['err']=$errors['assign'] = 'Данное действие запрещено. У вас нет прав для передачи заявок.';
             else {

                 $id = preg_replace("/[^0-9]/", "",$_POST['assignId']);
                 $claim = (is_numeric($_POST['assignId']) && $_POST['assignId']==$thisstaff->getId());

                 if(!$_POST['assignId'] || !$id)
                     $errors['assignId'] = 'Выберите для передачи';
                 elseif($_POST['assignId'][0]!='s' && $_POST['assignId'][0]!='t' && !$claim)
                     $errors['assignId']='Неверный номер назначения - свяжитесь с администратором';
                 elseif($ticket->isAssigned()) {
                     if($_POST['assignId'][0]=='s' && $id==$ticket->getStaffId())
                         $errors['assignId']='Заявка уже передана данному пользователю.';
                     elseif($_POST['assignId'][0]=='t' && $id==$ticket->getTeamId())
                         $errors['assignId']='Заявка уже передана этой команде.';
                 }

                 //Comments are not required on self-assignment (claim)
                 if($claim && !$_POST['assign_comments'])
                     $_POST['assign_comments'] = 'Заявка передана '.$thisstaff->getName();
                 elseif(!$_POST['assign_comments'])
                     $errors['assign_comments'] = 'Введите комментарий';
                 elseif(strlen($_POST['assign_comments'])<5)
                         $errors['assign_comments'] = 'Комментарий должен содержать более 5 символов';

                 if(!$errors && $ticket->assign($_POST['assignId'], $_POST['assign_comments'], !$claim)) {
                     if($claim) {
                         $msg = 'Заявка передана вам!';
                     } else {
                         $msg='Заявка успешно передана '.$ticket->getAssigned();
                         TicketLock::removeStaffLocks($thisstaff->getId(), $ticket->getId());
                         $ticket=null;
                     }
                 } elseif(!$errors['assign']) {
                     $errors['err'] = 'Невозможно завершить передачу заявки';
                     $errors['assign'] = 'Исправьте ошибки и попробуйте еще раз!';
                 }
             }
            break;
        case 'postnote': /* Post Internal Note */
            //Make sure the staff can set desired state
            if($_POST['state']) {
                if($_POST['state']=='closed' && !$thisstaff->canCloseTickets())
                    $errors['state'] = "У вас нет прав на закрытие заявки";
                elseif(in_array($_POST['state'], array('overdue', 'notdue', 'unassigned'))
                        && (!($dept=$ticket->getDept()) || !$dept->isManager($thisstaff)))
                    $errors['state'] = "У вас нет прав на смену статуса заявки";
            }

            $wasOpen = ($ticket->isOpen());

            $vars = $_POST;
            if($_FILES['attachments'])
                $vars['files'] = AttachmentFile::format($_FILES['attachments']);

            if(($note=$ticket->postNote($vars, $errors, $thisstaff))) {

                $msg='Внутреннее сообщение успешно добавлено';
                if($wasOpen && $ticket->isClosed())
                    $ticket = null; //Going back to main listing.

            } else {

                if(!$errors['err'])
                    $errors['err'] = 'Невозможно добавить внутреннее сообщение';

                $errors['postnote'] = 'Невозможно добавить внутреннее сообщение. Исправьте ошибки и попробуйте еще раз!';
            }
            break;
        case 'edit':
        case 'update':
            if(!$ticket || !$thisstaff->canEditTickets())
                $errors['err']='Доступ запрещен. У вас нет прав на изменение заявок';
            elseif($ticket->update($_POST,$errors)) {
                $msg='Заявка успешно изменена';
                $_REQUEST['a'] = null; //Clear edit action - going back to view.
                //Check to make sure the staff STILL has access post-update (e.g dept change).
                if(!$ticket->checkStaffAccess($thisstaff))
                    $ticket=null;
            } elseif(!$errors['err']) {
                $errors['err']='Невозможно изменить заявку. Исправьте ошибки и попробуйте еще раз!';
            }
            break;
        case 'process':
            switch(strtolower($_POST['do'])):
                case 'close':
                    if(!$thisstaff->canCloseTickets()) {
                        $errors['err'] = 'Доступ запрещен. У вас нет прав на закрытие заявок';
                    } elseif($ticket->isClosed()) {
                        $errors['err'] = 'Заявка уже закрыта!';
                    } elseif($ticket->close()) {
                        $msg='Заявка № '.$ticket->getExtId().' ЗАКРЫТА!';
                        //Log internal note
                        if($_POST['ticket_status_notes'])
                            $note = $_POST['ticket_status_notes'];
                        else
                            $note='Заявка закрыта (без комментариев)';

                        $ticket->logNote('Заявка закрыта', $note, $thisstaff);

                        //Going back to main listing.
                        TicketLock::removeStaffLocks($thisstaff->getId(), $ticket->getId());
                        $page=$ticket=null;

                    } else {
                        $errors['err']='Проблемы с закрытием заявки. Попробуйте еще раз';
                    }
                    break;
                case 'reopen':
                    //if staff can close or create tickets ...then assume they can reopen.
                    if(!$thisstaff->canCloseTickets() && !$thisstaff->canCreateTickets()) {
                        $errors['err']='Доступ запрещен. У вас нет доступа на открытие заявок.';
                    } elseif($ticket->isOpen()) {
                        $errors['err'] = 'Заявка уже открыта!';
                    } elseif($ticket->reopen()) {
                        $msg='Заявка снова открыта!';

                        if($_POST['ticket_status_notes'])
                            $note = $_POST['ticket_status_notes'];
                        else
                            $note='Заявка снова открыта (без комментариев)';

                        $ticket->logNote('Заявка снова открыта', $note, $thisstaff);

                    } else {
                        $errors['err']='При открытии заявки возникли проблемы. Попробуйте еще раз!';
                    }
                    break;
                case 'release':
                    if(!$ticket->isAssigned() || !($assigned=$ticket->getAssigned())) {
                        $errors['err'] = 'Заявка не назначена!';
                    } elseif($ticket->release()) {
                        $msg='Заявка откреплена от '.$assigned;
                        $ticket->logActivity('Ticket unassigned',$msg.' by '.$thisstaff->getName());
                    } else {
                        $errors['err'] = 'Problems releasing the ticket. Try again';
                    }
                    break;
                case 'claim':
                    if(!$thisstaff->canAssignTickets()) {
                        $errors['err'] = 'Perm. Denied. You are not allowed to assign/claim tickets.';
                    } elseif(!$ticket->isOpen()) {
                        $errors['err'] = 'Only open tickets can be assigned';
                    } elseif($ticket->isAssigned()) {
                        $errors['err'] = 'Ticket is already assigned to '.$ticket->getAssigned();
                    } elseif($ticket->assignToStaff($thisstaff->getId(), ('Ticket claimed by '.$thisstaff->getName()), false)) {
                        $msg = 'Ticket is now assigned to you!';
                    } else {
                        $errors['err'] = 'Problems assigning the ticket. Try again';
                    }
                    break;
                case 'overdue':
                    $dept = $ticket->getDept();
                    if(!$dept || !$dept->isManager($thisstaff)) {
                        $errors['err']='Perm. Denied. You are not allowed to flag tickets overdue';
                    } elseif($ticket->markOverdue()) {
                        $msg='Ticket flagged as overdue';
                        $ticket->logActivity('Ticket Marked Overdue',($msg.' by '.$thisstaff->getName()));
                    } else {
                        $errors['err']='Problems marking the the ticket overdue. Try again';
                    }
                    break;
                case 'answered':
                    $dept = $ticket->getDept();
                    if(!$dept || !$dept->isManager($thisstaff)) {
                        $errors['err']='Perm. Denied. You are not allowed to flag tickets';
                    } elseif($ticket->markAnswered()) {
                        $msg='Ticket flagged as answered';
                        $ticket->logActivity('Ticket Marked Answered',($msg.' by '.$thisstaff->getName()));
                    } else {
                        $errors['err']='Problems marking the the ticket answered. Try again';
                    }
                    break;
                case 'unanswered':
                    $dept = $ticket->getDept();
                    if(!$dept || !$dept->isManager($thisstaff)) {
                        $errors['err']='Perm. Denied. You are not allowed to flag tickets';
                    } elseif($ticket->markUnAnswered()) {
                        $msg='Ticket flagged as unanswered';
                        $ticket->logActivity('Ticket Marked Unanswered',($msg.' by '.$thisstaff->getName()));
                    } else {
                        $errors['err']='Problems marking the the ticket unanswered. Try again';
                    }
                    break;
                case 'banemail':
                    if(!$thisstaff->canBanEmails()) {
                        $errors['err']='Perm. Denied. You are not allowed to ban emails';
                    } elseif(BanList::includes($ticket->getEmail())) {
                        $errors['err']='Email already in banlist';
                    } elseif(Banlist::add($ticket->getEmail(),$thisstaff->getName())) {
                        $msg='Email ('.$ticket->getEmail().') added to banlist';
                    } else {
                        $errors['err']='Unable to add the email to banlist';
                    }
                    break;
                case 'unbanemail':
                    if(!$thisstaff->canBanEmails()) {
                        $errors['err'] = 'Perm. Denied. You are not allowed to remove emails from banlist.';
                    } elseif(Banlist::remove($ticket->getEmail())) {
                        $msg = 'Email removed from banlist';
                    } elseif(!BanList::includes($ticket->getEmail())) {
                        $warn = 'Email is not in the banlist';
                    } else {
                        $errors['err']='Unable to remove the email from banlist. Try again.';
                    }
                    break;
                case 'delete': // Dude what are you trying to hide? bad customer support??
                    if(!$thisstaff->canDeleteTickets()) {
                        $errors['err']='Perm. Denied. You are not allowed to DELETE tickets!!';
                    } elseif($ticket->delete()) {
                        $msg='Ticket #'.$ticket->getNumber().' deleted successfully';
                        //Log a debug note
                        $ost->logDebug('Ticket #'.$ticket->getNumber().' deleted',
                                sprintf('Ticket #%s deleted by %s',
                                    $ticket->getNumber(), $thisstaff->getName())
                                );
                        $ticket=null; //clear the object.
                    } else {
                        $errors['err']='Problems deleting the ticket. Try again';
                    }
                    break;
                default:
                    $errors['err']='You must select action to perform';
            endswitch;
            break;
        default:
            $errors['err']='Неизвестное действие';
        endswitch;
        if($ticket && is_object($ticket))
            $ticket->reload();//Reload ticket info following post processing
    }elseif($_POST['a']) {

        switch($_POST['a']) {
            case 'mass_process':
                if(!$thisstaff->canManageTickets())
                    $errors['err']='You do not have permission to mass manage tickets. Contact admin for such access';
                elseif(!$_POST['tids'] || !is_array($_POST['tids']))
                    $errors['err']='No tickets selected. You must select at least one ticket.';
                else {
                    $count=count($_POST['tids']);
                    $i = 0;
                    switch(strtolower($_POST['do'])) {
                        case 'reopen':
                            if($thisstaff->canCloseTickets() || $thisstaff->canCreateTickets()) {
                                $note='Ticket reopened by '.$thisstaff->getName();
                                foreach($_POST['tids'] as $k=>$v) {
                                    if(($t=Ticket::lookup($v)) && $t->isClosed() && @$t->reopen()) {
                                        $i++;
                                        $t->logNote('Ticket Reopened', $note, $thisstaff);
                                    }
                                }

                                if($i==$count)
                                    $msg = "Выбранные заявки ($i) открыты успешно";
                                elseif($i)
                                    $warn = "$i из $count выбранных заявок открыты";
                                else
                                    $errors['err'] = 'Unable to reopen selected tickets';
                            } else {
                                $errors['err'] = 'You do not have permission to reopen tickets';
                            }
                            break;
                        case 'close':
                            if($thisstaff->canCloseTickets()) {
                                $note='Заявка закрыта без ответа '.$thisstaff->getName();
                                foreach($_POST['tids'] as $k=>$v) {
                                    if(($t=Ticket::lookup($v)) && $t->isOpen() && @$t->close()) {
                                        $i++;
                                        $t->logNote('Заявка закрыта', $note, $thisstaff);
                                    }
                                }

                                if($i==$count)
                                    $msg ="Selected tickets ($i) closed succesfully";
                                elseif($i)
                                    $warn = "$i of $count selected tickets closed";
                                else
                                    $errors['err'] = 'Unable to close selected tickets';
                            } else {
                                $errors['err'] = 'You do not have permission to close tickets';
                            }
                            break;
                        case 'mark_overdue':
                            $note='Ticket flagged as overdue by '.$thisstaff->getName();
                            foreach($_POST['tids'] as $k=>$v) {
                                if(($t=Ticket::lookup($v)) && !$t->isOverdue() && $t->markOverdue()) {
                                    $i++;
                                    $t->logNote('Ticket Marked Overdue', $note, $thisstaff);
                                }
                            }

                            if($i==$count)
                                $msg = "Selected tickets ($i) marked overdue";
                            elseif($i)
                                $warn = "$i of $count selected tickets marked overdue";
                            else
                                $errors['err'] = 'Unable to flag selected tickets as overdue';
                            break;
                        case 'delete':
                            if($thisstaff->canDeleteTickets()) {
                                foreach($_POST['tids'] as $k=>$v) {
                                    if(($t=Ticket::lookup($v)) && @$t->delete()) $i++;
                                }

                                //Log a warning
                                if($i) {
                                    $log = sprintf('%s (%s) just deleted %d ticket(s)',
                                            $thisstaff->getName(), $thisstaff->getUserName(), $i);
                                    $ost->logWarning('Tickets deleted', $log, false);

                                }

                                if($i==$count)
                                    $msg = "Selected tickets ($i) deleted successfully";
                                elseif($i)
                                    $warn = "$i of $count selected tickets deleted";
                                else
                                    $errors['err'] = 'Unable to delete selected tickets';
                            } else {
                                $errors['err'] = 'You do not have permission to delete tickets';
                            }
                            break;
                        default:
                            $errors['err']='Unknown or unsupported action - get technical help';
                    }
                }
                break;
            case 'open':
                $ticket=null;
                if(!$thisstaff || !$thisstaff->canCreateTickets()) {
                     $errors['err']='You do not have permission to create tickets. Contact admin for such access';
                } else {
                    $vars = $_POST;
                    if($_FILES['attachments'])
                        $vars['files'] = AttachmentFile::format($_FILES['attachments']);

                    if(($ticket=Ticket::open($vars, $errors))) {
                        $msg='Заявка создана успешно';
                        $_REQUEST['a']=null;
                        if(!$ticket->checkStaffAccess($thisstaff) || $ticket->isClosed())
                            $ticket=null;
                    } elseif(!$errors['err']) {
                        $errors['err']='Unable to create the ticket. Correct the error(s) and try again';
                    }
                }
                break;
        }
    }
    if(!$errors)
        $thisstaff ->resetStats(); //We'll need to reflect any changes just made!
endif;

/*... Quick stats ...*/
$stats= $thisstaff->getTicketsStats();

//Navigation
$nav->setTabActive('tickets');
if($cfg->showAnsweredTickets()) {
    $nav->addSubMenu(array('desc'=>'Открытые ('.number_format($stats['open']+$stats['answered']).')',
                            'title'=>'Открытые заявки',
                            'href'=>'tickets.php',
                            'iconclass'=>'Ticket'),
                        (!$_REQUEST['status'] || $_REQUEST['status']=='open'));
} else {

    if($stats) {
        $nav->addSubMenu(array('desc'=>'Открытые ('.number_format($stats['open']).')',
                               'title'=>'Открытые заявки',
                               'href'=>'tickets.php',
                               'iconclass'=>'Ticket'),
                            (!$_REQUEST['status'] || $_REQUEST['status']=='open'));
    }

    if($stats['answered']) {
        $nav->addSubMenu(array('desc'=>'Отвеченные ('.number_format($stats['answered']).')',
                               'title'=>'Отвеченные заявки',
                               'href'=>'tickets.php?status=answered',
                               'iconclass'=>'answeredTickets'),
                            ($_REQUEST['status']=='answered'));
    }
}

if($stats['assigned']) {
    if(!$ost->getWarning() && $stats['assigned']>10)
        $ost->setWarning($stats['assigned'].' tickets assigned to you! Do something about it!');

    $nav->addSubMenu(array('desc'=>'Мои заявки ('.number_format($stats['assigned']).')',
                           'title'=>'Assigned Tickets',
                           'href'=>'tickets.php?status=assigned',
                           'iconclass'=>'assignedTickets'),
                        ($_REQUEST['status']=='assigned'));
}

if($stats['overdue']) {
    $nav->addSubMenu(array('desc'=>'Просроченные ('.number_format($stats['overdue']).')',
                           'title'=>'Просроченные заявки',
                           'href'=>'tickets.php?status=overdue',
                           'iconclass'=>'overdueTickets'),
                        ($_REQUEST['status']=='overdue'));

    if(!$sysnotice && $stats['overdue']>10)
        $sysnotice=$stats['overdue'] .' overdue tickets!';
}

if($thisstaff->showAssignedOnly() && $stats['closed']) {
    $nav->addSubMenu(array('desc'=>'Мои закрытые заявки ('.number_format($stats['closed']).')',
                           'title'=>'Мои закрытые заявки',
                           'href'=>'tickets.php?status=closed',
                           'iconclass'=>'closedTickets'),
                        ($_REQUEST['status']=='closed'));
} else {

    $nav->addSubMenu(array('desc'=>'Закрытые ('.number_format($stats['closed']).')',
                           'title'=>'Закрытые заявки',
                           'href'=>'tickets.php?status=closed',
                           'iconclass'=>'closedTickets'),
                        ($_REQUEST['status']=='closed'));
}

if($thisstaff->canCreateTickets()) {
    $nav->addSubMenu(array('desc'=>'Новая заявка',
                           'href'=>'tickets.php?a=open',
                           'iconclass'=>'newTicket'),
                        ($_REQUEST['a']=='open'));
}


$inc = 'tickets.inc.php';
if($ticket) {
    $ost->setPageTitle('Ticket #'.$ticket->getNumber());
    $nav->setActiveSubMenu(-1);
    $inc = 'ticket-view.inc.php';
    if($_REQUEST['a']=='edit' && $thisstaff->canEditTickets())
        $inc = 'ticket-edit.inc.php';
    elseif($_REQUEST['a'] == 'print' && !$ticket->pdfExport($_REQUEST['psize'], $_REQUEST['notes']))
        $errors['err'] = 'Внутренняя ошибка: Невозможно экспортировать заявку в формат PDF для печати.';
} else {
    $inc = 'tickets.inc.php';
    if($_REQUEST['a']=='open' && $thisstaff->canCreateTickets())
        $inc = 'ticket-open.inc.php';
    elseif($_REQUEST['a'] == 'export') {
        require_once(INCLUDE_DIR.'class.export.php');
        $ts = strftime('%Y%m%d');
        if (!($token=$_REQUEST['h']))
            $errors['err'] = 'Query token required';
        elseif (!($query=$_SESSION['search_'.$token]))
            $errors['err'] = 'Query token not found';
        elseif (!Export::saveTickets($query, "tickets-$ts.csv", 'csv'))
            $errors['err'] = 'Внутренняя ошибка: невозможно выгрузить заявки';
    }

    //Clear active submenu on search with no status
    if($_REQUEST['a']=='search' && !$_REQUEST['status'])
        $nav->setActiveSubMenu(-1);

    //set refresh rate if the user has it configured
    if(!$_POST && !$_REQUEST['a'] && ($min=$thisstaff->getRefreshRate()))
        $ost->addExtraHeader('<meta http-equiv="refresh" content="'.($min*60).'" />');
}

require_once(STAFFINC_DIR.'header.inc.php');
require_once(STAFFINC_DIR.$inc);
require_once(STAFFINC_DIR.'footer.inc.php');
?>
