<?php

namespace Opdavies\Tests\GmailFilterBuilder\Console\Command;

use Opdavies\GmailFilterBuilder\Console\Command\GenerateCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;

class GenerateFiltersTest extends TestCase
{
    const INPUT_FILENAME = __DIR__ . '/../../../fixtures/simple/input.php';
    const OUTPUT_FILENAME = 'test-output.xml';

    /** @var CommandTester */
    private $commandTester;

    /** @var Filesystem */
    private $fs;

    protected function setUp()
    {
        parent::setUp();

        $this->commandTester = new CommandTester(new GenerateCommand());
        $this->fs = new Filesystem();
    }

    protected function tearDown()
    {
        // Ensure that files generated during tests are removed to prevent
        // failures on future runs.
        $this->fs->remove([self::OUTPUT_FILENAME]);
    }

    /** @test */
    public function the_output_filename_matches_the_input_name()
    {
        $this->commandTester->execute([
            '--input-file' => self::INPUT_FILENAME,
        ]);

        $this->assertTrue($this->fs->exists('input.xml'));

        $this->fs->remove('input.xml');
    }

    /** @test */
    public function the_output_filename_can_be_set_explicity()
    {
        $outputFilename = 'a-different-filename.xml';

        $this->commandTester->execute([
            '--input-file' => self::INPUT_FILENAME,
            '--output-file' => $outputFilename,
        ]);

        $this->assertTrue($this->fs->exists($outputFilename));

        $this->fs->remove($outputFilename);
    }

    /** @test */
    public function it_converts_filters_from_php_to_minified_xml()
    {
        $this->commandTester->execute([
            '--input-file' => self::INPUT_FILENAME,
            '--output-file' => self::OUTPUT_FILENAME,
        ]);

        $this->assertTrue($this->fs->exists(self::OUTPUT_FILENAME));

        $expected = file_get_contents(__DIR__ . '/../../../fixtures/simple/output.xml');
        $result = file_get_contents(self::OUTPUT_FILENAME);

        $this->assertEquals(trim($expected), $result);
    }

    /** @test */
    public function it_converts_filters_from_php_to_expanded_xml()
    {
        $this->commandTester->execute([
            '--input-file' => self::INPUT_FILENAME,
            '--output-file' => self::OUTPUT_FILENAME,
            '--expanded' => true,
        ]);

        $this->assertTrue($this->fs->exists(self::OUTPUT_FILENAME));

        $expected = file_get_contents(__DIR__ . '/../../../fixtures/simple/output-expanded.xml');
        $result = file_get_contents(self::OUTPUT_FILENAME);

        $this->assertEquals(trim($expected), $result);
    }
}
