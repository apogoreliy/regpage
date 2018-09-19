<?php
/*********************************************************************
    index.php

    Helpdesk landing page. Please customize it to fit your needs.

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2006-2013 osTicket
    http://www.osticket.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/
require('client.inc.php');
$section = 'home';
require(CLIENTINC_DIR.'header.inc.php');
?>

<div id="landing_page">
    <h1>Добро пожаловать в систему управления заявками</h1>
    <p>
        In order to streamline support requests and better serve you, we utilize a support ticket system. Every support request is assigned a unique ticket number which you can use to track the progress and responses online. For your reference we provide complete archives and history of all your support requests. A valid email address is required to submit a ticket.
    </p>

    <div id="new_ticket">
        <h3>Открыть новую заявку</h3>
        <br>
        <div>Пожалуйста, описывайте вашу проблему более подробней с указанием всех деталей, также сообщайте полные сведения о себе.</div>
        <p>
            <a href="open.php" class="green button">Новая заявка</a>
        </p>
    </div>

    <div id="check_status">
        <h3>Проверить статус заявки</h3>
        <br>
        <div>Мы храним всю историю ваших обращений, чтобы вы могли получить более качественную помощь при последующих обращениях.</div>
        <p>
            <a href="view.php" class="blue button">Статус заявки</a>
        </p>
    </div>
</div>
<div class="clear"></div>
<?php
if($cfg && $cfg->isKnowledgebaseEnabled()){
    //FIXME: provide ability to feature or select random FAQs ??
?>
<p>Перед открытием заявки посмотрите нашу <a href="kb/index.php">базу знаний</a> (вопросы и ответы).</p>
</div>
<?php
} ?>
<?php require(CLIENTINC_DIR.'footer.inc.php'); ?>
