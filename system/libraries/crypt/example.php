<?php
// Recover password and plaintext from POST vars
$plaintext = (isset($_POST['plaintext']) ? $_POST['plaintext'] : 'Where no man has gone before.');
$password = (isset($_POST['password']) ? $_POST['password'] : 'OpenSesame');
?>
<html>
	<head>
		<title>Example usage of class.hash_crypt.php</title>
	</head>
<body>
	<h1 style="font-size:16px">Simple example encryption / decryption</h1>
	<form name="userinput" method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
		<table>
			<tr>
				<td>
					Plaintext
				</td>
				<td>
					<textarea rows="5" cols="60" name="plaintext"><?php echo $plaintext;?></textarea>
				</td>
			</tr>
			<tr>
				<td>
					Password
				</td>
				<td>
					<input type="text" name="password" value="<?php echo $password;?>" />
				</td>
			</tr>
		</table>
		<input type="submit" name="encrypt" value="Encrypt playintext" />
	</form>
	<hr>
<?php
// Did the user submit our form?
if(isset($_POST['encrypt'])) {
	$html = '';
	
	// Include class file
	require_once 'class.hash_crypt.php';	
	
	// Instantiate new hash_encryption object, feed password
	$crypt = new hash_encryption($password);
	
	// Do the encryption
	$encrypted = $crypt->encrypt($plaintext);

	// Convert ciphertext for convenient screen presentation: add linebreak every 80 chars
	$encrypted_output = '';
	$count = 0;
	while($count<strlen($encrypted)) {
		$encrypted_output .= substr($encrypted,$count,80) . "<br>";
		$count+=80;
	}
	
	// Prepare html output
	$html .= '<p>Ciphertext:</p>';
	$html .= '<p style="border: 1px solid black">' . $encrypted_output .'</p>';		
	
	$html .= '<p>By the way: The ciphertext decrypts to the following message using your password "' . htmlentities($password) . '":</p>';
	
	// Let's decrypt the ciphertext again
	$decrypted = $crypt->decrypt($encrypted);
	$html .= '<p style="border: 1px solid black">' . $decrypted . '</p>';
	
	$html .= '<h1 style="font-size:16px">Tip: Try to encrypt your plaintext using the same key several times. You will see that it will generate different ciphertext every time.</h1>';
	echo $html;
}
?>
	</body>
</html>