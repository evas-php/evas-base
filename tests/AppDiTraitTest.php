<?php
/**
 * Тест Di-контейнера приложения.
 * @package evas-php/evas-base
 * @author Egor Vasyakin <egor@evas-php.com>
 */
use Evas\Base\tests;

use Codeception\Util\Autoload;
use Evas\Base\App;
use Evas\Di\Container;

Autoload::addNamespace('Evas\\Base', 'vendor/evas-php/evas-base/src');
Autoload::addNamespace('Evas\\Di', 'vendor/evas-php/evas-di/src');

class AppDiTraitTest extends \Codeception\Test\Unit
{
    public function testManualSetDi()
    {
        $di = new Container;
        $di->set('test', 'testManualSetDi');
        App::setDi($di);
        $this->assertTrue(App::has('test'));
        $this->assertEquals('testManualSetDi', App::get('test'));
    }

    public function testAutoSetDi()
    {
        App::set('test', 'testAutoSetDi');
        $this->assertTrue(App::has('test'));
        $this->assertEquals('testAutoSetDi', App::get('test'));
    }
}
