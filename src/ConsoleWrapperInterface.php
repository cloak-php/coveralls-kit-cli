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
 * Class ConsoleWrapperInterface
 * @package coverallskit
 */
interface ConsoleWrapperInterface
{

    public function writeMessage($text = "");

    public function writeFailureMessage($text = "");

    public function writeSuccessMessage($text = "");

}
