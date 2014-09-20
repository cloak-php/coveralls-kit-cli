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

use Zend\Stdlib\Parameters;
use Ulrichsg\Getopt\Getopt;

/**
 * Class Context
 * @package coverallskit
 */
class Context implements ContextInterface
{

    const SCRIPT_NAME_KEY = 0;
    const COMMAND_NAME_KEY = 1;

    /**
     * @var string
     */
    private $scriptName;

    /**
     * @var string
     */
    private $commandName;

    /**
     * @var \Zend\Stdlib\Parameters
     */
    private $commandArguments;

    /**
     * @var \Zend\Stdlib\Parameters
     */
    private $environment;


    /**
     * @param array $argv
     * @param array $env
     */
    public function __construct(array $argv, $env = [])
    {
        $arguments = $argv;
        $this->scriptName = array_shift($arguments);
        $this->commandName = array_shift($arguments);
        $this->commandArguments = new Parameters($arguments);
        $this->environment = new Parameters($env);
    }

    /**
     * @return string
     */
    public function getScriptName()
    {
        return $this->scriptName;
    }

    /**
     * @return string
     */
    public function getCommandName()
    {
        return $this->commandName;
    }

    /**
     * @return Parameters
     */
    public function getCommandArguments()
    {
        return $this->commandArguments;
    }

    /**
     * @param Getopt $opts
     * @return \Ulrichsg\Getopt\Getopt
     */
    public function getCommandOptions(Getopt $opts)
    {
        $arguments = $this->getCommandArguments();
        $argumentValue = implode(' ', $arguments->toArray());

        $opts->parse($argumentValue);

        return $opts;
    }

}
