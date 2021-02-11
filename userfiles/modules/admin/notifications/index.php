<?php
$redirectLink = route('admin.notification.index');
?>

<?php
if (isset($params['stop_redirects']) && $params['stop_redirects'] == 1):
?>
<a href="<?php echo $redirectLink; ?>" class="btn btn-primary">Click here to redirecting to new notification module..</a>
<?php
else:
?>
<script>
    window.location.href = '<?php echo $redirectLink; ?>';
</script>

<?php
endif;
?>