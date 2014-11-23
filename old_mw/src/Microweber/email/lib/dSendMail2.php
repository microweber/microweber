<?php
// Ps: Read "changelog.txt"

/**
 Como funciona o sistema de EML?
 Trabalha apenas com o body da mensagem.

 Exemplo:
 ->setSubject(assunto)
 ->setFrom(from)
 ->setTo(to)
 ->setBcc(to)
 ->setEMLFile(eml_file) -- OU -- setHTMLFile(html_file[, auto_images=t/f]) -- OU --
 ->setMessage(message)  -- OU -- importHTML(body[, images_dir])
 ->send()

 Como enviar via SMTP ou MAIL():
 ->sendThroughMail()
 ->sendThroughSMTP($smtp_server, $port=25, $user=false, $pass=false, $ssl=[0=nao,1=sim,2=mixed])

 Como utilizar CALLBACK():
 ->send($startInPart, $callBack)
 Callback($part=LoopStart|LoopEnd, $loopPart, $totalLoops)
 Retornos poss�veis:
 "STOP" (For�a a parada imediata da execu��o)

 Bloqueio de e-mails duplicados:
 ->allowDupe()
 ->blockDupe() (Default)

 Author: IMAGINACOM
 E-mail: contato AT IMAGINACOM.com
 **/

class dSendMail2 extends htmlMimeMail {
	var $to = '"Destinatario" <nobody@noreply.com.br>';
	var $error = false;
	var $debug = 0;
	var $timeout = 15;

	var $delay = 1;
	var $groupAmnt = false;
	var $sendThrough = false;
	var $blockDupe = true;
	var $localhost = false;

	var $logFolder = false;
	var $logFile = false;

	static $StaticSendThrough = 'default';

	/** Static **/
	Function getVersion() {
		return "v2.37";
	}

	/** Public **/
	Function __construct() {
		$this->htmlMimeMail();

		if (self::$StaticSendThrough !== 'default')
			$this->sendThrough = self::$StaticSendThrough;

		/** Default values: **/
		$this->setGroupAmount(20);
		$this->setSMTPTimeout(15);
		$this->blockDupe();
		$this->setPriority(3);
		$this->setCrlf("\n");
		$this->setCharset('ISO-8859-1');
	}

	static Function easyMail($to, $subject, $message, $from = false, $html = false, $attach = false) {
		$this->setTo($to);
		$this->setSubject($subject);
		$this->setMessage($message, $html);

		if ($from)
			$this->setFrom($from);
		if ($attach)
			foreach ($attach as $att) {
				if (!isset($att[1]))
					die("Attach precisa ser: Array(Array(filename, filedata), Array(filename, filedata), ...)");

				$this->autoAttachFile($att[0], $att[1]);
			}

		return $this->send();
	}

	static Function createFromMail($to, $subject, $message, $headers = false) {
		$m = new dSendMail2;
		$m -> headers = $headers ? self::_convertStrHeaderToArray($headers) : Array();

		if (!isset($m -> headers['From']) || !$m -> headers['From']) {
			if (isset($_SERVER['SERVER_HOST']))
				$m -> setFrom("nobody@{$_SERVER['SERVER_NAME']}");
			elseif (isset($_SERVER['HTTP_HOST']) && preg_match("/^[A-Za-z0-9\-\_\.\:]+$", $_SERVER['HTTP_HOST']))
				$m -> setFrom("nobody@{$_SERVER['SERVER_HOST']}");
			elseif (isset($_SERVER['REMOTE_ADDR']))
				$m -> setFrom("nobody@{$_SERVER['REMOTE_ADDR']}");
			elseif (isset($_SERVER['USERDOMAIN']))
				$m -> setFrom("nobody@{$_SERVER['USERDOMAIN']}");
			else
				$m -> setFrom('nobody@nowhere.com');
		}

		$isHTML = (isset($m -> headers['Content-Type']) && stripos($m -> headers['Content-Type'], "text/html") !== false);

		$m -> setTo($to);
		$m -> setSubject($subject);
		$m -> setMessage($message, $isHTML);
		return $m;
	}

	Function setHTMLFile($filename, $importImages = true) {// Can receive an HTML File, and auto-attach all images to mass send
		$this->error = false;
		if (!is_readable($filename)) {
			$this->error = "Erro lendo o arquivo enviado.";
			return false;
		}

		if ($importImages === true) {
			$importImages = dirname($filename);
		}

		$this->importHTML(file_get_contents($filename), $importImages);
		return true;
	}

	Function setEMLFile($filename) {// Can receive an EML File to mass send
		$this->error = false;
		if (!is_readable($filename)) {
			$this->error = "Erro lendo o arquivo enviado.";
			return false;
		}

		$this->importEML(file_get_contents($filename));
		return true;
	}

	Function setMessage($body, $html = true, $nl2br = false) {// Defines the message contents
		($html) ? $this->html = ($nl2br ? nl2br($body) : $body) : $this->text = ($body);
	}

	Function replaceMessage($from, $to) {// Useful if you want to change something after calling importHTML
		$this->html = str_replace($from, $to, $this->html);
	}

	Function setPriority($priority) {// Defines the message priority (1=High 3=Normal 5=Low, only for Outlook)
		if (isset($this->headers['X-Priority']) || in_array($priority, Array(1, 5))) {
			$this->headers['X-Priority'] = $priority;
		}
	}

	Function setCharset($charset) {// Defines the charset. ISO-8859-1 is the default.
		// Usually: ISO-8859-1 or UTF-8
		// Default: ISO-8859-1 (Latin1)
		$this->headers['Content-Type'] = $charset;
		$this->build_params['html_charset'] = $charset;
		$this->build_params['text_charset'] = $charset;
		$this->build_params['head_charset'] = $charset;
	}

	Function setSubject($subject) {
		$subject = str_replace(Array("\r", "\n", "\r\n"), "_", $subject);
		return parent::setSubject($subject);
	}

	Function setTo($to) {// Se the 'To' (Visible)
		if (!is_array($to)) {
			$to = str_replace(";", ",", $to);
			$to = explode(",", $to);
		}
		$to = array_map(Array($this, '_normalizeEmailAddress'), $to);
		if ($this->blockDupe)
			$to = array_unique($to);
		$to = implode(",", $to);

		$this->to = $to;
	}

	Function setCc($to) {
		if (!is_array($to)) {
			$to = str_replace(";", ",", $to);
			$to = explode(",", $to);
		}
		$to = array_map(Array($this, '_normalizeEmailAddress'), $to);
		if ($this->blockDupe)
			$to = array_unique($to);
		$to = implode(",", $to);

		return parent::setCc($to);
	}

	Function setBcc($to) {
		if (!is_array($to)) {
			$to = str_replace(";", ",", $to);
			$to = explode(",", $to);
		}
		$to = array_map(Array($this, '_normalizeEmailAddress'), $to);
		if ($this->blockDupe)
			$to = array_unique($to);
		$to = implode(",", $to);

		return parent::setBcc($to);
	}

	Function setFrom($from, $nome = false) {
		if ($nome)
			$from = "\"{$nome}\" <$from>";

		$from = str_replace(Array("\r", "\n", "\r\n"), "_", $from);
		return parent::setFrom($from);
	}

	Function setReplyTo($email, $nome = false) {
		if ($nome)
			$email = "\"{$nome}\" <$email>";

		$email = str_replace(Array("\r", "\n", "\r\n"), "_", $email);
		$this->headers['Reply-To'] = $email;
	}

	Function setReturnPath($email, $nome = false) {
		if ($nome)
			$email = "\"{$nome}\" <$email>";

		$email = str_replace(Array("\r", "\n", "\r\n"), "_", $email);
		$this->headers['Return-Path'] = $email;
	}

	Function send($startInPart = 1, $callBack = false) {// Send the message, loop if necessary, save to database if necessary
		$this->error = false;
		if (!$this->is_built)
			$this->buildMessage();

		$this->output = str_replace("\r\n", "\n", $this->output);

		if (!isset($this->headers['From'])) {
			return array("error" => "Cannot proceed: You must call 'setFrom(email, name)' before sending e-mails.\r\n");
		}
		if (!isset($this->to)) {
			return array("error" => "Cannot proceed: You must call 'setTo(email)' before sending e-mails.\r\n");
		}
		// Normaliza campo "From" e "To:"
		$this->headers['From'] = $this->_normalizeEmail($this->headers['From']);
		$this->to = $this->_normalizeEmail($this->to);

		/** Define vari�veis importantes para a elabora��o dos v�rios la�os **/
		$hasCc = !empty($this->headers['Cc']);
		$hasBcc = !empty($this->headers['Bcc']);

		if ($hasBcc) {
			$parts_bcc = explode(",", $this->headers['Bcc']);
			unset($this->headers['Bcc']);
		}
		$sizeTo = substr_count($this->to, ",") + 1;
		$sizeCc = $hasCc ? (substr_count($this->headers['Cc'], ",") + 1) : 0;
		$sizeBcc = $hasBcc ? count($parts_bcc) : 0;

		$loopSize = $this->groupAmnt;
		$loopSize -= ($sizeTo + $sizeCc);
		// Considera os 'destinos' fixos, que s�o enviados em todos os la�os.

		$loopPart = $startInPart ? $startInPart : 1;
		$totalLoops = ceil($sizeBcc / $loopSize);

		// Prepara vari�veis para o padr�o
		$subject = $this->headers['Subject'];
		unset($this->headers['Subject']);

		// Get flat representation of headers
		foreach ($this->headers as $name => $value)
			$headers[] = $name . ': ' . $this->_encodeHeader($value, $this->build_params['head_charset']);

		$to = $this->_encodeHeader($this->to, $this->build_params['head_charset']);

		$this->_log("Iniciando envio . . .");
		$this->_log("From:    {$this->headers['From']}");
		$this->_log("To:      {$this->to}");
		$this->_log("Subject: {$subject}");
		$this->_log("--------------------------");
		$this->_log("Headers:");
		$this->_log(print_r($this->headers, true));
		$this->_log("Output:");
		$this->_log(print_r($this->output, true));
		$this->_log("--------------------------");
		$this->_log("Delay:     {$this->delay}");
		$this->_log("sizeTo:    {$sizeTo}");
		$this->_log("sizeCc:    {$sizeCc}");
		$this->_log("sizeBcc:   {$sizeBcc}");
		$this->_log("groupAmnt: {$this->groupAmnt}");
		$this->_log("Tamanho do la�o: {$loopSize}");
		$this->_log("Loop inicial:    {$startInPart}");
		$this->_log("Loop m�ximo:     {$totalLoops}");
		$this->_log("--------------------------");

		$orHeaders = $headers;
		/** Entra no loop para enviar mensagens **/
		do {
			$this->_log("Loop #{$loopPart}");
			if ($callBack) {
				$cbResult = call_user_func($callBack, 'LoopStart', $loopPart, $totalLoops);
				$this->_log("Resultado do callback LoopStart: {$cbResult}");
				if (substr($cbResult, 0, 4) == 'STOP')
					break;
			}

			$headers = $orHeaders;
			if ($hasBcc) {
				$headbcc = join(",", array_slice($parts_bcc, ($loopPart - 1) * $loopSize, $loopSize));
				$headers[] = "Bcc: $headbcc";
				$this->_log("+ Destinos ocultos: {$headbcc}");
			}

			if ($this->delay && $loopPart > 1) {
				$this->_log("+ Aguardando {$this->delay} para nao sobrecarregar servidor");
				sleep($this->delay);
			}
			$headersJoined = implode("\n", $headers);
			$result = $this->callMail($subject, $headersJoined);

			$this->_log("+ Resultado do envio: " . (($result === true) ? "Sucesso!" : "Falha no envio."));
			if (!$result) {
				$this->_log("FALHA CR�TICA: O loop #{$loopPart} (total de {$totalLoops}) na�o foi entregue com sucesso.");
				$this->_log("FALHA CR�TICA: Loop interrompido.");
				$this->error = "Falha no envio do laco $loopPart de $totalLoops. {$this->error}\n";
				break;
			}
			unset($headers, $headersJoined, $result, $headbcc);

			if ($callBack) {
				$cbResult = call_user_func($callBack, 'LoopEnd', $loopPart, $totalLoops);
				$this->_log("Resultado do callback LoopEnd: {$cbResult}");
				if ($cbResult == 'STOP')
					break;
			}
		} while($loopPart++ < $totalLoops);
		$this->_log("--------------------------");
		$this->_log(". . . Conclu�do!");

		// Reset the subject in case mail is resent
		if ($subject !== '') {
			$this->headers['Subject'] = $subject;
		}

		return $this->error ? false : true;
	}

