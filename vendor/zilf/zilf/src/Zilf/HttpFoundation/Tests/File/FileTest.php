<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zilf\HttpFoundation\Tests\File;

use Zilf\HttpFoundation\File\File;
use Zilf\HttpFoundation\File\MimeType\MimeTypeGuesser;

class FileTest extends \PHPUnit_Framework_TestCase
{
    protected $file;

    public function testGetMimeTypeUsesMimeTypeGuessers()
    {
        $file = new File(__DIR__ . '/Fixtures/test.gif');
        $guesser = $this->createMockGuesser($file->getPathname(), 'image/gif');

        MimeTypeGuesser::getInstance()->register($guesser);

        $this->assertEquals('image/gif', $file->getMimeType());
    }

    public function testGuessExtensionWithoutGuesser()
    {
        $file = new File(__DIR__ . '/Fixtures/directory/.empty');

        $this->assertNull($file->guessExtension());
    }

    public function testGuessExtensionIsBasedOnMimeType()
    {
        $file = new File(__DIR__ . '/Fixtures/test');
        $guesser = $this->createMockGuesser($file->getPathname(), 'image/gif');

        MimeTypeGuesser::getInstance()->register($guesser);

        $this->assertEquals('gif', $file->guessExtension());
    }

    /**
     * @requires extension fileinfo
     */
    public function testGuessExtensionWithReset()
    {
        $file = new File(__DIR__ . '/Fixtures/other-file.example');
        $guesser = $this->createMockGuesser($file->getPathname(), 'image/gif');
        MimeTypeGuesser::getInstance()->register($guesser);

        $this->assertEquals('gif', $file->guessExtension());

        MimeTypeGuesser::reset();

        $this->assertNull($file->guessExtension());
    }

    public function testConstructWhenFileNotExists()
    {
        $this->setExpectedException('Zilf\HttpFoundation\File\Exception\FileNotFoundException');

        new File(__DIR__.'/Fixtures/not_here');
    }

    public function testMove()
    {
        $path = __DIR__.'/Fixtures/test.copy.gif';
        $targetDir = __DIR__ . '/Fixtures/directory';
        $targetPath = $targetDir.'/test.copy.gif';
        @unlink($path);
        @unlink($targetPath);
        copy(__DIR__ . '/Fixtures/test.gif', $path);

        $file = new File($path);
        $movedFile = $file->move($targetDir);
        $this->assertInstanceOf('Zilf\HttpFoundation\File\File', $movedFile);

        $this->assertFileExists($targetPath);
        $this->assertFileNotExists($path);
        $this->assertEquals(realpath($targetPath), $movedFile->getRealPath());

        @unlink($targetPath);
    }

    public function testMoveWithNewName()
    {
        $path = __DIR__.'/Fixtures/test.copy.gif';
        $targetDir = __DIR__ . '/Fixtures/directory';
        $targetPath = $targetDir.'/test.newname.gif';
        @unlink($path);
        @unlink($targetPath);
        copy(__DIR__ . '/Fixtures/test.gif', $path);

        $file = new File($path);
        $movedFile = $file->move($targetDir, 'test.newname.gif');

        $this->assertFileExists($targetPath);
        $this->assertFileNotExists($path);
        $this->assertEquals(realpath($targetPath), $movedFile->getRealPath());

        @unlink($targetPath);
    }

    public function getFilenameFixtures()
    {
        return array(
            array('original.gif', 'original.gif'),
            array('..\\..\\original.gif', 'original.gif'),
            array('../../original.gif', 'original.gif'),
            array('файлfile.gif', 'файлfile.gif'),
            array('..\\..\\файлfile.gif', 'файлfile.gif'),
            array('../../файлfile.gif', 'файлfile.gif'),
        );
    }

    /**
     * @dataProvider getFilenameFixtures
     */
    public function testMoveWithNonLatinName($filename, $sanitizedFilename)
    {
        $path = __DIR__ . '/Fixtures/' .$sanitizedFilename;
        $targetDir = __DIR__ . '/Fixtures/directory/';
        $targetPath = $targetDir.$sanitizedFilename;
        @unlink($path);
        @unlink($targetPath);
        copy(__DIR__ . '/Fixtures/test.gif', $path);

        $file = new File($path);
        $movedFile = $file->move($targetDir, $filename);
        $this->assertInstanceOf('Zilf\HttpFoundation\File\File', $movedFile);

        $this->assertFileExists($targetPath);
        $this->assertFileNotExists($path);
        $this->assertEquals(realpath($targetPath), $movedFile->getRealPath());

        @unlink($targetPath);
    }

    public function testMoveToAnUnexistentDirectory()
    {
        $sourcePath = __DIR__.'/Fixtures/test.copy.gif';
        $targetDir = __DIR__.'/Fixtures/directory/sub';
        $targetPath = $targetDir.'/test.copy.gif';
        @unlink($sourcePath);
        @unlink($targetPath);
        @rmdir($targetDir);
        copy(__DIR__ . '/Fixtures/test.gif', $sourcePath);

        $file = new File($sourcePath);
        $movedFile = $file->move($targetDir);

        $this->assertFileExists($targetPath);
        $this->assertFileNotExists($sourcePath);
        $this->assertEquals(realpath($targetPath), $movedFile->getRealPath());

        @unlink($sourcePath);
        @unlink($targetPath);
        @rmdir($targetDir);
    }

    protected function createMockGuesser($path, $mimeType)
    {
        $guesser = $this->getMock('Zilf\HttpFoundation\File\MimeType\MimeTypeGuesserInterface');
        $guesser
            ->expects($this->once())
            ->method('guess')
            ->with($this->equalTo($path))
            ->will($this->returnValue($mimeType));

        return $guesser;
    }
}
