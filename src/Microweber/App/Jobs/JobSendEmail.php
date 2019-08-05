<?php
namespace Microweber\App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class JobSendEmail implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	
	public $emailTo = false;
	public $emailSubject = false;
	public $emailMessage = false;
	public $emailFrom = false;
	public $emailFromName = false;
	
	public function setEmailTo($email) {
		$this->emailTo = $email;
	}
	
	public function setEmailSubject($subject) {
		$this->emailSubject = $subject;
	}
	
	public function setEmailMessage($message) {
		$this->emailMessage = $message;
	}

	public function setEmailFrom($email) {
		$this->emailFrom = $email;
	}
	
	public function setEmailFromName($name) {
		$this->emailFromName = $name;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$sender = new \Microweber\Utils\MailSender();
		
		$sender->setEmailTo($this->emailTo);
		$sender->setEmailSubject($this->emailSubject);
		$sender->setEmailMessage($this->emailMessage);
		$sender->setEmailFrom($this->emailFrom);
		$sender->setEmailFromName($this->emailFromName);
		
		$send = $sender->send();
		
		// dd($send);
		
		return $send;
	}
}
