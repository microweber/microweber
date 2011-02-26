<script type="text/javascript">
  var RecaptchaOptions = { 
    theme:"<?= $theme ?>",
    lang:"<?= $lang ?>"
  };
</script>
<script type="text/javascript" src="<?= $server ?>/challenge?k=<?= $key.$errorpart ?>"></script>
<noscript>
		<iframe src="<?= $server ?>/noscript?lang=<?= $lang ?>&k=<?= $key.$errorpart ?>" height="300" width="500" frameborder="0"></iframe><br/>
		<textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
		<input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>
</noscript>