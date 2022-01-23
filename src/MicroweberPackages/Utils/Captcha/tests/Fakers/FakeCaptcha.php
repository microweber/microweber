<?php

namespace MicroweberPackages\Utils\Captcha\tests\Fakers;

class FakeCaptcha
{

    private $answer;

    public function validate($key, $captcha_id = null, $unset_if_found = true)
    {
        if ($key == $this->answer) {
            return true;
        }

        return false;
    }

    public function render($params = array())
    {
        return;
    }

    public function setAnswer($answer)
    {
        $this->answer = $answer;
    }

    public function reset($captcha_id = null)
    {
        $this->answer = null;
    }

}
