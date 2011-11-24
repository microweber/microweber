<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />

<script type="text/javascript">
            eventlistener='';
            imgurl="<?php print TEMPLATE_URL; ?>static/img/";
            template_url="<?php print TEMPLATE_URL; ?>";
            site_url="<?php print site_url(); ?>";



</script>
<?
$edit = ($this->template ['edit']);
		if (! $edit) {
			$edit = url_param ( 'edit' );
			if ($edit) {
				$this->template ['edit'] = true;
			}
		}

?>
<? if ($edit) : ?>

<script type="text/javascript" src="<?php print site_url('api/js/index/edit:true'); ?>"></script>
<link rel="stylesheet" href="http://akzhan.github.com/jwysiwyg/jwysiwyg/jquery.wysiwyg.css" type="text/css" media="screen"  />
<link rel="stylesheet" href="http://akzhan.github.com/jwysiwyg/jwysiwyg/jquery.wysiwyg.modal.css" type="text/css" media="screen"  />
<link rel="stylesheet" href="http://akzhan.github.com/jwysiwyg/lib/jquery.simplemodal.css" type="text/css" media="screen"  />
<script type="text/javascript" src="http://akzhan.github.com/jwysiwyg/jwysiwyg/jquery.wysiwyg.js"></script>
<script type="text/javascript" src="http://akzhan.github.com/jwysiwyg/lib/jquery.simplemodal.js"></script>
 
 
<? else: ?>
<script type="text/javascript" src="<?php print site_url('api/js'); ?>"></script>
<? endif; ?>

<link rel="stylesheet" href="<?php print TEMPLATE_URL; ?>static/css/style.css" type="text/css" media="screen"  />
<link rel="stylesheet" href="<?php print TEMPLATE_URL; ?>static/css/mw.css" type="text/css" media="screen"  />
<?php echo '<!--[if IE]><?import namespace="v" implementation="#default#VML" ?><![endif]-->'; ?>

<script type="text/javascript" src="<?php print TEMPLATE_URL; ?>static/js/libs.js"></script>
<script type="text/javascript" src="<?php print TEMPLATE_URL; ?>static/js/mw.js"></script>
<script type="text/javascript" src="<?php print TEMPLATE_URL; ?>static/js/functions.js"></script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script><script type="text/javascript">stLight.options({publisher:'c9929a13-60f4-4b07-adab-14c336c9f623'});</script>

 
 

