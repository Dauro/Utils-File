<?php
namespace Dauro\Utils\File;

/**
 * @todo
 */
class HandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Exception
     */
    public function testOpenFail(){
        $file = new Handler('NotExistingFile.txt');
    }

}