<link rel="stylesheet" href="<?php print $config['url_to_module']; ?>style.css" />
<?php $video =  get_option('video', $params['id']); ?>
<?php 
if($video==false) { 
$video = "//demosthenes.info/assets/videos/polina.mp4";
}
?>
<div  class="bgvid">
  <video autoplay>
    <source src="<?php print $video; ?>" type="video/mp4">
  </video>
  <div class="video_with_text_holder">
    <div class="edit" field="video_text" rel="<?php print $params['id'] ?>">
      <h1>Hello world</h1>
      <p>You can edit this text</p>
    </div>
  </div>
</div>
