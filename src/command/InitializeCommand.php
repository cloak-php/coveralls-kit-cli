<?php

/**
 * This file is part of CoverallsKit.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace coverallskit\command;

use coverallskit\AbstractCommand;
use coverallskit\ConsoleWrapperInterface;
use coverallskit\HelpException;
use coverallskit\RequireException;
use coverallskit\FailureException;


/**
 * Class InitializeCommand
 * @package coverallskit\command
 */
class InitializeCommand extends AbstractCommand
{

    /**
     * @var array
     */
    protected $rules = [
        'help|h-s' => 'Prints this usage information.',
    ];

    public function execute(ConsoleWrapperInterface $console)
    {

        if ($this->options->help) {
            throw new HelpException($this->getUsageMessage());
        };

    }

}
