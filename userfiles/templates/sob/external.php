<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title></title>
  <script type="text/javascript" src="http://google.com/jsapi"></script>
  <script type="text/javascript">google.load("jquery", "1.4.2");</script>
  <style type="text/css">
    *{
      margin: 0;
      padding: 0;
    }
    html, body{
      overflow: hidden;
    }

    #iframe{
      width: 100%;
      height: 100%;
    }


  </style>
  <script type="text/javascript">

  var url = "<?php print $_GET['url']; ?>";



  $(document).ready(function(){

  $("#iframe").height($(window).height()-$('#toolbar').outerHeight());
  $("#iframe").width($(window).width());

  $(window).bind('load resize', function(){
    $("#iframe").height($(window).height()-$('#toolbar').outerHeight());
    $("#iframe").width($(window).width());
  });


  });
  </script>
</head>
<body>
    <div id="toolbar">Cool </div>
    <iframe src="" frameborder="0" id="iframe"></iframe>
    <script type="text/javascript">
        document.getElementById('iframe').src=url;
    </script>
</body>
</html>