	Function autoAttachFile($filename, &$filedata) {// Auto-detect if need to attach or embed the attachment. This need to be called after setMessage()
		// $filename = basename($filename);
		if ($this->html && preg_match('/(?:"|\')' . preg_quote($filename, '/') . '(?:"|\')/Ui', $this->html)) {
			$this->embedFile($filename, $filedata);
			$this->_log("Adicionando HTMLImage: $filename (" . strlen($filedata) . " bytes)");
			return 1;
		} else {
			$this->attachFile(basename($filename), $filedata);
			$this->_log("Adicionando Attachment: $filename (" . strlen($filedata) . " bytes)");
			return 2;
		}
	}

	Function attachFile($filename, &$filedata) {
		return $this->addAttachment($filedata, basename($filename), $this->_getAutoMimeType($filename));
	}

	Function embedFile($filename, &$filedata) {
		$cid = $this->addHtmlImage($filedata, $filename, $this->_getAutoMimeType($filename));
		return $cid ? "CID:{$cid}" : false;
	}

	Function allowDupe($yesno = true) {// Default: False
		$this->blockDupe = !$yesno;
	}

	Function blockDupe($yesno = true) {// Default: True
		$this->allowDupe(!$yesno);
	}

	Function setGroupAmount($amount) {// Default: 20
		$this->groupAmnt = $amount;
	}

	Function importHTML($body, $baseDir = false, $importImages = true) {// Auto-detect all images inside an HTML body and embed it as attachments.
		if (!$baseDir)
			$importImages = false;
		$body2 = str_ireplace('{SITE_URL}', '', $body);
		//$body2 = str_ireplace('{SITE_URL}', '', $body);
		//$body2 = $this->app->url->replace_site_url_back($body);
		$this->setMessage($body2, true, false);
		// body, html, force_nl2br
		if ($importImages) {
			if ($importImages && strpos($baseDir, '/') === null)
				die("dSendMail2 - importHTML() - Par�metro '\$baseDir' deve ter um caminho absoluto, n�o relativo. Atualmente, '{$baseDir}'.");

			$tags = preg_match_all("/<.+?(src|background)=[\"']?(.+?)[\"' ].*?>/is", $body, $out);
			if ($tags)
				foreach ($out[2] as $addFile) {
					if (strpos($addFile, "://")) {
						continue;
					} else {

						$dieCritical = false;

						$addFile = str_ireplace('{SITE_URL}', '', $addFile);

						if (file_exists("$baseDir/$addFile")) {
							$this->autoAttachFile($addFile, file_get_contents("$baseDir/$addFile"));
						} else {
							echo "HTML Pediu o arquivo: '{$addFile}', mas este n�o existe em '{$baseDir}/{$addFile}'<br />\r\n";
							$dieCritical = true;
						}
						if ($dieCritical) {
							die("Arquivos encontrados no HTML mas n�o foram encontrados na pasta. Cancelando envio.\r\n");
						}
					}
				}
		}
		return true;
	}

	Function importEML($fileBody, $importAll = false) {
		/** Primeiro passo: Separar header/body e importar o header**/
		preg_match("/^(.+?)\r?\n\r?\n(.+)$/s", $fileBody, $emlContentsParts);

		/** Segundo passo: Definir constru��o da mensagem **/
		$this->setMessage("Esta mensagem foi enviada diretamente em formato EML.\nPara ver seu conte�do, ser� necess�rio fazer o download.");
		$this->headers = $this->_convertStrHeaderToArray($emlContentsParts[1]);
		$this->output = isset($emlContentsParts[2]) ? $emlContentsParts[2] : '';
		$this->is_built = true;

		if (!$importAll) {
			// Remove headers that will be ignored or overwritted
			unset($this->headers["X-Unsent"]);
			unset($this->headers["From"]);
			unset($this->headers["To"]);
			unset($this->headers["Cc"]);
			unset($this->headers["Bcc"]);
		} else {
			// Import all headers, and parse some of them
			$this->setTo($this->headers['To']);
			unset($this->headers["To"]);
		}
	}

	Function exportEML($setUnsent = false) {
		if (!$this->is_built)
			$this->buildMessage();

		$ret = "";
		foreach ($this->headers as $key => $value)
			$ret .= "{$key}: " . trim($value) . "\n";

		if ($setUnsent)
			$ret .= "X-Unsent: 1\n";

		$ret .= "\n";
		$ret .= $this->output;
		return $ret;
	}

	Function setSMTPTimeout($timeout) {// Default: 15 (seconds)
		$this->timeout = $timeout;
	}

	Function sendThroughSMTP($smtp_server, $port = 25, $user = false, $pass = false, $ssl = false) {
		$this->sendThrough = Array($smtp_server, $port, $user, $pass, $ssl);

	}

	Function sendThroughMail() {
		$this->sendThrough = false;
	}

	Function sendThroughGMail($user, $pass) {
		$this->sendThroughSMTP('smtp.gmail.com', 465, $user, $pass, 1);
	}

	Function sendThroughHotMail($user, $pass) {
		$this->sendThroughSMTP('smtp.live.com', 25, $user, $pass, 2);
	}

	Function sendThroughYahoo($user, $pass) {
		$this->sendThroughSMTP('smtp.mail.yahoo.com', 465, $user, $pass, 1);
	}

	Function sendThroughNowhere($randomError = 0) {
		// Used ONLY for test purpose.
		// $randomError between 0 and 1
		$this->sendThrough = Array('NULL', $randomError);
	}

	Function sendThroughSetDefault() {
		self::$StaticSendThrough = $this->sendThrough;
	}

	Function getError() {
		return $this->error;
	}

	/** Private **/
	Function callMail($subject, $headersJoined) {
		if (!$this->sendThrough) {
			$this->_log("Enviando atrav�s da fun��o MAIL()");
			$sendRes = false;

			if (!mail($this->to, $subject, $this->output, $headersJoined)) {
				return false;
			} else {
				return true;
			}

		} elseif ($this->sendThrough[0] == 'NULL') {
			$errProb = $this->sendThrough[1];
			$this->_log("Modo de testes, n�o enviando para ningu�m. Possibilidade de erro: {$errProb}.");
			if ($errProb && rand(0, 100) < (($errProb > 1) ? $errProb : $errProb * 100)) {
				$this->error = "Simulando erro de testes.";
				return false;
			}
			return true;
		} else {
			$this->_log("Enviando atrav�s de SMTP");

			$user = $this->sendThrough[2];
			$pass = $this->sendThrough[3];
			if ($user) {
				$user = explode("@", $this->sendThrough[2], 2);
				$realm = isset($user[1]) ? $user[1] : false;
				$user = $user[0];
			}

			$smtp = new smtp_class;
			$smtp -> host_name = $this->sendThrough[0];
			$smtp -> host_port = $this->sendThrough[1];
			$smtp -> ssl = ($this->sendThrough[4] === 1 || ($this->sendThrough[4] === true));
			$smtp -> start_tls = ($this->sendThrough[4] === 2);
			$smtp -> localhost = ($this->localhost ? $this->localhost : (isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : "localhost"));
			$smtp -> timeout = $this->timeout;
			$smtp -> data_timeout = 0;
			$smtp -> debug = $this->debug;
			$smtp -> html_debug = !true;
			$smtp -> pop3_auth_host = "";
			$smtp -> user = $user ? $user : false;
			$smtp -> realm = $user ? $realm : false;
			$smtp -> password = $user ? $pass : false;

			$arheader = $this->_convertStrHeaderToArray($headersJoined);
			if (!isset($arheader['From']))
				$arheader['From'] = $this->headers['From'];
			if (!isset($arheader['To']))
				$arheader['To'] = $this->to;
			if (!isset($arheader['Subject']))
				$arheader['Subject'] = $subject;
			if (!isset($arheader['Date']))
				$arheader['Date'] = date('r');

			$bccTo = false;
			if (isset($arheader['Bcc'])) {
				$bccTo = $arheader['Bcc'];
				unset($arheader['Bcc']);
				;
			}

			$newheader = Array();
			foreach ($arheader as $key => $headerline) {
				$newheader[] = "{$key}: {$headerline}";
			}
			$ok = $smtp -> SendMessage($this->_normalizeEmail($this->headers['From'], true), explode(",", $this->to . ($bccTo ? ",{$bccTo}" : "")), $newheader, $this->output);
			if (!$ok) {
				$this->error = $smtp -> error;
				return false;
			}
			return true;
		}

		return array("error" => "Don't know how to send.");
	}

