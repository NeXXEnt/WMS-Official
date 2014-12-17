<!doctype html>
<html lang="en">
   <head>
      <title><?= $title ?></title>
      <link type="text/css" rel="stylesheet" href="/css/bp/screen.css" media="screen, projection">
      <link type="text/css" rel="stylesheet" href="/css/bp/print.css" media="print">      
      <!--[if lt IE 8]>
         <link rel="stylesheet" href="css/bp/ie.css" type="text/css" media="screen, projection">
      <![endif]-->
      <link type="text/css" rel="stylesheet" href="/css/bp/src/grid.css">
      <link type="text/css" rel="stylesheet" href="/css/bp/plugins/buttons/screen.css">
      <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
      <link type="text/css" rel="stylesheet" href="/js/table-sort/themes/blue/style.css">
      <link type="text/css" rel="stylesheet" href="/css/default.css">
      <?= $_styles ?>

      <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
      <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
      <script src="//ajax.googleapis.com/ajax/libs/dojo/1.9.1/dojo/dojo.js"></script>
      <script type="text/javascript" src="/js/table-sort/jquery.tablesorter.js"></script>
      <script type="text/javascript" src="/js/common.js"></script>
      <?= $_scripts ?>
      
      
   </head>
   <body class="body">
      <?= $header ?>
      <div class="wrapper">
         <?= $sidebar ?>      
         <?= $content ?>
      </div>
      <?= $footer ?>
      

   </body>

</html>