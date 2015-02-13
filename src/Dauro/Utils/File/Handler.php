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

class Handler {

    /**
     * @var string The path of the file
     */
    protected $_path;

    /**
     * @var resource The handler of the file
     */
    protected $_resource = null;

    /**
     * @var resource The opening mode.
     */
    protected $_mode;

    /**
     *  @var string The Mime Type of the file
     */
    protected $_mimeType;

    /**
     *  @var string The Mime Encoding of the file
     */
    protected $_mimeEncoding;

    /**
     *  @var string The Mime string
     */
    protected $_mime;

    /**
     *  @cons bool Use include path when trying to open a file
     */
    const USE_INCLUDE_PATH = true;

    /**
     *
     * @param string $path The path of the file
     * @param string $mode The open mode (as defined in fopen)
     * @throws FileNotFoundException in case that $path is not a valid file.
     */
    public function __construct($path, $mode = 'rb'){
        if (
            ($mode === 'rb' || $mode === 'r')
            && !file_exists($path))
            throw new NotFoundException($path);

        $this->_path = $path;
        $this->_mode = $mode;
    }

    /**
     * Destruct the File Object.
     * - Close the file handler.
     */
    public function __destruct() {
        $this->_resource && $this->close();
    }

    /**
     * Opens the file. Generates a handler
     *
     * @return boolean true when the file was opened and a resource was set.
     * @throws FileNotFoundException
     */
    protected function open(){
        if ($this->_resource = fopen($this->_path, $this->_mode, self::USE_INCLUDE_PATH))
            return true;
        else
            throw new Exception($this->_path, 'Could not open the file');
    }

    /**
     * Close the file handler
     * @return true if the file was closed
     * @throws Exception When there is an error on closing the file.
     */
    protected function close(){
        if (fclose($this->_resource))
            return true;
        else
            throw new Exception($this->_path, 'Could not close the file');
    }

    /**
     *
     * @return int Get the size of the file in bytes
     */
    public function getSize(){
        return filesize($this->_path);
    }

    /**
     * Get the mime data of the file from a File Info resource.
     * @see http://php.net/manual/en/fileinfo.constants.php
     * @param int $option a Predefined file info constant.
     * @return string The requested mime data
     * @throws Gapp\Utils\File\Exception if the file info resource could not be stablished.
     */
    protected function getFileInfo($option){
        if ($finfo = finfo_open($option)){
            $mimeData = finfo_file($finfo, $this->_path);
            finfo_close($finfo);
            return $mimeData;
        }
        else{
            throw new Exception($this->_path, 'File Info could not open the resource.');
        }

    }

    /**
     * Get the mime data of the file from a File Info resource.
     * @see http://php.net/manual/en/fileinfo.constants.php
     * @param int $option a Predefined file info constant.
     * @return string The requested mime data
     * @throws Gapp\Utils\File\Exception if the file info resource could not be stablished.
     */
    public function getMime(){
        $this->_mime || $this->_mime = $this->getFileInfo(FILEINFO_MIME);
        return $this->_mime;
    }

    /**
     * Get the mime type of the file
     * @return string The mime type of the file
     * @throws Gapp\Utils\File\Exception if the file info resource could not be stablished.
     */
    public function getMimeType(){
        $this->_mimeType || $this->_mimeType = explode(';', $this->getMime())[0];
        return $this->_mimeType;
    }

    /**
     * Get the mime encoding of the file
     * @return string The mime encoding of the file
     * @throws Gapp\Utils\File\Exception if the file info resource could not be stablished.
     */
    public function getMimeEncoding(){
        $this->_mimeEncoding || $this->_mimeEncoding = explode('=', explode(';', $this->getMime())[1])[1];
        return $this->_mimeEncoding;
    }

    /**
     * Read a part of the file with $length size.
     * If length is null, the full file will be readed.
     * @param int $length the lenght of portion of the file to be readed.
     * @return string The readed part of the file. An EOF value will be returned when the end of file is reached.
     */
    public function read($length = null) {
        if ($length) {
            $this->_resource || $this->open ();
            return fread($this->_resource, $length);
        }
        else {
            return file_get_contents($this->_path, self::USE_INCLUDE_PATH);
        }
    }

    /**
     * Reads a line of the file
     * @return string The line
     */
    public function readLine() {
        $this->_resource || $this->open ();
        return fgets($this->_resource);
    }

    /**
     * Write $content to the file
     *
     * @param string $content The content of the file
     * @return bool true in case of success.
     */
    public function write($content){
        $this->_resource || $this->open ();
        return fwrite($this->_resource, $content);
    }

    /**
     * Write $content to the file
     *
     * @param string $content The content of the file
     * @return bool true in case of success.
     */
    public function writeLine($content){
        $this->_resource || $this->open ();
        return fwrite($this->_resource, $content . PHP_EOL);
    }

    /**
     * Rewind the pointer
     * @return bool true on success, false on error.
     */
    public function rewind(){
        return rewind($this->_resource);
    }
}
