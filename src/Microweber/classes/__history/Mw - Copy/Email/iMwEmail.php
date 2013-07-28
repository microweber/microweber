<?php
namespace Mw\email;

interface  iMwEmail {

	public function send($to, $subject, $message);

}
