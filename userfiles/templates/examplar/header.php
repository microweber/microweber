<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>{content_meta_title}</title>
<meta NAME="Description" CONTENT="{content_meta_description}">
<meta NAME="Keywords" CONTENT="{content_meta_keywords}">

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="<? print TEMPLATE_URL ?>css/style.css" media="all" />
<link rel="stylesheet" type="text/css" href="<? print TEMPLATE_URL ?>css/ooyes.framework.css" media="all" />
<script type="text/javascript">

var img_url = '<? print TEMPLATE_URL ?>/img/'

</script>
<link rel="shortcut icon" href="<?  print site_url('favicon.ico'); ?>">
<link rel="apple-touch-icon" href="<?  print site_url('favicon.ico'); ?>">
<!-- mA_t_ckFfGWjpRym1cnj_eYenlo -->

<meta name="alexaVerifyID" content="mA_t_ckFfGWjpRym1cnj_eYenlo" />



<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>

<!--<script type="text/javascript" src="<?  print site_url('api/js'); ?>"></script>
--><script type="text/javascript" src="<? print TEMPLATE_URL ?>js/libs.js"></script>
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
contact = $("#the_contact_form").serialize();
       $.post("<? print TEMPLATE_URL ?>mailsender.php", contact, function(){
$("#the_contact_form").html("<br><br><h2>Your request has been sent</h2><br><br><br>");

_gaq.push(['_trackPageview', '/contactsent']);


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
      <table border="0" cellspacing="5" cellpadding="5">
  <tr>
    <td><img src="<? print TEMPLATE_URL ?>img/top_icon1.png" alt="Contact Us" /></td>
    <td><a href="http://twitter.com/#!/exemplarhealth" target="_blank"><img src="<? print TEMPLATE_URL ?>img/top_icon2.png" alt="Twitter Follow" /></a></td>
    <td><a href="https://www.facebook.com/pages/Exemplar-Health-Resources-LLC/130372863723105"  target="_blank" title="Examplar Facebook"><img src="<? print TEMPLATE_URL ?>img/top_icon3.png"  alt="Like us on Face book" /></a></td>
  </tr>
</table>

         
        </div>
       <a href="<? print site_url(); ?>" id="logo" title="Exemplar Health Resources"><h1>Exemplar Health Resources</h1></a>
     
      <div id="nav">
        <microweber module="content/menu"  name="main_menu"  />
      </div>
    </div>
    <!-- /#header -->
    <div id="content">
      