<?php
/*********************************************************************
    profile.php

    Staff's profile handle

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2006-2013 osTicket
    http://www.osticket.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/

require_once('staff.inc.php');
$msg='';
$staff=Staff::lookup($thisstaff->getId());
if($_POST && $_POST['id']!=$thisstaff->getId()) { //Check dummy ID used on the form.
 $errors['err']='Внутренняя ошибка. Доступ запрещен';
} elseif(!$errors && $_POST) { //Handle post

    if(!$staff)
        $errors['err']='Неизвестный или неправильный пользователь';
    elseif($staff->updateProfile($_POST,$errors)){
        $msg='Профиль успешно обновлен';
        $thisstaff->reload();
        $staff->reload();
        $_SESSION['TZ_OFFSET']=$thisstaff->getTZoffset();
        $_SESSION['TZ_DST']=$thisstaff->observeDaylight();
    }elseif(!$errors['err'])
        $errors['err']='Ошибка при обновлении профиля. Исправьте ошибки и попробуйте еще раз!';
}

//Forced password Change.
if($thisstaff->forcePasswdChange() && !$errors['err'])
    $errors['err']=sprintf('<b>%s</b> - Вы должны изменить ваш пароль для продолжения!',$thisstaff->getFirstName());
elseif($thisstaff->onVacation() && !$warn)
    $warn=sprintf('<b>Добро пожаловать %s</b>! Ваш статус \'в отпуске\' Пожалуйста свяжитесь с администратором для смены вашего статуса.',$thisstaff->getFirstName());

$inc='profile.inc.php';
$nav->setTabActive('dashboard');
require_once(STAFFINC_DIR.'header.inc.php');
require(STAFFINC_DIR.$inc);
require_once(STAFFINC_DIR.'footer.inc.php');
?>
