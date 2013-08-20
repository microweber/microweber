<?php
namespace Microweber\email;

interface  iMwEmail {

	public function send($to, $subject, $message);

}
