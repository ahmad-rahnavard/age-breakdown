<?php

namespace Tests;

use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withHeaders(['Accept' => 'application/json']);
    }

    /**
     * @param string $name
     * @param string $mimeType
     * @param int    $size
     *
     * @return File
     */
    protected function fakeUploadedFile(string $name, string $mimeType, int $size = 1024): File
    {
        return UploadedFile::fake()->create($name, $size, $mimeType);
    }

    /**
     * @param string $name
     * @param array  $fileLines
     *
     * @return File
     */
    protected function fakeUploadedFileWithContent(string $name, array $fileLines = []): File
    {
        $contents = implode("\n", $fileLines);

        return UploadedFile::fake()->createWithContent($name, $contents);
    }
}
