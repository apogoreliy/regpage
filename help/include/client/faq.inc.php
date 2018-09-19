<?php
if(!defined('OSTCLIENTINC') || !$faq  || !$faq->isPublished()) die('Access Denied');
$category=$faq->getCategory();
?>
<h1>Вопросы и ответы</h1>
<div id="breadcrumbs">
    <a href="index.php">Все категории</a>
    &raquo; <a href="faq.php?cid=<?php echo $category->getId(); ?>"><?php echo $category->getName(); ?></a>
</div>
<hr>
<div style="width:700;padding-top:2px; float:left;">
<strong style="font-size:16px;"><?php echo $faq->getQuestion() ?></strong>
</div>
<div style="float:right;text-align:right;padding-top:5px;padding-right:5px;"></div>
<div class="clear"></div>
<p>
<?php echo Format::safe_html($faq->getAnswer()); ?>
</p>
<p>
<?php
if($faq->getNumAttachments()) { ?>
 <div><span class="faded"><b>Вложения:</b></span>  <?php echo $faq->getAttachmentsLinks(); ?></div>
<?php
} ?>
<div class="article-meta">
<?php
### ---> Доработка от osTicket.Ru
$topics=$faq->getHelpTopics();
$test=implode(', ',$topics);
if ($test!="") echo "<span class=\"faded\"><b>Тема обращения: </b></span>".$test;
?>
</div>
</p>
<hr>
<div class="faded">&nbsp;Последнее обновление -> <?php echo Format::db_daydatetime($category->getUpdateDate()); ?></div>
