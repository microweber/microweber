<?php

/*
 * This file is part of Composer.
 *
 * (c) Nils Adermann <naderman@naderman.de>
 *     Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace MicroweberPackages\Package\Helpers;

use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Composer\IO\BufferIO;


class InstallerIO extends BufferIO
{

    /**
     * @param string $input
     * @param int $verbosity
     * @param OutputFormatterInterface|null $formatter
     */
    public function __construct($input = '', $verbosity = StreamOutput::VERBOSITY_NORMAL, OutputFormatterInterface $formatter = null)
    {
        parent::__construct($input, $verbosity, $formatter);
    }

    public function writeError($messages, $newline = true, $verbosity = self::NORMAL)
    {

        if(is_array($messages)){
            $messages = implode(',',$messages);
        }
    //    echo $messages . '<br />';

       app()->update->log_msg($messages);
     }
}
