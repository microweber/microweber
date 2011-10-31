<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>{content_meta_title}</title>
<meta NAME="Description" CONTENT="{content_meta_description}">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="<? print TEMPLATE_URL ?>css/style.css" media="all" />
<link rel="stylesheet" type="text/css" href="<? print TEMPLATE_URL ?>css/ooyes.framework.css" media="all" />
<script type="text/javascript">

var img_url = '<? print TEMPLATE_URL ?>/img/'

</script>


<script type="text/javascript" src="<?  print site_url('api/js'); ?>"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/libs.js"></script>
<script type="text/javascript" src="<? print TEMPLATE_URL ?>js/functions.js"></script>
<script type="text/javascript">

  $(document).ready(function(){
     $("#the_contact_form").validate({
       valid:function(){

        var contact = {
            name:$('#name').val(),
            email:$('#email').val(),
            phone:$('#cphone').val(),
            message:$('#message').val()
        }

       $.post("<? print TEMPLATE_URL ?>mailsender.php", contact, function(){

       });

        return false;
       },
       preventSubmit:true
     });
  });

  </script>
</head>
<body>
<div id="container">
  <div id="wrapper">
    <div id="header">
      <div id="phone">
        <address class="xhidden">
        Телефон на клиента - 02 / 962 60 26
        </address>
        <a  href="https://www.facebook.com/pages/Дентален-Център-3М/241127939263153" target="_blank" id="facebook_top">Стани ни приятел</a> </div>
      <div class="c" style="padding-bottom: 10px">&nbsp;</div>
      <a href="<? print site_url(); ?>" id="logo" title="Дентален Център 3М - Здрава и красива усмивка всеки ден">Дентален Център "3М" - Здрава и красива усмивка всеки ден</a>
     
      <div id="nav">
        <microweber module="content/menu"  name="main_menu"  />
      </div>
    </div>
    <!-- /#header -->
    <div id="content">
      