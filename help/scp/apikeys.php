<?php
/*********************************************************************
    apikeys.php

    API keys.

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2006-2013 osTicket
    http://www.osticket.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/
require('admin.inc.php');
include_once(INCLUDE_DIR.'class.api.php');

$api=null;
if($_REQUEST['id'] && !($api=API::lookup($_REQUEST['id'])))
    $errors['err']='Неизвестный API ID ключа.';

if($_POST){
    switch(strtolower($_POST['do'])){
        case 'update':
            if(!$api){
                $errors['err']='Неизвестный API ключ.';
            }elseif($api->update($_POST,$errors)){
                $msg='API ключ успешно обновлен';
            }elseif(!$errors['err']){
                $errors['err']='Error updating API key. Try again!';
            }
            break;
        case 'add':
            if(($id=API::add($_POST,$errors))){
                $msg='API ключ успешно добавлен';
                $_REQUEST['a']=null;
            }elseif(!$errors['err']){
                $errors['err']='Невозможно добавить API ключ. Исправьте ошибки и попробуйте еще раз.';
            }
            break;
        case 'mass_process':
            if(!$_POST['ids'] || !is_array($_POST['ids']) || !count($_POST['ids'])) {
                $errors['err'] = 'Выдолжны выбрать хот бы один API ключ';
            } else {
                $count=count($_POST['ids']);
                switch(strtolower($_POST['a'])) {
                    case 'enable':
                        $sql='UPDATE '.API_KEY_TABLE.' SET isactive=1 '
                            .' WHERE id IN ('.implode(',', db_input($_POST['ids'])).')';
                        if(db_query($sql) && ($num=db_affected_rows())) {
                            if($num==$count)
                                $msg = 'Выбранные API ключи включены';
                            else
                                $warn = "$num из $count выбранных API ключей включены";
                        } else {
                            $errors['err'] = 'Невозможно включить выбранные API ключи';
                        }
                        break;
                    case 'disable':
                        $sql='UPDATE '.API_KEY_TABLE.' SET isactive=0 '
                            .' WHERE id IN ('.implode(',', db_input($_POST['ids'])).')';
                        if(db_query($sql) && ($num=db_affected_rows())) {
                            if($num==$count)
                                $msg = 'Выбранные API ключи отключены';
                            else
                                $warn = "$num из $count выбранных API ключей отключены";
                        } else {
                            $errors['err']='Невозможно отключить выбранные API ключи';
                        }
                        break;
                    case 'delete':
                        $i=0;
                        foreach($_POST['ids'] as $k=>$v) {
                            if(($t=API::lookup($v)) && $t->delete())
                                $i++;
                        }
                        if($i && $i==$count)
                            $msg = 'Выбранные API ключи удалены успешно';
                        elseif($i>0)
                            $warn = "$i из $count выбранных API ключей удалены";
                        elseif(!$errors['err'])
                            $errors['err'] = 'Невозможно удалить выбранные API ключи';
                        break;
                    default:
                        $errors['err']='Неизвестное действие - свяжитесь с администратором';
                }
            }
            break;
        default:
            $errors['err']='неизвестное действие';
            break;
    }
}

$page='apikeys.inc.php';
if($api || ($_REQUEST['a'] && !strcasecmp($_REQUEST['a'],'add')))
    $page='apikey.inc.php';

$nav->setTabActive('manage');
require(STAFFINC_DIR.'header.inc.php');
require(STAFFINC_DIR.$page);
include(STAFFINC_DIR.'footer.inc.php');
?>
