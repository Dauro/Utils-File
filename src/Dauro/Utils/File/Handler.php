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
     *  @var string The extension of the file
     */
    protected $_pathinfo;

    /**
     *  @cons bool Use include path when trying to open a file
     */
    const USE_INCLUDE_PATH = true;

    /**
     *
     * @param string $path The path of the file
     * @param string $mode The open mode (as defined in fopen)
     * @throws FileNotFoundException in case that $path is not a valid file and you are opening for reading.
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
     * Creates a new file
     *
     * @param type $path
     * @param type $mode
     * @return \static
     */
    public static function create($path, $mode = 'w'){
        if(file_exists($path))
            throw new Exception($path, 'Can not create file. File already exists');

        return new static($path, $mode);
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
     * Deletes the file
     * @return true in case of success, false if the file did not exist.
     * @throws Exception When there is an error on closing the file.
     */
    public function delete() {
        if (!file_exists($this->_path))
            return false;
        else
            unlink($this->_path);
    }

    /**
     * Duplicate a file
     * @return File The new file
     * @throws Exception
     */
    public function duplicate($target = NULL){
        $origin = $this->getPath();
        $target || $target = $this->getDirName() . "/" . $this->getFileName() . "-2." . $this->getExtension();
        
        if (copy($origin, $target))
            return new static($target);
        else
            return false;
    }

    /**
     * Get the name of the directory where the file is located
     * @return string the dir name
     */
    public function getDirName(){
        return dirname($this->_path);
    }

    /**
     * Rename a file
     * @param string $newName The name of the file
     * @return bool true on success, false on failure
     */
    public function rename($newName){
        return rename($this->_path, dirname($this->_path) . '/' . $newname);
    }

    /**
     * Get the path of the file
     * @return String the Path
     */
    public function getPath(){
        return $this->_path;
    }

    /**
     * Get the extension of the file
     * @return string The file extension
     * @throws Gapp\Utils\File\Exception if the file info resource could not be stablished.
     */
    public function getExtension(){
        $this->_pathinfo || $this->_pathinfo = pathinfo($this->_path);
        return $this->_pathinfo['extension'];
    }

    /**
     * Get basename
     * @return string The name of the file (without extension)
     * @throws Gapp\Utils\File\Exception if the file info resource could not be stablished.
     */
    public function getBaseName(){
        return basename($this->getPath(), $this->getExtension());
    }

    /**
     * Get file name
     * @return string The name of the file.
     * @throws Gapp\Utils\File\Exception if the file info resource could not be stablished.
     */
    public function getFileName(){
        return basename($this->getPath());
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
        $this->_resource || $this->open();
        return fwrite($this->_resource, $content);
    }

    /**
     * Write $content to the file
     *
     * @param string $content The content of the file
     * @return bool true in case of success.
     */
    public function writeLine($content){
        $this->_resource || $this->open();
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
