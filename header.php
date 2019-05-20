<?php

include_once "preheader.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Страница регистрации</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="Expires" content="Wed, 06 Sep 2017 16:35:12 GMT">
    <meta http-equiv="Pragma" content="no-cache">
    <!--<meta name="yandex-verification" content="56dd8d3e07b017d1" />-->
    <meta name="mailru-domain" content="z83V20hFDKLekMbc" />

    <link href="favicon.ico" rel="shortcut icon" />

    <link href="css/font-awesome.min.css" rel="stylesheet" />
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet" />
    <link href="css/bootstrap-modal.css" rel="stylesheet" />
    <link href="css/bootstrap-datepicker.min.css" rel="stylesheet" />
    <link href="css/style.css?v21" rel="stylesheet" />

    <script src="js/jquery.min.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <script src="js/bootstrap-modalmanager.js" type="text/javascript"></script>
    <script src="js/bootstrap-modal.js" type="text/javascript"></script>
    <script src="js/jquery.mockjax.js" type="text/javascript"></script>
    <script src="js/jquery.autocomplete.js" type="text/javascript"></script> <!-- https://github.com/devbridge/jQuery-Autocomplete -->
    <script src="js/jquery.maskedinput.min.js" type="text/javascript"></script>
    <script src="js/script.js?v45" type="text/javascript"></script>
    <script src="js/jquery-textrange.js" type="text/javascript"></script>
    <script src="js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="js/bootstrap-datepicker.ru.js" type="text/javascript"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

</head>

<body>
    <script>window.adminId=<?php echo isset ($memberId) ? "'$memberId'" : 'null'; ?>;</script>
    <div id="globalError" class="alert alert-error above-modal" style="display: none"><a class="close close-alert" href="#">&times;</a><span>AJAX ERROR</span></div>
    <div id="globalHint" class="alert alert-success above-modal" style="display: none"><a class="close close-alert" href="#">&times;</a><span>GLOBAL HINT</span></div>
    <div id="ajaxLoading" style="display: none"><img src="img/ajax_loader.gif"></div>
    <div><i class="fa fa-arrow-up fa-3x scroll-up" aria-hidden="true"></i></div>
