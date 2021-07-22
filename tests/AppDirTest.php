<?php
/**
 * Тест директории приложения.
 * @package evas-php/evas-base
 * @author Egor Vasyakin <egor@evas-php.com>
 */
use Evas\Base\tests;

use Codeception\Util\Autoload;
use Evas\Base\App;
use Evas\Base\Help\PhpHelp;
use Evas\Di\Container;

Autoload::addNamespace('Evas\\Base', 'vendor/evas-php/evas-base/src');
Autoload::addNamespace('Evas\\Di', 'vendor/evas-php/evas-di/src');

class AppDirTest extends \Codeception\Test\Unit
{
    public function testSetDir()
    {
        // $dir = dirname(dirname(dirname(dirname(__DIR__)))) .DIRECTORY_SEPARATOR;
        $dir = realpath(__DIR__ . '/../../../../') . DIRECTORY_SEPARATOR;
        $this->assertEquals($dir, App::dir());
        $dirTmp = __DIR__ . DIRECTORY_SEPARATOR;
        App::setDir($dirTmp);
        $this->assertEquals($dirTmp, App::dir());
        App::setDir($dir);
        $this->assertEquals($dir, App::dir());
    }

    public function testDirPath()
    {
        $path = PhpHelp::path('vendor/evas-php/evas-base/tests/');
        $dir = __DIR__ . DIRECTORY_SEPARATOR;
        $this->assertEquals($path, App::relativePathByApp($dir));
        $this->assertEquals($dir, App::absolutePathByApp($path));
        $this->assertTrue(App::isAbsoluteByApp($dir));
        $this->assertFalse(App::isAbsoluteByApp($path));
        $this->assertEquals($dir, App::resolveByApp($dir));
        $this->assertEquals($path, App::resolveByApp($path));
        $path2 = PhpHelp::path('evas-php/evas-base/tests/');
        $this->assertEquals(App::dir() . $path2, App::resolveByApp($path2));
    }
}
