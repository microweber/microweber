<?php /**
* Multibyte safe version of trim()
* Always strips whitespace characters (those equal to \s)
*
* @author Peter Johnson
* @email phpnet@rcpt.at
* @param $string The string to trim
* @param $chars Optional list of chars to remove from the string ( as per trim() )
* @param $chars_array Optional array of preg_quote'd chars to be removed
* @return string
*/
 function mb_trim( $string, $chars = "", $chars_array = array() )
{
    for( $x=0; $x<iconv_strlen( $chars ); $x++ ) $chars_array[] = preg_quote( iconv_substr( $chars, $x, 1 ) );
    $encoded_char_list = implode( "|", array_merge( array( "\s","\t","\n","\r", "\0", "\x0B" ), $chars_array ) );

    $string = mb_ereg_replace( "^($encoded_char_list)*", "", $string );
    $string = mb_ereg_replace( "($encoded_char_list)*$", "", $string );
    return $string;
}


//var_dump($_POST) ;
$mails = array();
foreach($_POST as $k => $v){


if(stristr($v, 'mailform_subject')){

$subj =     $v    ;


$subj =   str_ireplace('-', '', $subj );    ;
$subj =   str_ireplace('mailform_subject', '', $subj );    
$subj = ($subj);

}

if(stristr($v, 'page_id')){

$page_id =     $v    ;


$page_id =   str_ireplace('-', '', $page_id );    ;
$page_id =   str_ireplace('page_id', '', $page_id );    
$page_id = intval($page_id);

}


$v = mb_trim($v);

$string =$v;

$pattern = '/([a-z0-9])(([-a-z0-9._])*([a-z0-9]))*\@([a-z0-9])' .
'(([a-z0-9-])*([a-z0-9]))+' . '(\.([a-z0-9])([-a-z0-9_-])?([a-z0-9])+)/i';
print $subj;
preg_match ($pattern, $string, $matches);
//echo "We extracted " . $matches[0] . " from $string";
//var_dump($matches[0]);
if($matches[0] != false){
	$mails[] = $matches[0];
}
$message.=$v . "\r\n";
}



 $file123 = 'counters/count_file'.'.txt';
if (file_exists($file123))
	{
		$fil = fopen($file123, r);
		$dat = fread($fil, filesize($file123));
		$count =  $dat+1;
		fclose($fil);
		$fil = fopen($file123, w);
		fwrite($fil, $dat+1);
	}

	else
	{
		$fil = fopen($file123, w);
		fwrite($fil, 1);
		$count = '1';
		fclose($fil);
	}



$mails = array_unique($mails);
$subject='[Yomex] ' . $count .' ' .  $subj .' ' ;

  // var_dump($subject);

$headers.='From: ' . $mails[0] . ' <' . $mails[0] . '>';
$headers .= "\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: text/plain; charset=utf-8\r\n";
  $headers .= "Content-Transfer-Encoding: quoted-printable\r\n";
  $sendto = 'yomex@yomexbg.com, boksiora@gmail.com';

  $message = str_replace("<br>", "\r\n",$message);
       $message = str_replace("<br />", "\r\n",$message);







   $sendto = explode(',',$sendto);
   foreach($sendto as $to){
	   $to = trim($to);
	mail($to, $subject, $message, $headers);
	//UTF8_mail($_REQUEST['email'],$to,$subject,$message,$cc="",$bcc="");
   }
//$message.="\r\nMessage:" . $_REQUEST['message'];


$subject='[YomexBG.com] Потвърждение ';
$headers = '';
$headers.='From: ' . 'yomex@yomexbg.com' . ' <' . 'yomex@yomexbg.com' . '>';
$headers .= "\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: text/html; charset=utf-8\r\n";
  $headers .= "Content-Transfer-Encoding: quoted-printable\r\n";
  $sendto = 'yomex@yomexbg.com';
$message = file_get_contents('auto_responders/offer.html');
   
   $sendto = explode(',',$sendto);
   foreach($sendto as $to){
	   $to = trim($to);
	mail($mails[0], $subject, $message, $headers);
	//UTF8_mail($_REQUEST['email'],$to,$subject,$message,$cc="",$bcc="");
   }

?>