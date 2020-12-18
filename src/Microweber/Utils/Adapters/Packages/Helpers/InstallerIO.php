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


namespace Microweber\Utils\Adapters\Packages\Helpers;

use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Helper\HelperSet;
use Composer\IO\ConsoleIO;
use Composer\IO\BufferIO;
use Composer\IO\BaseIO;


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
        mw()->update->log_msg($messages);

     }



}