	Function _log($event) {
		if ($this->logFolder) {
			if (!is_writable($this->logFolder))
				return array("error" => "Imposs�vel enviar e-mails - Pasta de log sem permiss�es. ($this->logFolder)");

			if (!$this->logFile)
				$this->logFile = fopen("{$this->logFolder}/log-" . date('d.m.Y-H.i.s') . '-' . substr(uniqid(), -4) . ".txt", "a+");

			$toLog = date('d/m/Y H:i:s') . " " . trim($event) . "\r\n";
			fwrite($this->logFile, $toLog);
		}
	}

	Function _getAutoMimeType($filename) {
		$ext = strtolower(substr($filename, -3));
		switch($ext) {
			case ".xls" :
				$content_type = "application/excel";
				break;
			case ".hqx" :
				$content_type = "application/macbinhex40";
				break;
			case ".doc" :
			case ".dot" :
			case ".wrd" :
				$content_type = "application/msword";
				break;
			case ".pdf" :
				$content_type = "application/pdf";
				break;
			case ".pgp" :
				$content_type = "application/pgp";
				break;
			case ".ps" :
			case ".eps" :
			case ".ai" :
				$content_type = "application/postscript";
				break;
			case ".ppt" :
				$content_type = "application/powerpoint";
				break;
			case ".rtf" :
				$content_type = "application/rtf";
				break;
			case ".tgz" :
			case ".gtar" :
				$content_type = "application/x-gtar";
				break;
			case ".gz" :
				$content_type = "application/x-gzip";
				break;
			case ".php" :
			case ".php3" :
				$content_type = "application/x-httpd-php";
				break;
			case ".js" :
				$content_type = "application/x-javascript";
				break;
			case ".ppd" :
			case ".psd" :
				$content_type = "application/x-photoshop";
				break;
			case ".swf" :
			case ".swc" :
			case ".rf" :
				$content_type = "application/x-shockwave-flash";
				break;
			case ".tar" :
				$content_type = "application/x-tar";
				break;
			case ".zip" :
				$content_type = "application/zip";
				break;
			case ".mid" :
			case ".midi" :
			case ".kar" :
				$content_type = "audio/midi";
				break;
			case ".mp2" :
			case ".mp3" :
			case ".mpga" :
				$content_type = "audio/mpeg";
				break;
			case ".ra" :
				$content_type = "audio/x-realaudio";
				break;
			case ".wav" :
				$content_type = "audio/wav";
				break;
			case ".bmp" :
				$content_type = "image/bitmap";
				break;
			case ".gif" :
				$content_type = "image/gif";
				break;
			case ".iff" :
				$content_type = "image/iff";
				break;
			case ".jb2" :
				$content_type = "image/jb2";
				break;
			case ".jpg" :
			case ".jpe" :
			case ".jpeg" :
				$content_type = "image/jpeg";
				break;
			case ".jpx" :
				$content_type = "image/jpx";
				break;
			case ".png" :
				$content_type = "image/png";
				break;
			case ".tif" :
			case ".tiff" :
				$content_type = "image/tiff";
				break;
			case ".wbmp" :
				$content_type = "image/vnd.wap.wbmp";
				break;
			case ".xbm" :
				$content_type = "image/xbm";
				break;
			case ".css" :
				$content_type = "text/css";
				break;
			case ".txt" :
				$content_type = "text/plain";
				break;
			case ".htm" :
			case ".html" :
				$content_type = "text/html";
				break;
			case ".xml" :
				$content_type = "text/xml";
				break;
			case ".mpg" :
			case ".mpe" :
			case ".mpeg" :
				$content_type = "video/mpeg";
				break;
			case ".qt" :
			case ".mov" :
				$content_type = "video/quicktime";
				break;
			case ".avi" :
				$content_type = "video/x-ms-video";
				break;
			case ".eml" :
				$content_type = "message/rfc822";
				break;
			default :
				$content_type = "application/octet-stream";
				break;
		}
		return $content_type;
	}

	Function & _convertStrHeaderToArray($header) {
		$headers = Array();
		$strHeader = explode("\n", $header);
		foreach ($strHeader as $lineHeader) {
			$parts = explode(": ", $lineHeader, 2);
			if (sizeof($parts) == 2) {
				// If correct format (Title: value)
				$lastHeaderTitle = implode("-", array_map('ucfirst', explode("-", $parts[0])));
				if (preg_match("/^(from|to|cc|bcc|subject)$/i", $lastHeaderTitle))// Check if the essential headers are correctly written
					$lastHeaderTitle = ucfirst(strtolower($lastHeaderTitle));
				// The whole string is lower-case, but the first letter
				$headers[$lastHeaderTitle] = rtrim($parts[1]);
			} else// It's a continuation of the previous Title
			if (!isset($lastHeaderTitle))// But there's no previous title! Weird...
				$headers[rtrim($parts[0])] = "";
			else
				$headers[$lastHeaderTitle] .= "\n" . rtrim($parts[0]);
		}
		return $headers;
	}

	Function _normalizeEmail($email, $only_address = false) {
		if (!strpos($email, "<")) {
			// Padr�o: a@b.c
			return trim(str_replace(Array(" ", "<", ">"), "", strtolower($email)));
		}

		// Padr�o: "Nome" <a@b.c>
		$nome = trim(str_replace("\"", "", substr($email, 0, strpos($email, "<"))));
		$email = substr($email, strpos($email, "<"));
		$email = trim(str_replace(Array(" ", "<", ">"), "", $email));
		$email = strtolower($email);

		return $only_address ? $email : "\"{$nome}\" <$email>";
	}

	Function _normalizeEmailAddress($email) {
		return $this->_normalizeEmail($email, true);
	}

}

// MimeMessage Support
class htmlMimeMail {
	var $html;
	var $text;
	var $output;
	var $html_text;
	var $html_images;
	var $image_types;
	var $build_params;
	var $attachments;
	var $headers;
	var $is_built;
	var $return_path;
	var $smtp_params;

	function htmlMimeMail() {
		$this->html_images = array();
		$this->headers = array();
		$this->is_built = false;

		$this->image_types = array('gif' => 'image/gif', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'jpe' => 'image/jpeg', 'bmp' => 'image/bmp', 'png' => 'image/png', 'tif' => 'image/tiff', 'tiff' => 'image/tiff', 'swf' => 'application/x-shockwave-flash');

		/**
		 * Set these up
		 */
		$this->build_params['html_encoding'] = '7bit';
		$this->build_params['text_encoding'] = '7bit';
		$this->build_params['html_charset'] = 'ISO-8859-1';
		$this->build_params['text_charset'] = 'ISO-8859-1';
		$this->build_params['head_charset'] = 'ISO-8859-1';
		$this->build_params['text_wrap'] = 998;

		/**
		 * Defaults for smtp sending
		 */
		if (!empty($GLOBALS['HTTP_SERVER_VARS']['HTTP_HOST'])) {
			$helo = $GLOBALS['HTTP_SERVER_VARS']['HTTP_HOST'];
		} elseif (!empty($GLOBALS['HTTP_SERVER_VARS']['SERVER_NAME'])) {
			$helo = $GLOBALS['HTTP_SERVER_VARS']['SERVER_NAME'];
		} else {
			$helo = 'localhost';
		}

		$this->smtp_params['host'] = 'localhost';
		$this->smtp_params['port'] = 25;
		$this->smtp_params['helo'] = $helo;
		$this->smtp_params['auth'] = false;
		$this->smtp_params['user'] = '';
		$this->smtp_params['pass'] = '';

		/**
		 * Make sure the MIME version header is first.
		 */
		$this->headers['MIME-Version'] = '1.0';
	}

	/**
	 * This function will read a file in
	 * from a supplied filename and return
	 * it. This can then be given as the first
	 * argument of the the functions
	 * add_html_image() or add_attachment().
	 */
	function getFile($filename) {
		$return = '';
		if ($fp = fopen($filename, 'rb')) {
			while (!feof($fp)) {
				$return .= fread($fp, 1024);
			}
			fclose($fp);
			return $return;

		} else {
			return false;
		}
	}

	/**
	 * Accessor to set the CRLF style
	 */
	function setCrlf($crlf = "\n") {
		if (!defined('CRLF')) {
			define('CRLF', $crlf, true);
		}

		if (!defined('MAIL_MIMEPART_CRLF')) {
			define('MAIL_MIMEPART_CRLF', $crlf, true);
		}
	}

	/**
	 * Accessor to set the SMTP parameters
	 */
	function setSMTPParams($host = null, $port = null, $helo = null, $auth = null, $user = null, $pass = null) {
		if (!is_null($host))
			$this->smtp_params['host'] = $host;
		if (!is_null($port))
			$this->smtp_params['port'] = $port;
		if (!is_null($helo))
			$this->smtp_params['helo'] = $helo;
		if (!is_null($auth))
			$this->smtp_params['auth'] = $auth;
		if (!is_null($user))
			$this->smtp_params['user'] = $user;
		if (!is_null($pass))
			$this->smtp_params['pass'] = $pass;
	}

	/**
	 * Accessor function to set the text encoding
	 */
	function setTextEncoding($encoding = '7bit') {
		$this->build_params['text_encoding'] = $encoding;
	}

	/**
	 * Accessor function to set the HTML encoding
	 */
	function setHtmlEncoding($encoding = 'quoted-printable') {
		$this->build_params['html_encoding'] = $encoding;
	}

	/**
	 * Accessor function to set the text charset
	 */
	function setTextCharset($charset = 'ISO-8859-1') {
		$this->build_params['text_charset'] = $charset;
	}

	/**
	 * Accessor function to set the HTML charset
	 */
	function setHtmlCharset($charset = 'ISO-8859-1') {
		$this->build_params['html_charset'] = $charset;
	}

	/**
	 * Accessor function to set the header encoding charset
	 */
	function setHeadCharset($charset = 'ISO-8859-1') {
		$this->build_params['head_charset'] = $charset;
	}

	/**
	 * Accessor function to set the text wrap count
	 */
	function setTextWrap($count = 998) {
		$this->build_params['text_wrap'] = $count;
	}

	/**
	 * Accessor to set a header
	 */
	function setHeader($name, $value) {
		$this->headers[$name] = $value;
	}

	/**
	 * Accessor to add a Subject: header
	 */
	function setSubject($subject) {
		$this->headers['Subject'] = $subject;
	}

	/**
	 * Accessor to add a From: header
	 */
	function setFrom($from) {
		$this->headers['From'] = $from;
	}

