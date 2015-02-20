<?php
namespace Dauro\Utils\File;

/**
 * @coversDefaultClass Dauro\Utils\File\Handler
 */
class HandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException NotFoundException
     */
    public function testOpenFail() {
        $file = new Handler('NotExistingFile.txt');
        $file->read();
    }

    /**
     * @covers ::duplicate
     */
    public function testDuplicate() {
        $faker = \Faker\Factory::create();
        $content = $faker->sentence();

        $originalFile = new Handler('originalFile.txt');
        $originalFile->write($content);
        $newFile = $originalFile->duplicate();
        $this->assertEquals($content, $newFile->read());
        $originalFile->delete();
        $newFile->delete();
    }

    /**
     * @covers ::getMime
     */
    public function testMime(){
        $faker = \Faker\Factory::create();
        $content = $faker->sentence();
        $originalFile = new Handler('originalFile.txt');
        $originalFile->write($content);
        $this->assertType('string', $originalFile->getMimeType());
        $originalFile->delete();
    }


    /**
     * @covers ::delete
     * @expectedException Exception
     */
    public function testDelete(){
        $faker = \Faker\Factory::create();
        $content = $faker->sentence();

        $originalFile = new Handler('originalFile.txt');
        $originalFile->write($content);
        $originalFile->delete();

        $this->assertFalse(file_exists('originalFile.txt'));

    }

}