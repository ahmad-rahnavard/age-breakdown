<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithFaker;

class AgeBreakdownTest extends TestCase
{
    use WithFaker;

    protected string $route;
    protected UploadedFile $testFile;

    protected function setUp(): void
    {
        parent::setUp();

        $this->route    = route('breakdown-ages');
        $sampleContent  = [
            'John,22',
            'Emma,15',
            'Jim,35',
            'Anna,22',
            'Joe,41',
            'Mia,31',
            'Peter,33',
            'Sara,31',
            'Ben,22',
            'Sofia,22'
        ];
        $this->testFile = $this->fakeUploadedFileWithContent('test-file.csv', $sampleContent);
    }

    /** @test */
    public function csv_file_is_required()
    {
        $response = $this->post($this->route)
            ->assertStatus(422);

        $this->assertArrayHasKey('file', $response['errors']);
        $this->assertEquals('The file field is required.', $response['errors']['file'][0]);
    }

    /** @test */
    public function the_file_must_be_a_valid_csv_file()
    {
        $invalidFile = $this->fakeUploadedFile('invalid-file', 'text/plain');
        $response    = $this->post($this->route, ['file' => $invalidFile])
            ->assertStatus(422);

        $this->assertArrayHasKey('file', $response['errors']);
        $this->assertEquals('The file must be a file of type: csv.', $response['errors']['file'][0]);

        $this->post($this->route, ['file' => $this->testFile])
            ->assertStatus(200);
    }

    /** @test */
    public function the_file_must_have_content()
    {
        $emptyFile = $this->fakeUploadedFileWithContent('empty-file.csv', []);

        $response = $this->post($this->route, ['file' => $emptyFile])
            ->assertStatus(422);

        $this->assertArrayHasKey('file', $response['errors']);
        $this->assertEquals('The file is empty!', $response['message']);
    }

    /** @test */ // can be separated in response structure tests
    public function json_response_has_expected_keys()
    {
        $this->post($this->route, ['file' => $this->testFile])
            ->assertJsonStructure(['status', 'message', 'data']);
    }

    /** @test */ // can be separated in response structure tests
    public function json_response_includes_a_data_key_of_array_type()
    {
        $response = $this->post($this->route, ['file' => $this->testFile]);

        $this->assertArrayHasKey('data', $response);
        $this->assertIsArray($response['data']);
    }

    /** @test */
    public function expected_percentage_in_response()
    {
        $testFile       = $this->fakeUploadedFileWithContent('test-file.csv', ['Ahmad,31']);
        $response       = $this->post($this->route, ['file' => $testFile]);
        $expectedResult = [31 => 100];

        $this->assertArrayHasKey(31, $response['data']);
        $this->assertEquals(100, $response['data'][31]);
        $this->assertEquals($expectedResult, $response['data']);
    }

    /** @test */
    public function expected_json_response()
    {
        $response       = $this->post($this->route, ['file' => $this->testFile]);
        $expectedResult = [
            22 => 40,
            15 => 10,
            35 => 10,
            41 => 10,
            31 => 20,
            33 => 10
        ];

        $this->assertEquals($expectedResult, $response['data']);
    }
}
