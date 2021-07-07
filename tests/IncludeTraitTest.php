<?php
use Evas\Base\tests;

use Codeception\Util\Autoload;
use Evas\Base\Exceptions\FileNotFoundException;
use Evas\Base\Help\PhpHelp;
use Evas\Base\Traits\IncludeTrait;

Autoload::addNamespace('Evas\\Base', 'vendor/evas-php/evas-base/src');

class IncludeClassForTest
{
    use IncludeTrait;
}

class IncludeTraitTest extends \Codeception\Test\Unit
{
    protected $incHelp;
    
    protected function _before() {
        $this->incHelp = new IncludeClassForTest;
    }

    public function testCanInclude()
    {
        $filenameNoExists = __DIR__ .'/data/no-exists.txt';
        $this->assertFalse($this->incHelp->canInclude($filenameNoExists));
        $filenameExists = __DIR__ .'/data/includedFile.php';
        $this->assertTrue($this->incHelp->canInclude($filenameExists));
    }

    public function testInclude()
    {
        $filenameExists = __DIR__ .'/data/includedFile.php';
        $this->assertEquals(
            include $filenameExists, $this->incHelp->include($filenameExists)
        );
    }

    public function testIncludeException()
    {
        $filenameNoExists = PhpHelp::path(__DIR__ .'/data/no-exists.txt');
        $this->expectException(FileNotFoundException::class);
        $this->expectExceptionMessage("File \"$filenameNoExists\" not found");
        $this->incHelp->include($filenameNoExists);

        $this->expectException(FileNotFoundException::class);
        $this->expectExceptionMessage("File \"$filenameNoExists\" not found");
        $this->incHelp->throwIfNotCanInclude($filenameNoExists);
    }
}