	/**
	 * Accessor to set the return path
	 */
	function setReturnPath($return_path) {
		$this->return_path = $return_path;
	}

	/**
	 * Accessor to add a Cc: header
	 */
	function setCc($cc) {
		$this->headers['Cc'] = $cc;
	}

	/**
	 * Accessor to add a Bcc: header
	 */
	function setBcc($bcc) {
		$this->headers['Bcc'] = $bcc;
	}

	/**
	 * Adds plain text. Use this function
	 * when NOT sending html email
	 */
	function setText($text = '') {
		$this->text = $text;
	}

	/**
	 * Adds a html part to the mail.
	 * Also replaces image names with
	 * content-id's.
	 */
	function setHtml($html, $text = null, $images_dir = null) {
		$this->html = $html;
		$this->html_text = $text;
		if (isset($images_dir)) {
			$this->_findHtmlImages($images_dir);
		}
	}

	/**
	 * Function for extracting images from
	 * html source. This function will look
	 * through the html code supplied by add_html()
	 * and find any file that ends in one of the
	 * extensions defined in $obj->image_types.
	 * If the file exists it will read it in and
	 * embed it, (not an attachment).
	 *
	 * @author Dan Allen
	 */
	function _findHtmlImages($images_dir) {
		// Build the list of image extensions
		while (list($key, ) = each($this->image_types)) {
			$extensions[] = $key;
		}

		preg_match_all('/(?:"|\')([^"\']+\.(' . implode('|', $extensions) . '))(?:"|\')/Ui', $this->html, $images);

		for ($i = 0; $i < count($images[1]); $i++) {
			if (file_exists($images_dir . $images[1][$i])) {
				$html_images[] = $images[1][$i];
				$this->html = str_replace($images[1][$i], basename($images[1][$i]), $this->html);
			}
		}

		if (!empty($html_images)) {

			// If duplicate images are embedded, they may show up as attachments, so remove them.
			$html_images = array_unique($html_images);
			sort($html_images);

			for ($i = 0; $i < count($html_images); $i++) {
				if ($image = $this->getFile($images_dir . $html_images[$i])) {
					$ext = substr($html_images[$i], strrpos($html_images[$i], '.') + 1);
					$content_type = $this->image_types[strtolower($ext)];
					$this->addHtmlImage($image, basename($html_images[$i]), $content_type);
				}
			}
		}
	}

	/**
	 * Adds an image to the list of embedded
	 * images.
	 */
	function addHtmlImage($file, $name = '', $c_type = 'application/octet-stream') {
		$cid = md5(uniqid());
		$this->html_images[] = array('body' => $file, 'name' => $name, 'c_type' => $c_type, 'cid' => $cid);
		return $cid;
	}

	/**
	 * Adds a file to the list of attachments.
	 */
	function addAttachment($file, $name = '', $c_type = 'application/octet-stream', $encoding = 'base64') {
		$this->attachments[] = array('body' => $file, 'name' => $name, 'c_type' => $c_type, 'encoding' => $encoding);
	}

	/**
	 * Adds a text subpart to a mime_part object
	 */
	function & _addTextPart(&$obj, $text) {
		$params['content_type'] = 'text/plain';
		$params['encoding'] = $this->build_params['text_encoding'];
		$params['charset'] = $this->build_params['text_charset'];
		if (is_object($obj)) {
			$return = $obj -> addSubpart($text, $params);
		} else {
			$return = new Mail_mimePart($text, $params);
		}

		return $return;
	}

	/**
	 * Adds a html subpart to a mime_part object
	 */
	function & _addHtmlPart(&$obj) {
		$params['content_type'] = 'text/html';
		$params['encoding'] = $this->build_params['html_encoding'];
		$params['charset'] = $this->build_params['html_charset'];
		if (is_object($obj)) {
			$return = $obj -> addSubpart($this->html, $params);
		} else {
			$return = new Mail_mimePart($this->html, $params);
		}

		return $return;
	}

	/**
	 * Starts a message with a mixed part
	 */
	function & _addMixedPart() {
		$params['content_type'] = 'multipart/mixed';
		$return = new Mail_mimePart('', $params);

		return $return;
	}

	/**
	 * Adds an alternative part to a mime_part object
	 */
	function & _addAlternativePart(&$obj) {
		$params['content_type'] = 'multipart/alternative';
		if (is_object($obj)) {
			$return = $obj -> addSubpart('', $params);
		} else {
			$return = new Mail_mimePart('', $params);
		}

		return $return;
	}

	/**
	 * Adds a html subpart to a mime_part object
	 */
	function & _addRelatedPart(&$obj) {
		$params['content_type'] = 'multipart/related';
		if (is_object($obj)) {
			$return = $obj -> addSubpart('', $params);
		} else {
			$return = new Mail_mimePart('', $params);
		}

		return $return;
	}

	/**
	 * Adds an html image subpart to a mime_part object
	 */
	function _addHtmlImagePart(&$obj, $value) {
		$params['content_type'] = $value['c_type'];
		$params['encoding'] = 'base64';
		$params['disposition'] = 'inline';
		$params['dfilename'] = $value['name'];
		$params['cid'] = $value['cid'];
		$obj -> addSubpart($value['body'], $params);
	}

	/**
	 * Adds an attachment subpart to a mime_part object
	 */
	function _addAttachmentPart(&$obj, $value) {
		$params['content_type'] = $value['c_type'];
		$params['encoding'] = $value['encoding'];
		$params['disposition'] = 'attachment';
		$params['dfilename'] = $value['name'];
		$obj -> addSubpart($value['body'], $params);
	}

