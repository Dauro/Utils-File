<?php

namespace Dauro\Utils\Csv;

/**
 * File manager
 *
 * @package Utils
 * @subpackage Csv
 * @version 0.1 (beta)
 * @author Gotardo González <contact@gotardo.es>
 * @license http://opensource.org/licenses/MIT MIT
 */

class Exception extends Dauro\Utils\File\Exception
{
    /**
     *
     * @param string $path The path of the file that caused the exception
     * @param string $message
     * @param \Exception $previous
     */
    public function __construct($path, $message, \Exception $previous = null) {
        parent::__construct($path, $message, 999001011, $previous);
    }
}