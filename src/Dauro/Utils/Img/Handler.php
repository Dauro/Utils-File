<?php

namespace Dauro\Utils\Img;


/**
 * CSV File manager
 *
 * @package Utils
 * @subpackage Img
 * @version 0.5 (beta)
 * @author Gotardo González <contact@gotardo.es>
 * @license http://opensource.org/licenses/MIT MIT
 */

class Handler extends \Dauro\Utils\File\Handler {


    protected function detectGd() {
        return extension_loaded('gd') && function_exists('gd_info');
    }

    protected function detectImagik() {
        return extension_loaded('imagick');
    }

    public function crop($x1, $y1, $x2, $y2) {

    }

    public function resize($scale) {
        
    }

    public function getFormat(){
        return $this->getMimeType();
    }
}
