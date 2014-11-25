<script src="<? print $config['../ants/url_to_module'] ?>js/jquery.insects.js"></script>
<link rel="stylesheet" type="text/css" href="<? print $config['../ants/url_to_module'] ?>css/jquery.insects.css" />

<script type="text/javascript">
	$(document).ready(function () {
	 $('#<? print $params['id']; ?>').insectify({chance: 1, squishable:1,"max-speed":10, 'mouse-distance':20});
	});
</script>
