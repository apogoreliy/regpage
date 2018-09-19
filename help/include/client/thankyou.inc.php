<?php
if(!defined('OSTCLIENTINC') || !is_object($ticket)) die('Kwaheri rafiki!');
//Please customize the message below to fit your organization speak!
?>
<div style="margin:5px 100px 100px 0;">
    <?php echo Format::htmlchars($ticket->getName()); ?>,<br>
    <p>
     Спасибо Вам за обращение!<br>
     Заявка была успешно создана и в ближайшее время наши специалисты ответят на ваш запрос.</p>
          
    <?php if($cfg->autoRespONNewTicket()){ ?>
    <p>Вся необходимая информация направлена вам на <b><?php echo $ticket->getEmail(); ?></b>.
       Данные сведения нужны Вам для дальнейшего просмотра статуса заявки. 
    </p>
    <?php } ?>
    <p>С уважением, администрация сайта</p>
</div>
