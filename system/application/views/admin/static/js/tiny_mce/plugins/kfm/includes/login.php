<html>
	<head>
		<style type="text/css">
			th,td,input{font:12px sans-serif}
			body{margin:0;padding:0;text-align:center;background:#fff}
			form{padding:0;margin:200px auto 0;text-align:left;width:300px;border:1px solid #006;background:#f0f0f0}
			em{margin:0 auto;display:block}
			table{width:100%}
			th[colspan=2]{font-size:2em}
			td{vertical-align:top}
		</style>
		<title>KFM - Kae's File Manager - Login</title>
	</head>
	<body>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
			<table>
				<tr>
					<th colspan="2">KFM Login</th>
				</tr>
				<tr>
					<th>Username</th><td><input name="username" /></td>
				</tr>
				<tr>
					<th>Password</th><td><input type="password" name="password" /></td>
				</tr>
				<tr>
					<th colspan="2"><input type="submit" value="Login" /></th>
				</tr>
			</table>
		</form>
		<?php if($err)echo $err; ?>
	</body>
</html>
