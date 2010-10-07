<!--[if IE]><? echo '<?import namespace="v" implementation="#default#VML" ?>' ?><![endif]-->
<link rel="stylesheet" href="<? print $layout_url; ?>layout.css" type="text/css" media="all"  />
<link rel="stylesheet" href="<? print $layout_url; ?>styles/<?  ($page['content_layout_style'] != '') ? print $page['content_layout_style']: print 'default.css'; ?>" type="text/css" media="all"  />
<script>


   var style_url = '<? print $layout_url; ?>styles/<?  ($page['content_layout_style'] != '') ? print $page['content_layout_style'] : print ""; ?>';
   //alert('{STYLE_URL}');
   </script>
<? if(!empty($page['dynamic_content_relations'])): ?>
<? foreach($page['dynamic_content_relations'] as $relation): ?>
<? $parsed = $this->template_model->parseDynamicRelations($relation);
 if($parsed['content_layout_name'] != ''): ?>
<link rel="stylesheet" href="<? print $layouts_url; ?><? print $parsed['content_layout_name']; ?>/<? ?><?  print 'layout.css'; ?>" type="text/css" media="all"  />
<link rel="stylesheet" href="<? print $layouts_url; ?><? print $parsed['content_layout_name']; ?>/<? ?>styles/<?  ($parsed['content_layout_style'] != '') ? print $parsed['content_layout_style']: print 'default.css'; ?>" type="text/css" media="all"  />
<? endif; ?>
<?
 
 // p($parsed,0)
  ?>
<? endforeach; ?>
<? endif; ?>
<?
// p($page,1)
 
  ?>
<link rel="shortcut icon" type="image/x-icon" href="<? print TEMPLATE_URL; ?>favicon.ico" />
<script type="text/javascript">
$(window).load(function(){
   $(".submit").click(function(){
     $(this).parents("form").submit();
     return false;
   });
   $(".submitenter").click(function(){
     $(this).parents("form").find("input[type=submit]").click();
     return false;
   });


});


                </script>
