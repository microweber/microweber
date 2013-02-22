<? $cur = get_option('currency', 'payments');  
$num = rand(100,1000000).'.'.rand(10,100);;
?>

<div class="vSpace"></div>
<label class="mw-ui-label">Currency display</label>
<input  value="<? print ( currency_format($num, $cur)); ?>" disabled  type="text" />
<div class="vSpace"></div>
