<?php

/**
 * This file is part of CoverallsKit.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace coverallskit;

use Zend\Console\Console as ZendConole;
use Zend\Console\ColorInterface as Color;


/**
 * Class ConsoleWrapper
 * @package coverallskit
 */
class ConsoleWrapper implements ConsoleWrapperInterface
{

    /**
     * @var \Zend\Console\Adapter\AdapterInterface
     */
    private $console;


    public function __construct()
    {
        $this->console = ZendConole::getInstance();
    }

    public function writeMessage($text = "")
    {
        $this->console->writeLine($text);
    }

    public function writeFailureMessage($text = "")
    {
        $this->console->writeLine($text, Color::RED);
    }

    public function writeSuccessMessage($text = "")
    {
        $this->console->writeLine($text, Color::GREEN);
    }

}
