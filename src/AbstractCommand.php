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

/**
 * Class AbstractCommand
 * @package coverallskit
 */
abstract class AbstractCommand implements CommandInterface
{
    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @var string
     */
    protected $summaryMessage;

    /**
     * @var ContextInterface
     */
    protected $context;

    /**
     * @var \Ulrichsg\Getopt\Getopt
     */
    protected $options;


    /**
     * @param ContextInterface $context
     */
    public function __construct(ContextInterface $context)
    {
        $this->context = $context;
        $this->options = $context->getCommandOptions($this->getOptions());
    }

    /**
     * @return string
     */
    public function getSummaryMessage()
    {
        return $this->summaryMessage;
    }

    /**
     * @return string
     */
    public function getUsageMessage()
    {
        return $this->options->getHelpText();
    }

    /**
     * @param ConsoleWrapperInterface $console
     */
    public function execute(ConsoleWrapperInterface $console)
    {
        $this->prepare();
        $this->perform($console);
    }

    /**
     * @return array
     */
    protected function getRules()
    {
        return $this->rules;
    }

    /**
     * @return \Ulrichsg\Getopt\Getopt;
     */
    abstract protected function getOptions();

    /**
     * @throws HelpException
     */
    protected function prepare()
    {
        if ($this->options->getOption('h')) {
            throw new HelpException($this->getUsageMessage());
        }
    }

    /**
     * @param ConsoleWrapperInterface $console
     */
    abstract protected function perform(ConsoleWrapperInterface $console);

}
