<?php
namespace Dauro\Utils\File;

/**
 * @coversDefaultClass Dauro\Utils\File\Handler
 */
class HandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Dauro\Utils\File\NotFoundException
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

        $originalFile = Handler::create('./originalFile.txt');
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
        $originalFile = Handler::create('originalFile.txt');
        $originalFile->write($content);
        $this->assertNotNull('string', $originalFile->getMimeType());
        $originalFile->delete();
    }


    /**
     * @covers ::delete
     */
    public function testDelete(){
        $faker = \Faker\Factory::create();
        $content = $faker->sentence();

        $originalFile = Handler::create('originalFile.txt');
        $originalFile->write($content);
        $this->assertTrue(file_exists('originalFile.txt'), 'Test file could not be created');
        $originalFile->delete();

        $this->assertFalse(file_exists('originalFile.txt'), 'Test file could not be deleted');

    }

}