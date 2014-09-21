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

use UnexpectedValueException;

/**
 * Class DirectoryNotFoundException
 * @package coverallskit
 */
class DirectoryNotFoundException extends UnexpectedValueException
{

    /**
     * @param Stdio $stdio
     */
    public function printMessage(Stdio $stdio)
    {
        $stdio->errln($this->getMessage());
    }

}
