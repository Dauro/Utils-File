<?php

namespace Dauro\Utils\File;

/**
 * File manager
 *
 * @package Utils
 * @subpackage File
 * @version 0.1 (beta)
 * @author Gotardo González <contact@gotardo.es>
 * @license http://opensource.org/licenses/MIT MIT
 */

class Exception extends \Exception {

    /** @var string The file path */
    protected $_path;

    /**
     *
     * @param string $path The path of the file that caused the exception
     * @param string $message
     * @param \Exception $previous
     */
    public function __construct($path, $message = null, $code = null, $previous = null) {
        $this->_path = $path;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Get the file path that generated the execption.
     * @return string The path
     */
    public function getPath() {
        return $this->_path;
    }
}