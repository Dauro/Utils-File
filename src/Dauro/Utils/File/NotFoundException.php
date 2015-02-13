<?php

namespace Dauro\Utils\File;

/**
 * NotFoundException
 *
 * @package Utils
 * @subpackage File
 * @version 0.1 (beta)
 * @author Gotardo González <contact@gotardo.es>
 * @license http://opensource.org/licenses/MIT MIT
 */

class NotFoundException extends Exception
{
    /**
     *
     * @param String $path The path of the not found file.
     * @param type $previous
     */
    public function __construct($path, $previous = null) {
        parent::__construct($path, 'File not found', 999001001, $previous);
    }
}