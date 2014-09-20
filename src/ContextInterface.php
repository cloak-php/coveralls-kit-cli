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

use Ulrichsg\Getopt\Getopt;

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
     * @param Getopt $opts
     * @return \Ulrichsg\Getopt\Getopt
     */
    public function getCommandOptions(Getopt $opts);

}
