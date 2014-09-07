<?php

namespace coverallskit\spec\fixture;

use coverallskit\CommandInterface;
use coverallskit\ConsoleWrapperInterface;
use coverallskit\Context;


class FixtureBuildCommand implements CommandInterface
{

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
    }

    /**
     * @return string
     */
    public function getUsageMessage()
    {
    }

    /**
     * @throws FailureException
     */
    public function execute(ConsoleWrapperInterface $console)
    {
    }

}
