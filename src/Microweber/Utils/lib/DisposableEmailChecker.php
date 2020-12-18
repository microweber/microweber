<?php

namespace Microweber\Utils\lib;


class DisposableEmailChecker
{
    public function check($mail)
    {

        // list is from here https://gist.github.com/hassanazimi/d6e49469258d7d06f9f4
        $file = __DIR__ . DIRECTORY_SEPARATOR . 'disposable_email_addresses.txt';
        $file = normalize_path($file, false);


        $mail_domains_ko = file_get_contents($file);
        $mail_domains_ko = explode("\n", $mail_domains_ko);
        if (empty($mail_domains_ko)) {
            return false;
        }

        foreach ($mail_domains_ko as $ko_mail) {
            list(, $mail_domain) = explode('@', $mail);
            if (strcasecmp($mail_domain, trim($ko_mail)) == 0) {
                return TRUE;
            }
        }
        return FALSE;
    }
}