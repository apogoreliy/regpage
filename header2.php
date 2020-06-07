<?php

include_once "preheader.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-144838221-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-144838221-1');
</script>
    <meta charset="utf-8">
    <title>Страница регистрации</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="Expires" content="Wed, 06 Sep 2017 16:35:12 GMT">
    <meta http-equiv="Pragma" content="no-cache">
    <!--<meta name="yandex-verification" content="56dd8d3e07b017d1" />-->
    <meta name="mailru-domain" content="z83V20hFDKLekMbc" />
    <link href="favicon.ico" rel="shortcut icon" />
    <link href="css/font-awesome.min.css" rel="stylesheet" />
     <link href="css/style2.css?v3" rel="stylesheet" />
  <!--  <link href="css/style_slide.css?v2" rel="stylesheet" />-->
    <link href="css/contacts.css?v1" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <!--<script src="js/script.js?v182" type="text/javascript"></script>-->
    <script src="js/script2.js?v3" type="text/javascript"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="https://cdn.envybox.io/widget/cbk.css">
    <script type="text/javascript" src="https://cdn.envybox.io/widget/cbk.js?wcb_code=27de0b86fa9a7c4373ae996711b6f549" charset="UTF-8" async></script>
</head>

<body>
    <script>window.adminId=<?php echo isset ($memberId) ? "'$memberId'" : 'null'; ?>;</script>
    <div id="globalError" class="alert alert-error above-modal" style="display: none"><a class="close close-alert" href="#">&times;</a><span>AJAX ERROR</span></div>
    <div id="globalHint" class="alert alert-success above-modal" style="display: none"><a class="close close-alert" href="#">&times;</a><span>GLOBAL HINT</span></div>
    <div id="ajaxLoading" style="display: none"><img src="img/ajax_loader.gif"></div>
    <div><i class="fa fa-arrow-up fa-3x scroll-up" aria-hidden="true"></i></div>
