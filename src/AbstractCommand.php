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
     * @var Context
     */
    protected $context;

    /**
     * @var Getopt
     */
    protected $options;

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
        $this->options = $context->getCommandOptions($this->getRules());
    }

    /**
     * @return array
     */
    protected function getRules()
    {
        return $this->rules;
    }

    /**
     * @return string
     */
    public function getUsageMessage()
    {
        return $this->options->getUsageMessage();
    }

}
