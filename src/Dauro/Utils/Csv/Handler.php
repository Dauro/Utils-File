<?php

namespace Dauro\Utils\Csv;


/**
 * CSV File manager
 *
 * @package Utils
 * @subpackage File
 * @version 0.5 (beta)
 * @author Gotardo González <contact@gotardo.es>
 * @license http://opensource.org/licenses/MIT MIT
 */

class Handler extends Dauro\Utils\File {

    /**
     * @var int The headers of the document.
     */
    protected $_headers;

    /**
     * @var int The number of readed CSV rows.
     */
    protected $_counter = 0;

    /**
     * @var string the default target enconding when autoencoding.
     */
    protected $_toEncoding = 'UTF-8';

    /**
     * @var bool Enable/disable autoencoding on reading CSV values.
     */
    protected $_autoencodeEnabled = true;

    /**
     * @var bool Enable/disable header reading.
     */
    protected $_headersEnabled = true;

    /**
     * Read the headers on the CSV.
     * Beware to retrieve the headers on he begining of the file.
     *
     * @return type
     * @throws Exception if you try to read he headers when at any moment but the begining of the file.
     */
    protected function readHeaders(){
        if (!$this->_resource)
            $this->open();

        if (!$this->_counter) {
            return fgetcsv($this->_resource);
        }
        else
            throw new Exception($this->_path, 'Can\'t read headers. Reading already started.');
    }

    /**
     * Get the headers for the current CSV.
     * @return array
     */
    public function getHeaders(){
        if (empty($this->_headers))
            $this->_headers = $this->readHeaders();
        return $this->_headers;
    }

    public function getHeader($headerNUmber){
        return $this->_headers[$headerNUmber];
    }

    public function setHeaderValidation($array){

    }

    public function getRow(){
        $this->_resource || $this->open();

        if ($line = fgetcsv($this->_resource)){
            if ($this->_headersEnabled)
                $headers = $this->getHeaders();
            else
                $headers = null;

            $this->_counter += 1;
            $lineReturn = [];
            if ($headers)
                foreach ($headers as $key => $value)
                    $lineReturn[$headers[$key]] = $line[$key];
            else
                $lineReturn = $line;

            $this->_autoencodeEnabled && array_walk($lineReturn, [$this, 'autoEncode']);

            return $lineReturn;
        }
        else{
            return false;
        }
    }

    /**
     * Count the lines
     * @return type
     */
    public function countLines(){
        return $this->_counter;
    }

    /**
     * Encode to UTF-8 and clean the variable.
     * @param string $string The string to be modified
     * @return string
     */
    public function autoEncode(&$string){
        $string =  trim(\Gapp\Utils\String::encode($string, $this->_toEncoding, $this->getMimeEncoding()));
        return $string;
    }

    /**
     * Enable the header reading. If you expect to have headers defining the
     * column names on the top of the CSV file, enable this option to recevie an
     * associative array on each getRow() read.
     *
     * @return \Gapp\Utils\File\Csv
     */
    public function withHeaders() {
        return $this->enableHeaders();
    }

    /**
     * Enable the header reading. If you expect to have headers defining the
     * column names on the top of the CSV file, enable this option to recevie an
     * associative array on each getRow() read.
     *
     * @return \Gapp\Utils\File\Csv
     */
    public function enableHeaders(){
        $this->_headersEnabled = true;
        return $this;
    }

    /**
     * Disable the header reading. If no headers are defined, each row will be returned as an array.
     *
     * @return \Gapp\Utils\File\Csv
     */
    public function disableHeaders(){
        $this->_headersEnabled = false;
        return $this;
    }

    /**
     * Disable autoencode for CSV values
     * @return \Gapp\Utils\File\Csv this
     */
    public function disableAutoEncode() {
        $this->_autoencodeEnabled = false;
        return $this;
    }

    /**
     * Enable autoencode for CSV values
     * @return \Gapp\Utils\File\Csv this
     */
    public function enableAutoEncode(){
        $this->_autoencodeEnabled = true;
        return $this;
    }
}