	/**
	 * Builds the multipart message from the
	 * list ($this->_parts). $params is an
	 * array of parameters that shape the building
	 * of the message. Currently supported are:
	 *
	 * $params['html_encoding'] - The type of encoding to use on html. Valid options are
	 *                            "7bit", "quoted-printable" or "base64" (all without quotes).
	 *                            7bit is EXPRESSLY NOT RECOMMENDED. Default is quoted-printable
	 * $params['text_encoding'] - The type of encoding to use on plain text Valid options are
	 *                            "7bit", "quoted-printable" or "base64" (all without quotes).
	 *                            Default is 7bit
	 * $params['text_wrap']     - The character count at which to wrap 7bit encoded data.
	 *                            Default this is 998.
	 * $params['html_charset']  - The character set to use for a html section.
	 *                            Default is ISO-8859-1
	 * $params['text_charset']  - The character set to use for a text section.
	 *                          - Default is ISO-8859-1
	 * $params['head_charset']  - The character set to use for header encoding should it be needed.
	 *                          - Default is ISO-8859-1
	 */
	function buildMessage($params = array()) {
		if (!empty($params)) {
			while (list($key, $value) = each($params)) {
				$this->build_params[$key] = $value;
			}
		}

		if (!empty($this->html_images)) {
			foreach ($this->html_images as $value) {
				$this->html = str_replace($value['name'], 'cid:' . $value['cid'], $this->html);
			}
		}

		$null = null;
		$attachments = !empty($this->attachments) ? true : false;
		$html_images = !empty($this->html_images) ? true : false;
		$html = !empty($this->html) ? true : false;
		$text = isset($this->text) ? true : false;

		switch (true) {
			case $text AND !$attachments :
				$message = &$this->_addTextPart($null, $this->text);
				break;

			case !$text AND $attachments AND !$html :
				$message = &$this->_addMixedPart();

				for ($i = 0; $i < count($this->attachments); $i++) {
					$this->_addAttachmentPart($message, $this->attachments[$i]);
				}
				break;

			case $text AND $attachments :
				$message = &$this->_addMixedPart();
				$this->_addTextPart($message, $this->text);

				for ($i = 0; $i < count($this->attachments); $i++) {
					$this->_addAttachmentPart($message, $this->attachments[$i]);
				}
				break;

			case $html AND !$attachments AND !$html_images :
				if (!is_null($this->html_text)) {
					$message = &$this->_addAlternativePart($null);
					$this->_addTextPart($message, $this->html_text);
					$this->_addHtmlPart($message);
				} else {
					$message = &$this->_addHtmlPart($null);
				}
				break;

			case $html AND !$attachments AND $html_images :
				if (!is_null($this->html_text)) {
					$message = &$this->_addAlternativePart($null);
					$this->_addTextPart($message, $this->html_text);
					$related = &$this->_addRelatedPart($message);
				} else {
					$message = &$this->_addRelatedPart($null);
					$related = &$message;
				}
				$this->_addHtmlPart($related);
				for ($i = 0; $i < count($this->html_images); $i++) {
					$this->_addHtmlImagePart($related, $this->html_images[$i]);
				}
				break;

			case $html AND $attachments AND !$html_images :
				$message = &$this->_addMixedPart();
				if (!is_null($this->html_text)) {
					$alt = &$this->_addAlternativePart($message);
					$this->_addTextPart($alt, $this->html_text);
					$this->_addHtmlPart($alt);
				} else {
					$this->_addHtmlPart($message);
				}
				for ($i = 0; $i < count($this->attachments); $i++) {
					$this->_addAttachmentPart($message, $this->attachments[$i]);
				}
				break;

			case $html AND $attachments AND $html_images :
				$message = &$this->_addMixedPart();
				if (!is_null($this->html_text)) {
					$alt = &$this->_addAlternativePart($message);
					$this->_addTextPart($alt, $this->html_text);
					$rel = &$this->_addRelatedPart($alt);
				} else {
					$rel = &$this->_addRelatedPart($message);
				}
				$this->_addHtmlPart($rel);
				for ($i = 0; $i < count($this->html_images); $i++) {
					$this->_addHtmlImagePart($rel, $this->html_images[$i]);
				}
				for ($i = 0; $i < count($this->attachments); $i++) {
					$this->_addAttachmentPart($message, $this->attachments[$i]);
				}
				break;
		}

		if (isset($message)) {
			$output = $message -> encode();
			$this->output = $output['body'];
			$this->headers = array_merge($this->headers, $output['headers']);

			// Add message ID header
			srand((double)microtime() * 10000000);
			$message_id = sprintf('<%s.%s@%s>', base_convert(time(), 10, 36), base_convert(rand(), 10, 36), isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'LOCALHOST');
			$this->headers['Message-ID'] = $message_id;

			$this->is_built = true;
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Function to encode a header if necessary
	 * according to RFC2047
	 */
	function _encodeHeader($input, $charset = 'ISO-8859-1') {
		preg_match_all('/(\s?\w*[\x80-\xFF]+\w*\s?)/', $input, $matches);
		foreach ($matches[1] as $value) {
			$replacement = preg_replace('/([\x20\x80-\xFF])/e', '"=" . strtoupper(dechex(ord("\1")))', $value);
			$input = str_replace($value, '=?' . $charset . '?Q?' . $replacement . '?=', $input);
		}

		return $input;
	}

	/**
	 * Sends the mail.
	 *
	 * @param  array  $recipients
	 * @param  string $type OPTIONAL
	 * @return mixed
	 */
	function send($recipients, $type = 'mail') {
		if (!defined('CRLF')) {
			$this->setCrlf($type == 'mail' ? "\n" : "\r\n");
		}

		if (!$this->is_built) {
			$this->buildMessage();
		}

		switch ($type) {
			case 'mail' :
				$subject = '';
				if (!empty($this->headers['Subject'])) {
					$subject = $this->_encodeHeader($this->headers['Subject'], $this->build_params['head_charset']);
					unset($this->headers['Subject']);
				}

				// Get flat representation of headers
				foreach ($this->headers as $name => $value) {
					$headers[] = $name . ': ' . $this->_encodeHeader($value, $this->build_params['head_charset']);
				}

				$to = $this->_encodeHeader(implode(', ', $recipients), $this->build_params['head_charset']);

				if (!empty($this->return_path)) {
					$result = mail($to, $subject, $this->output, implode(CRLF, $headers), '-f' . $this->return_path);
				} else {
					$result = mail($to, $subject, $this->output, implode(CRLF, $headers));
				}

				// Reset the subject in case mail is resent
				if ($subject !== '') {
					$this->headers['Subject'] = $subject;
				}

				// Return
				return $result;
				break;

			case 'smtp' :
				die("Classe SMTP n�o est� implementada.");
				break;
		}
	}

	/**
	 * Use this method to return the email
	 * in message/rfc822 format. Useful for
	 * adding an email to another email as
	 * an attachment. there's a commented
	 * out example in example.php.
	 */
	function getRFC822($recipients, $type = 'mail') {
		// Make up the date header as according to RFC822
		$this->setHeader('Date', date('D, d M y H:i:s O'));

		if (!defined('CRLF')) {
			$this->setCrlf("\n");
		}

		if (!$this->is_built) {
			$this->buildMessage();
		}

		// Return path ?
		if (isset($this->return_path)) {
			$headers[] = 'Return-Path: ' . $this->return_path;
		}

		// Get flat representation of headers
		foreach ($this->headers as $name => $value) {
			$headers[] = $name . ': ' . $value;
		}
		$headers[] = 'To: ' . implode(', ', $recipients);

		return implode(CRLF, $headers) . CRLF . CRLF . $this->output;
	}

}// End of class.

class Mail_mimePart {
	var $_encoding;
	// The encoding type of this part
	var $_subparts;
	// An array of subparts
	var $_encoded;
	// The output of this part after being built
	var $_headers;
	// Headers for this part
	var $_body;
	// The body of this part (not encoded)

	/**
	 * Constructor.
	 *
	 * Sets up the object.
	 *
	 * @param $body   - The body of the mime part if any.
	 * @param $params - An associative array of parameters:
	 *                  content_type - The content type for this part eg multipart/mixed
	 *                  encoding     - The encoding to use, 7bit, 8bit, base64, or quoted-printable
	 *                  cid          - Content ID to apply
	 *                  disposition  - Content disposition, inline or attachment
	 *                  dfilename    - Optional filename parameter for content disposition
	 *                  description  - Content description
	 *                  charset      - Character set to use
	 * @access public
	 */
	function Mail_mimePart($body = '', $params = array()) {
		if (!defined('MAIL_MIMEPART_CRLF')) {
			define('MAIL_MIMEPART_CRLF', defined('MAIL_MIME_CRLF') ? MAIL_MIME_CRLF : "\r\n", TRUE);
		}

		foreach ($params as $key => $value) {
			switch ($key) {
				case 'content_type' :
					$headers['Content-Type'] = $value . (isset($charset) ? '; charset="' . $charset . '"' : '');
					break;

				case 'encoding' :
					$this->_encoding = $value;
					$headers['Content-Transfer-Encoding'] = $value;
					break;

				case 'cid' :
					$headers['Content-ID'] = '<' . $value . '>';
					break;

				case 'disposition' :
					$headers['Content-Disposition'] = $value . (isset($dfilename) ? '; filename="' . $dfilename . '"' : '');
					break;

				case 'dfilename' :
					if (isset($headers['Content-Disposition'])) {
						$headers['Content-Disposition'] .= '; filename="' . $value . '"';
					} else {
						$dfilename = $value;
					}
					break;

				case 'description' :
					$headers['Content-Description'] = $value;
					break;

				case 'charset' :
					if (isset($headers['Content-Type'])) {
						$headers['Content-Type'] .= '; charset="' . $value . '"';
					} else {
						$charset = $value;
					}
					break;
			}
		}

		// Default content-type
		if (!isset($headers['Content-Type'])) {
			$headers['Content-Type'] = 'text/plain';
		}

		//Default encoding
		if (!isset($this->_encoding)) {
			$this->_encoding = '7bit';
		}

		// Assign stuff to member variables
		$this->_encoded = array();
		$this->_headers = $headers;
		$this->_body = $body;
	}

	/**
	 * encode()
	 *
	 * Encodes and returns the email. Also stores
	 * it in the encoded member variable
	 *
	 * @return An associative array containing two elements,
	 *         body and headers. The headers element is itself
	 *         an indexed array.
	 * @access public
	 */
	function encode() {
		$encoded = &$this->_encoded;

		if (!empty($this->_subparts)) {
			srand((double)microtime() * 1000000);
			$boundary = '=_' . md5(uniqid(rand()) . microtime());
			$this->_headers['Content-Type'] .= ';' . MAIL_MIMEPART_CRLF . "\t" . 'boundary="' . $boundary . '"';

			// Add body parts to $subparts
			for ($i = 0; $i < count($this->_subparts); $i++) {
				$headers = array();
				$tmp = $this->_subparts[$i] -> encode();
				foreach ($tmp['headers'] as $key => $value) {
					$headers[] = $key . ': ' . $value;
				}
				$subparts[] = implode(MAIL_MIMEPART_CRLF, $headers) . MAIL_MIMEPART_CRLF . MAIL_MIMEPART_CRLF . $tmp['body'];
			}

			$encoded['body'] = '--' . $boundary . MAIL_MIMEPART_CRLF . implode('--' . $boundary . MAIL_MIMEPART_CRLF, $subparts) . '--' . $boundary . '--' . MAIL_MIMEPART_CRLF;

		} else {
			$encoded['body'] = $this->_getEncodedData($this->_body, $this->_encoding) . MAIL_MIMEPART_CRLF . MAIL_MIMEPART_CRLF;
		}

		// Add headers to $encoded
		$encoded['headers'] = &$this->_headers;

		return $encoded;
	}

	/**
	 * &addSubPart()
	 *
	 * Adds a subpart to current mime part and returns
	 * a reference to it
	 *
	 * @param $body   The body of the subpart, if any.
	 * @param $params The parameters for the subpart, same
	 *                as the $params argument for constructor.
	 * @return A reference to the part you just added. It is
	 *         crucial if using multipart/* in your subparts that
	 *         you use =& in your script when calling this function,
	 *         otherwise you will not be able to add further subparts.
	 * @access public
	 */
	function & addSubPart($body, $params) {
		$this->_subparts[] = new Mail_mimePart($body, $params);
		return $this->_subparts[count($this->_subparts) - 1];
	}

	/**
	 * _getEncodedData()
	 *
	 * Returns encoded data based upon encoding passed to it
	 *
	 * @param $data     The data to encode.
	 * @param $encoding The encoding type to use, 7bit, base64,
	 *                  or quoted-printable.
	 * @access private
	 */
	function _getEncodedData($data, $encoding) {
		switch ($encoding) {
			case '8bit' :
			case '7bit' :
				return $data;
				break;

			case 'quoted-printable' :
				return $this->_quotedPrintableEncode($data);
				break;

			case 'base64' :
				return rtrim(chunk_split(base64_encode($data), 76, MAIL_MIMEPART_CRLF));
				break;

			default :
				return $data;
		}
	}

	/**
	 * quoteadPrintableEncode()
	 *
	 * Encodes data to quoted-printable standard.
	 *
	 * @param $input    The data to encode
	 * @param $line_max Optional max line length. Should
	 *                  not be more than 76 chars
	 *
	 * @access private
	 */
	function _quotedPrintableEncode($input, $line_max = 76) {
		$lines = preg_split("/\r?\n/", $input);
		$eol = MAIL_MIMEPART_CRLF;
		$escape = '=';
		$output = '';

		while (list(, $line) = each($lines)) {

			$linlen = strlen($line);
			$newline = '';

			for ($i = 0; $i < $linlen; $i++) {
				$char = substr($line, $i, 1);
				$dec = ord($char);

				if (($dec == 32) AND ($i == ($linlen - 1))) {// convert space at eol only
					$char = '=20';

				} elseif ($dec == 9) {; // Do nothing if a tab.
				} elseif (($dec == 61) OR ($dec < 32) OR ($dec > 126)) {
					$char = $escape . strtoupper(sprintf('%02s', dechex($dec)));
				}

				if ((strlen($newline) + strlen($char)) >= $line_max) {// MAIL_MIMEPART_CRLF is not counted
					$output .= $newline . $escape . $eol;
					// soft line break; " =\r\n" is okay
					$newline = '';
				}
				$newline .= $char;
			}// end of for
			$output .= $newline . $eol;
		}
		$output = substr($output, 0, -1 * strlen($eol));
		// Don't want last crlf
		return $output;
	}

}// End of class

class smtp_class {
	// SMTP Support
	/*
	 <version>@(#) $Id: smtp.php,v 1.41 2009/04/12 06:15:06 mlemos Exp $</version>
	 <copyright>Copyright � (C) Manuel Lemos 1999-2009</copyright>
	 <title>Sending e-mail messages via SMTP protocol</title>
	 <author>Manuel Lemos</author>
	 <authoraddress>mlemos-at-acm.org</authoraddress>
	 */
	var $user = "";
	var $realm = "";
	var $password = "";
	var $workstation = "";
	var $authentication_mechanism = "";
	var $host_name = "";
	var $host_port = 25;
	var $ssl = 0;
	var $start_tls = 0;
	var $localhost = "";
	var $timeout = 0;
	var $data_timeout = 0;
	var $direct_delivery = 0;
	var $error = "";
	var $debug = 0;
	var $html_debug = 0;
	var $esmtp = 1;
	var $esmtp_extensions = array();
	var $exclude_address = "";
	var $getmxrr = "GetMXRR";
	var $pop3_auth_host = "";
	var $pop3_auth_port = 110;

	/* private variables - DO NOT ACCESS */

	var $state = "Disconnected";
	var $connection = 0;
	var $pending_recipients = 0;
	var $next_token = "";
	var $direct_sender = "";
	var $connected_domain = "";
	var $result_code;
	var $disconnected_error = 0;
	var $esmtp_host = "";
	var $maximum_piped_recipients = 100;

	/* Private methods - DO NOT CALL */

	Function Tokenize($string, $separator = "") {
		if (!strcmp($separator, "")) {
			$separator = $string;
			$string = $this->next_token;
		}
		for ($character = 0; $character < strlen($separator); $character++) {
			if (GetType($position = strpos($string, $separator[$character])) == "integer")
				$found = (IsSet($found) ? min($found, $position) : $position);
		}
		if (IsSet($found)) {
			$this->next_token = substr($string, $found + 1);
			return (substr($string, 0, $found));
		} else {
			$this->next_token = "";
			return ($string);
		}
	}

	Function setError($error) {
		$this->error = $error;
		$this->OutputDebug("Erro ocurred: {$error}");
	}

	Function OutputDebug($message) {
		if ($this->debug) {
			$message .= "\n";
			if ($this->html_debug)
				$message = str_replace("\n", "<br />\n", HtmlEntities($message));
			echo $message;
			flush();
		}
	}

	Function SetDataAccessError($error) {
		$this->setError($error);
		if (function_exists("socket_get_status")) {
			$status = socket_get_status($this->connection);
			if ($status["timed_out"])
				$this->error .= ": data access time out";
			elseif ($status["eof"]) {
				$this->error .= ": the server disconnected";
				$this->disconnected_error = 1;
			}
		}
	}

	Function GetLine() {
		for ($line = ""; ; ) {
			if (feof($this->connection)) {
				$this->setError("reached the end of data while reading from the SMTP server conection");
				return ("");
			}
			if (GetType($data = @fgets($this->connection, 100)) != "string" || strlen($data) == 0) {
				$this->SetDataAccessError("it was not possible to read line from the SMTP server");
				return ("");
			}
			$line .= $data;
			$length = strlen($line);
			if ($length >= 1 && substr($line, -1) == "\n") {
				$this->OutputDebug("S " . str_replace(Array("\r\n", "\r", "\n"), Array("<CRNL>", "<CR>", "<NL>"), $line));
				$line = rtrim($line, "\r\n");
				return ($line);
			}
		}
	}

	Function PutLine($line) {
		$this->OutputDebug("C {$line}<CRNL>");
		if (!@fputs($this->connection, "$line\r\n")) {
			$this->SetDataAccessError("it was not possible to send a line to the SMTP server");
			return (0);
		}
		return (1);
	}

	Function PutData(&$data) {
		if (strlen($data)) {
			$this->OutputDebug("C $data");
			if (!@fputs($this->connection, $data)) {
				$this->SetDataAccessError("it was not possible to send data to the SMTP server");
				return (0);
			}
		}
		return (1);
	}

	Function VerifyResultLines($code, &$responses) {
		$responses = array();
		Unset($this->result_code);
		while (strlen($line = $this->GetLine($this->connection))) {
			if (IsSet($this->result_code)) {
				if (strcmp($this->Tokenize($line, " -"), $this->result_code)) {
					$this->setError($line);
					return (0);
				}
			} else {
				$this->result_code = $this->Tokenize($line, " -");
				if (GetType($code) == "array") {
					for ($codes = 0; $codes < count($code) && strcmp($this->result_code, $code[$codes]); $codes++);
					if ($codes >= count($code)) {
						$this->setError($line);
						return (0);
					}
				} else {
					if (strcmp($this->result_code, $code)) {
						$this->setError($line);
						return (0);
					}
				}
			}
			$responses[] = $this->Tokenize("");
			if (!strcmp($this->result_code, $this->Tokenize($line, " ")))
				return (1);
		}
		return (-1);
	}

	Function FlushRecipients() {
		if ($this->pending_sender) {
			if ($this->VerifyResultLines("250", $responses) <= 0)
				return (0);
			$this->pending_sender = 0;
		}
		for (; $this->pending_recipients; $this->pending_recipients--) {
			if ($this->VerifyResultLines(array("250", "251"), $responses) <= 0)
				return (0);
		}
		return (1);
	}

	Function ConnectToHost($domain, $port, $resolve_message) {
		if ($this->ssl) {
			$version = explode(".", function_exists("phpversion") ? phpversion() : "3.0.7");
			$php_version = intval($version[0]) * 1000000 + intval($version[1]) * 1000 + intval($version[2]);
			if ($php_version < 4003000)
				return ("establishing SSL connections requires at least PHP version 4.3.0");
			if (!function_exists("extension_loaded") || !extension_loaded("openssl"))
				return ("establishing SSL connections requires the OpenSSL extension enabled");
		}
		if (function_exists('preg_match') ? preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', $domain) : ereg('^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$', $domain))
			$ip = $domain;
		else {
			$this->OutputDebug($resolve_message);
			if (!strcmp($ip = @gethostbyname($domain), $domain))
				return ("could not resolve host \"" . $domain . "\"");
		}
		if (strlen($this->exclude_address) && !strcmp(@gethostbyname($this->exclude_address), $ip))
			return ("domain \"" . $domain . "\" resolved to an address excluded to be valid");
		$this->OutputDebug("Connecting to host address \"" . $ip . "\" port " . $port . "...");
		if (($this->connection = ($this->timeout ? @fsockopen(($this->ssl ? "ssl://" : "") . $ip, $port, $errno, $error, $this->timeout) : @fsockopen(($this->ssl ? "ssl://" : "") . $ip, $port))))
			return ("");
		$error = ($this->timeout ? strval($error) : "??");
		switch($error) {
			case "-3" :
				return ("-3 socket could not be created");
			case "-4" :
				return ("-4 dns lookup on hostname \"" . $domain . "\" failed");
			case "-5" :
				return ("-5 connection refused or timed out");
			case "-6" :
				return ("-6 fdopen() call failed");
			case "-7" :
				return ("-7 setvbuf() call failed");
		}
		return ("could not connect to the host \"" . $domain . "\": " . $error);
	}

	Function SASLAuthenticate($mechanisms, $credentials, &$authenticated, &$mechanism) {
		$authenticated = 0;
		if (!function_exists("class_exists") || !class_exists("sasl_client_class")) {
			$this->setError("it is not possible to authenticate using the specified mechanism because the SASL library class is not loaded");
			return (0);
		}
		$sasl = new sasl_client_class;
		$sasl -> SetCredential("user", $credentials["user"]);
		$sasl -> SetCredential("password", $credentials["password"]);
		if (IsSet($credentials["realm"]))
			$sasl -> SetCredential("realm", $credentials["realm"]);
		if (IsSet($credentials["workstation"]))
			$sasl -> SetCredential("workstation", $credentials["workstation"]);
		if (IsSet($credentials["mode"]))
			$sasl -> SetCredential("mode", $credentials["mode"]);
		do {
			$status = $sasl -> Start($mechanisms, $message, $interactions);
		} while($status==SASL_INTERACT);
		switch($status) {
			case SASL_CONTINUE :
				break;
			case SASL_NOMECH :
				if (strlen($this->authentication_mechanism)) {
					$this->setError("authenticated mechanism " . $this->authentication_mechanism . " may not be used: " . $sasl -> error);
					return (0);
				}
				break;
			default :
				$this->setError("Could not start the SASL authentication client: " . $sasl -> error);
				return (0);
		}
		if (strlen($mechanism = $sasl -> mechanism)) {
			if ($this->PutLine("AUTH " . $sasl -> mechanism . (IsSet($message) ? " " . base64_encode($message) : "")) == 0) {
				$this->setError("Could not send the AUTH command");
				return (0);
			}
			if (!$this->VerifyResultLines(array("235", "334"), $responses))
				return (0);
			switch($this->result_code) {
				case "235" :
					$response = "";
					$authenticated = 1;
					break;
				case "334" :
					$response = base64_decode($responses[0]);
					break;
				default :
					$this->setError("Authentication error: " . $responses[0]);
					return (0);
			}
			for (; !$authenticated; ) {
				do {
					$status = $sasl -> Step($response, $message, $interactions);
				} while($status==SASL_INTERACT);
				switch($status) {
					case SASL_CONTINUE :
						if ($this->PutLine(base64_encode($message)) == 0) {
							$this->setError("Could not send the authentication step message");
							return (0);
						}
						if (!$this->VerifyResultLines(array("235", "334"), $responses))
							return (0);
						switch($this->result_code) {
							case "235" :
								$response = "";
								$authenticated = 1;
								break;
							case "334" :
								$response = base64_decode($responses[0]);
								break;
							default :
								$this->setError("Authentication error: " . $responses[0]);
								return (0);
						}
						break;
					default :
						$this->setError("Could not process the SASL authentication step: " . $sasl -> error);
						return (0);
				}
			}
		}
		return (1);
	}

	Function StartSMTP($localhost) {
		$success = 1;
		$this->esmtp_extensions = array();
		$fallback = 1;
		if ($this->esmtp || strlen($this->user)) {
			if ($this->PutLine('EHLO ' . $localhost)) {
				if (($success_code = $this->VerifyResultLines('250', $responses)) > 0) {
					$this->esmtp_host = $this->Tokenize($responses[0], " ");
					for ($response = 1; $response < count($responses); $response++) {
						$extension = strtoupper($this->Tokenize($responses[$response], " "));
						$this->esmtp_extensions[$extension] = $this->Tokenize("");
					}
					$success = 1;
					$fallback = 0;
				} else {
					if ($success_code == 0) {
						$code = $this->Tokenize($this->error, " -");
						switch($code) {
							case "421" :
								$fallback = 0;
								break;
						}
					}
				}
			} else
				$fallback = 0;
		}
		if ($fallback) {
			if ($this->PutLine("HELO $localhost") && $this->VerifyResultLines("250", $responses) > 0)
				$success = 1;
		}
		return ($success);
	}

	/* Public methods */
	Function Connect($domain = "") {
		if (strcmp($this->state, "Disconnected")) {
			$this->setError("connection is already established");
			return (0);
		}
		$this->disconnected_error = 0;
		$this->error = $error = "";
		$this->esmtp_host = "";
		$this->esmtp_extensions = array();
		$hosts = array();
		if ($this->direct_delivery) {
			if (strlen($domain) == 0)
				return (1);
			$hosts = $weights = $mxhosts = array();
			$getmxrr = $this->getmxrr;
			if (function_exists($getmxrr) && $getmxrr($domain, $hosts, $weights)) {
				for ($host = 0; $host < count($hosts); $host++)
					$mxhosts[$weights[$host]] = $hosts[$host];
				KSort($mxhosts);
				for (Reset($mxhosts), $host = 0; $host < count($mxhosts); Next($mxhosts), $host++)
					$hosts[$host] = $mxhosts[Key($mxhosts)];
			} else {
				if (strcmp(@gethostbyname($domain), $domain) != 0)
					$hosts[] = $domain;
			}
		} else {
			if (strlen($this->host_name))
				$hosts[] = $this->host_name;
			if (strlen($this->pop3_auth_host)) {
				$user = $this->user;
				if (strlen($user) == 0) {
					$this->setError("it was not specified the POP3 authentication user");
					return (0);
				}
				$password = $this->password;
				if (strlen($password) == 0) {
					$this->setError("it was not specified the POP3 authentication password");
					return (0);
				}
				$domain = $this->pop3_auth_host;
				$this->setError($this->ConnectToHost($domain, $this->pop3_auth_port, "Resolving POP3 authentication host \"" . $domain . "\"..."));
				if (strlen($this->error))
					return (0);
				if (strlen($response = $this->GetLine()) == 0)
					return (0);
				if (strcmp($this->Tokenize($response, " "), "+OK")) {
					$this->setError("POP3 authentication server greeting was not found");
					return (0);
				}
				if (!$this->PutLine("USER " . $this->user) || strlen($response = $this->GetLine()) == 0)
					return (0);
				if (strcmp($this->Tokenize($response, " "), "+OK")) {
					$this->setError("POP3 authentication user was not accepted: " . $this->Tokenize("\r\n"));
					return (0);
				}
				if (!$this->PutLine("PASS " . $password) || strlen($response = $this->GetLine()) == 0)
					return (0);
				if (strcmp($this->Tokenize($response, " "), "+OK")) {
					$this->setError("POP3 authentication password was not accepted: " . $this->Tokenize("\r\n"));
					return (0);
				}
				fclose($this->connection);
				$this->connection = 0;
			}
		}
		if (count($hosts) == 0) {
			$this->setError("could not determine the SMTP to connect");
			return (0);
		}
		for ($host = 0, $error = "not connected"; strlen($error) && $host < count($hosts); $host++) {
			$domain = $hosts[$host];
			$error = $this->ConnectToHost($domain, $this->host_port, "Resolving SMTP server domain \"$domain\"...");
		}
		if (strlen($error)) {
			$this->setError($error);
			return (0);
		}
		$timeout = ($this->data_timeout ? $this->data_timeout : $this->timeout);
		if ($timeout && function_exists("socket_set_timeout"))
			socket_set_timeout($this->connection, $timeout, 0);
		$this->OutputDebug("Connected to SMTP server \"" . $domain . "\".");
		if (!strcmp($localhost = $this->localhost, "") && !strcmp($localhost = getenv("SERVER_NAME"), "") && !strcmp($localhost = getenv("HOST"), ""))
			$localhost = "localhost";

		$success = 0;
		if ($this->VerifyResultLines("220", $responses) > 0) {
			$success = $this->StartSMTP($localhost);
			if ($this->start_tls) {
				if (!IsSet($this->esmtp_extensions["STARTTLS"])) {
					$this->setError("server does not support starting TLS");
					$success = 0;
				} elseif (!function_exists('stream_socket_enable_crypto')) {
					$this->setError("this PHP installation or version does not support starting TLS");
					$success = 0;
				} elseif ($success = ($this->PutLine('STARTTLS') && $this->VerifyResultLines('220', $responses) > 0)) {
					$this->OutputDebug('Starting TLS cryptograpic protocol');
					if (!($success = @stream_socket_enable_crypto($this->connection, 1, STREAM_CRYPTO_METHOD_TLS_CLIENT)))
						$this->error = 'could not start TLS connection encryption protocol';
					else {
						$this->OutputDebug('TLS started');
						$success = $this->StartSMTP($localhost);
					}
				}
			}
			if ($success && strlen($this->user) && strlen($this->pop3_auth_host) == 0) {
				if (!IsSet($this->esmtp_extensions["AUTH"])) {
					$this->setError("server does not require authentication");
					if (IsSet($this->esmtp_extensions["STARTTLS"]))
						$this->error .= ', it probably requires starting TLS';
					$success = 0;
				} else {
					if (strlen($this->authentication_mechanism))
						$mechanisms = array($this->authentication_mechanism);
					else {
						$mechanisms = array();
						for ($authentication = $this->Tokenize($this->esmtp_extensions["AUTH"], " "); strlen($authentication); $authentication = $this->Tokenize(" "))
							$mechanisms[] = $authentication;
					}
					$credentials = array("user" => $this->user, "password" => $this->password);
					if (strlen($this->realm))
						$credentials["realm"] = $this->realm;
					if (strlen($this->workstation))
						$credentials["workstation"] = $this->workstation;
					$success = $this->SASLAuthenticate($mechanisms, $credentials, $authenticated, $mechanism);
					if (!$success && !strcmp($mechanism, "PLAIN")) {
						/*
						 * Author:  Russell Robinson, 25 May 2003, http://www.tectite.com/
						 * Purpose: Try various AUTH PLAIN authentication methods.
						 */
						$mechanisms = array("PLAIN");
						$credentials = array("user" => $this->user, "password" => $this->password);
						if (strlen($this->realm)) {
							/*
							 * According to: http://www.sendmail.org/~ca/email/authrealms.html#authpwcheck_method
							 * some sendmails won't accept the realm, so try again without it
							 */
							$success = $this->SASLAuthenticate($mechanisms, $credentials, $authenticated, $mechanism);
						}
						if (!$success) {
							/*
							 * It was seen an EXIM configuration like this:
							 * user^password^unused
							 */
							$credentials["mode"] = SASL_PLAIN_EXIM_DOCUMENTATION_MODE;
							$success = $this->SASLAuthenticate($mechanisms, $credentials, $authenticated, $mechanism);
						}
						if (!$success) {
							/*
							 * ... though: http://exim.work.de/exim-html-3.20/doc/html/spec_36.html
							 * specifies: ^user^password
							 */
							$credentials["mode"] = SASL_PLAIN_EXIM_MODE;
							$success = $this->SASLAuthenticate($mechanisms, $credentials, $authenticated, $mechanism);
						}
					}
					if ($success && strlen($mechanism) == 0) {
						$this->setError("it is not supported any of the authentication mechanisms required by the server");
						$success = 0;
					}
				}
			}
		}
		if ($success) {
			$this->state = "Connected";
			$this->connected_domain = $domain;
		} else {
			fclose($this->connection);
			$this->connection = 0;
		}
		return ($success);
	}

	Function MailFrom($sender) {
		if ($this->direct_delivery) {
			switch($this->state) {
				case "Disconnected" :
					$this->direct_sender = $sender;
					return (1);
				case "Connected" :
					$sender = $this->direct_sender;
					break;
				default :
					$this->setError("direct delivery connection is already established and sender is already set");
					return (0);
			}
		} else {
			if (strcmp($this->state, "Connected")) {
				$this->setError("connection is not in the initial state");
				return (0);
			}
		}
		if (!$this->PutLine("MAIL FROM:<$sender>"))
			return (0);
		if (!IsSet($this->esmtp_extensions["PIPELINING"]) && $this->VerifyResultLines("250", $responses) <= 0)
			return (0);
		$this->state = "SenderSet";
		if (IsSet($this->esmtp_extensions["PIPELINING"]))
			$this->pending_sender = 1;
		$this->pending_recipients = 0;
		return (1);
	}

	Function SetRecipient($recipient) {
		if ($this->direct_delivery) {
			if (GetType($at = strrpos($recipient, "@")) != "integer")
				return ("it was not specified a valid direct recipient");
			$domain = substr($recipient, $at + 1);
			switch($this->state) {
				case "Disconnected" :
					if (!$this->Connect($domain))
						return (0);
					if (!$this->MailFrom("")) {
						$error = $this->error;
						$this->Disconnect();
						$this->setError($error);
						return (0);
					}
					break;
				case "SenderSet" :
				case "RecipientSet" :
					if (strcmp($this->connected_domain, $domain)) {
						$this->setError("it is not possible to deliver directly to recipients of different domains");
						return (0);
					}
					break;
				default :
					$this->setError("connection is already established and the recipient is already set");
					return (0);
			}
		} else {
			switch($this->state) {
				case "SenderSet" :
				case "RecipientSet" :
					break;
				default :
					$this->setError("connection is not in the recipient setting state");
					return (0);
			}
		}
		if (!$this->PutLine("RCPT TO:<$recipient>"))
			return (0);
		if (IsSet($this->esmtp_extensions["PIPELINING"])) {
			$this->pending_recipients++;
			if ($this->pending_recipients >= $this->maximum_piped_recipients) {
				if (!$this->FlushRecipients())
					return (0);
			}
		} else {
			if ($this->VerifyResultLines(array("250", "251"), $responses) <= 0)
				return (0);
		}
		$this->state = "RecipientSet";
		return (1);
	}

	Function StartData() {
		if (strcmp($this->state, "RecipientSet")) {
			$this->setError("connection is not in the start sending data state");
			return (0);
		}
		if (!$this->PutLine("DATA"))
			return (0);
		if ($this->pending_recipients) {
			if (!$this->FlushRecipients())
				return (0);
		}
		if ($this->VerifyResultLines("354", $responses) <= 0)
			return (0);
		$this->state = "SendingData";
		return (1);
	}

	Function PrepareData($data) {
		if (function_exists("preg_replace"))
			return (preg_replace(array("/\n\n|\r\r/", "/(^|[^\r])\n/", "/\r([^\n]|\$)/D", "/(^|\n)\\./"), array("\r\n\r\n", "\\1\r\n", "\r\n\\1", "\\1.."), $data));
		else
			return (
			ereg_replace("(^|\n)\\.", "\\1..", ereg_replace("\r([^\n]|\$)", "\r\n\\1", ereg_replace("(^|[^\r])\n", "\\1\r\n", ereg_replace("\n\n|\r\r", "\r\n\r\n", $data)))));
	}

	Function SendData($data) {
		if (strcmp($this->state, "SendingData")) {
			$this->setError("connection is not in the sending data state");
			return (0);
		}
		return ($this->PutData($data));
	}

	Function EndSendingData() {
		if (strcmp($this->state, "SendingData")) {
			$this->setError("connection is not in the sending data state");
			return (0);
		}
		if (!$this->PutLine("\r\n.") || $this->VerifyResultLines("250", $responses) <= 0)
			return (0);
		$this->state = "Connected";
		return (1);
	}

	Function ResetConnection() {
		switch($this->state) {
			case "Connected" :
				return (1);
			case "SendingData" :
				$this->setError("can not reset the connection while sending data");
				return (0);
			case "Disconnected" :
				$this->setError("can not reset the connection before it is established");
				return (0);
		}
		if (!$this->PutLine("RSET") || $this->VerifyResultLines("250", $responses) <= 0)
			return (0);
		$this->state = "Connected";
		return (1);
	}

	Function Disconnect($quit = 1) {
		if (!strcmp($this->state, "Disconnected")) {
			$this->setError("it was not previously established a SMTP connection");
			return (0);
		}
		$this->error = "";
		if (!strcmp($this->state, "Connected") && $quit && (!$this->PutLine("QUIT") || ($this->VerifyResultLines("221", $responses) <= 0 && !$this->disconnected_error)))
			return (0);
		if ($this->disconnected_error)
			$this->disconnected_error = 0;
		else
			fclose($this->connection);
		$this->connection = 0;
		$this->state = "Disconnected";
		$this->OutputDebug("Disconnected.");
		return (1);
	}

	Function SendMessage($sender, $recipients, $headers, $body) {
		if (($success = $this->Connect())) {
			if (($success = $this->MailFrom($sender))) {
				for ($recipient = 0; $recipient < count($recipients); $recipient++) {
					if (!($success = $this->SetRecipient($recipients[$recipient])))
						break;
				}
				if ($success && ($success = $this->StartData())) {
					for ($header_data = "", $header = 0; $header < count($headers); $header++)
						$header_data .= $headers[$header] . "\r\n";
					$success = ($this->SendData($header_data . "\r\n") && $this->SendData($this->PrepareData($body)) && $this->EndSendingData());
				}
			}
			$error = $this->error;
			$disconnect_success = $this->Disconnect($success);
			if ($success)
				$success = $disconnect_success;
			else
				$this->setError($error);
		}
		return ($success);
	}

};

define("SASL_INTERACT", 2);
define("SASL_CONTINUE", 1);
define("SASL_OK", 0);
define("SASL_FAIL", -1);
define("SASL_NOMECH", -4);
class sasl_interact_class {
	var $id;
	var $challenge;
	var $prompt;
	var $default_result;
	var $result;
};
class sasl_client_class {
	var $error = '';
	var $mechanism = '';
	var $encode_response = 1;

	/* Private variables */
	var $driver;
	var $drivers = array("Digest" => array("digest_sasl_client_class", "digest_sasl_client.php"), "CRAM-MD5" => array("cram_md5_sasl_client_class", "cram_md5_sasl_client.php"), "LOGIN" => array("login_sasl_client_class", "login_sasl_client.php"), "NTLM" => array("ntlm_sasl_client_class", "ntlm_sasl_client.php"), "PLAIN" => array("plain_sasl_client_class", "plain_sasl_client.php"), "Basic" => array("basic_sasl_client_class", "basic_sasl_client.php"));
	var $credentials = array();

	/* Public functions */
	Function SetCredential($key, $value) {
		$this->credentials[$key] = $value;
	}

	Function GetCredentials(&$credentials, $defaults, &$interactions) {
		Reset($credentials);
		$end = (GetType($key = Key($credentials)) != "string";
		for (; !$end; ) {
			if (!IsSet($this->credentials[$key])) {
				if (IsSet($defaults[$key]))
					$credentials[$key] = $defaults[$key];
				else {
					$this->error = "the requested credential " . $key . " is not defined";
					return (SASL_NOMECH);
				}
			} else
				$credentials[$key] = $this->credentials[$key];
			Next($credentials);
			$end = (GetType($key = Key($credentials)) != "string";
		}
		return (SASL_CONTINUE);
	}

	Function Start($mechanisms, &$message, &$interactions) {
		if (strlen($this->error))
			return (SASL_FAIL);
		if (IsSet($this->driver))
			return ($this->driver -> Start($this, $message, $interactions));
		$no_mechanism_error = "";
		for ($m = 0; $m < count($mechanisms); $m++) {
			$mechanism = $mechanisms[$m];
			if (IsSet($this->drivers[$mechanism])) {
				if (!class_exists($this->drivers[$mechanism][0]))
					require (dirname(__FILE__) . "/" . $this->drivers[$mechanism][1]);
				$this->driver = new $this->drivers[$mechanism][0];
				if ($this->driver -> Initialize($this)) {
					$this->encode_response = 1;
					$status = $this->driver -> Start($this, $message, $interactions);
					switch($status) {
						case SASL_NOMECH :
							Unset($this->driver);
							if (strlen($no_mechanism_error) == 0)
								$no_mechanism_error = $this->error;
							$this->error = "";
							break;
						case SASL_CONTINUE :
							$this->mechanism = $mechanism;
							return ($status);
						default :
							Unset($this->driver);
							$this->error = "";
							return ($status);
					}
				} else {
					Unset($this->driver);
					if (strlen($no_mechanism_error) == 0)
						$no_mechanism_error = $this->error;
					$this->error = "";
				}
			}
		}
		$this->error = (strlen($no_mechanism_error) ? $no_mechanism_error : "it was not requested any of the authentication mechanisms that are supported");
		return (SASL_NOMECH);
	}

	Function Step($response, &$message, &$interactions) {
		if (strlen($this->error))
			return (SASL_FAIL);
		return ($this->driver -> Step($this, $response, $message, $interactions));
	}

};

define("SASL_LOGIN_STATE_START", 0);
define("SASL_LOGIN_STATE_IDENTIFY_USER", 1);
define("SASL_LOGIN_STATE_IDENTIFY_PASSWORD", 2);
define("SASL_LOGIN_STATE_DONE", 3);
class login_sasl_client_class {
	var $credentials = array();
	var $state = SASL_LOGIN_STATE_START;

	Function Initialize(&$client) {
		return (1);
	}

	Function Start(&$client, &$message, &$interactions) {
		if ($this->state != SASL_LOGIN_STATE_START) {
			$client -> error = "LOGIN authentication state is not at the start";
			return (SASL_FAIL);
		}
		$this->credentials = array("user" => "", "password" => "", "realm" => "");
		$defaults = array("realm" => "");
		$status = $client -> GetCredentials($this->credentials, $defaults, $interactions);
		if ($status == SASL_CONTINUE)
			$this->state = SASL_LOGIN_STATE_IDENTIFY_USER;
		Unset($message);
		return ($status);
	}

	Function Step(&$client, $response, &$message, &$interactions) {
		switch($this->state) {
			case SASL_LOGIN_STATE_IDENTIFY_USER :
				$message = $this->credentials["user"] . (strlen($this->credentials["realm"]) ? "@" . $this->credentials["realm"] : "");
				$this->state = SASL_LOGIN_STATE_IDENTIFY_PASSWORD;
				break;
			case SASL_LOGIN_STATE_IDENTIFY_PASSWORD :
				$message = $this->credentials["password"];
				$this->state = SASL_LOGIN_STATE_DONE;
				break;
			case SASL_LOGIN_STATE_DONE :
				$client -> error = "LOGIN authentication was finished without success";
				break;
			default :
				$client -> error = "invalid LOGIN authentication step state";
				return (SASL_FAIL);
		}
		return (SASL_CONTINUE);
	}

};

define("SASL_PLAIN_STATE_START", 0);
define("SASL_PLAIN_STATE_IDENTIFY", 1);
define("SASL_PLAIN_STATE_DONE", 2);
define("SASL_PLAIN_DEFAULT_MODE", 0);
define("SASL_PLAIN_EXIM_MODE", 1);
define("SASL_PLAIN_EXIM_DOCUMENTATION_MODE", 2);
class plain_sasl_client_class {
	var $credentials = array();
	var $state = SASL_PLAIN_STATE_START;

	Function Initialize(&$client) {
		return (1);
	}

	Function Start(&$client, &$message, &$interactions) {
		if ($this->state != SASL_PLAIN_STATE_START) {
			$client -> error = "PLAIN authentication state is not at the start";
			return (SASL_FAIL);
		}
		$this->credentials = array("user" => "", "password" => "", "realm" => "", "mode" => "");
		$defaults = array("realm" => "", "mode" => "");
		$status = $client -> GetCredentials($this->credentials, $defaults, $interactions);
		if ($status == SASL_CONTINUE) {
			switch($this->credentials["mode"]) {
				case SASL_PLAIN_EXIM_MODE :
					$message = $this->credentials["user"] . "\0" . $this->credentials["password"] . "\0";
					break;
				case SASL_PLAIN_EXIM_DOCUMENTATION_MODE :
					$message = "\0" . $this->credentials["user"] . "\0" . $this->credentials["password"];
					break;
				default :
					$message = $this->credentials["user"] . "\0" . $this->credentials["user"] . (strlen($this->credentials["realm"]) ? "@" . $this->credentials["realm"] : "") . "\0" . $this->credentials["password"];
					break;
			}
			$this->state = SASL_PLAIN_STATE_DONE;
		} else
			Unset($message);
		return ($status);
	}

	Function Step(&$client, $response, &$message, &$interactions) {
		switch($this->state) {
			/*
			 case SASL_PLAIN_STATE_IDENTIFY:
			 switch($this->credentials["mode"])
			 {
			 case SASL_PLAIN_EXIM_MODE:
			 $message=$this->credentials["user"]."\0".$this->credentials["password"]."\0";
			 break;
			 case SASL_PLAIN_EXIM_DOCUMENTATION_MODE:
			 $message="\0".$this->credentials["user"]."\0".$this->credentials["password"];
			 break;
			 default:
			 $message=$this->credentials["user"]."\0".$this->credentials["user"].(strlen($this->credentials["realm"]) ? "@".$this->credentials["realm"] : "")."\0".$this->credentials["password"];
			 break;
			 }
			 var_dump($message);
			 $this->state=SASL_PLAIN_STATE_DONE;
			 break;
			 */
			case SASL_PLAIN_STATE_DONE :
				$client -> error = "PLAIN authentication was finished without success";
				return (SASL_FAIL);
			default :
				$client -> error = "invalid PLAIN authentication step state";
				return (SASL_FAIL);
		}
		return (SASL_CONTINUE);
	}

};
