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
 * Interface ContextInterface
 * @package coverallskit
 */
interface ContextInterface
{

    /**
     * @return string
     */
    public function getScriptName();

    /**
     * @return string
     */
    public function getCommandName();

    /**
     * @return \Zend\Stdlib\Parameters
     */
    public function getCommandArguments();

    /**
     * @param array $rules
     * @return \Zend\Console\Getopt
     */
    public function getCommandOptions(array $rules);

}
