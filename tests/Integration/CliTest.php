<?php

/**
 * This file is part of coisa/php-cs-fixer.
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 *
 * @link      https://github.com/coisa/php-cs-fixer
 * @copyright Copyright (c) 2020-2026 Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

declare(strict_types=1);

namespace CoiSA\PhpCsFixer\Tests\Integration;

use CoiSA\PhpCsFixer\Tests\FilesystemTestCase;
use PHPUnit\Framework\Attributes\CoversNothing;
use Symfony\Component\Process\Process;

/**
 * @internal
 */
#[CoversNothing]
final class CliTest extends FilesystemTestCase
{
    private const INPUT = '<?php $message="ok";echo $message;';

    private string $fixture;
    private string $excluded;
    private string $config;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fixture = $this->writeFile('input/file with spaces.php', self::INPUT);
        $this->excluded = $this->writeFile('input/excluded/untouched.php', self::INPUT);
        $this->config = $this->writeFile('config.php', "<?php\nrequire "
            . var_export(\dirname(__DIR__, 2) . '/vendor/autoload.php', true)
            . ";\nreturn CoiSA\\PhpCsFixer\\PhpCsFixer::create("
            . var_export([$this->directory . '/input', '!excluded'], true)
            . ", '/** Synthetic header %year% */');\n");
    }

    public function testDryRunReportsChangesWithoutWritingFiles(): void
    {
        $process = $this->fix('--dry-run', '--diff');

        self::assertSame(8, $process->getExitCode(), $process->getOutput() . $process->getErrorOutput());
        self::assertStringContainsString('Synthetic header ' . date('Y'), $process->getOutput());
        self::assertSame(self::INPUT, file_get_contents($this->fixture));
        self::assertSame(self::INPUT, file_get_contents($this->excluded));
    }

    public function testAppliesTheHeaderAndPreservesExclusionsSyntaxAndBehavior(): void
    {
        $process = $this->fix();

        self::assertSame(0, $process->getExitCode(), $process->getOutput() . $process->getErrorOutput());
        $formatted = file_get_contents($this->fixture);
        self::assertNotSame(self::INPUT, $formatted);
        self::assertStringContainsString('Synthetic header ' . date('Y'), $formatted);
        self::assertStringNotContainsString('%year%', $formatted);
        self::assertSame(self::INPUT, file_get_contents($this->excluded));

        $lint = $this->runPhp(['-l', $this->fixture]);
        self::assertSame(0, $lint->getExitCode(), $lint->getOutput() . $lint->getErrorOutput());

        $execution = $this->runPhp([$this->fixture]);
        self::assertSame(0, $execution->getExitCode(), $execution->getErrorOutput());
        self::assertSame('ok', $execution->getOutput());
    }

    public function testFormattingIsIdempotentWithoutACache(): void
    {
        $first = $this->fix();
        self::assertSame(0, $first->getExitCode(), $first->getOutput() . $first->getErrorOutput());
        $formatted = file_get_contents($this->fixture);

        $second = $this->fix('--dry-run', '--diff');

        self::assertSame(0, $second->getExitCode(), $second->getOutput() . $second->getErrorOutput());
        self::assertSame($formatted, file_get_contents($this->fixture));
    }

    public function testPreservesByteOperationsStringContentsAndPropertyUnsetting(): void
    {
        $input = <<<'PHP'
            <?php
            final class Payload
            {
                public string $value = 'assigned';

                public function clear(): void
                {
                    unset($this->value);
                }
            }
            $payload = new Payload();
            $payload->clear();
            $text = "line with space " . "\n";
            echo json_encode([strlen('é'), bin2hex(substr('é', 0, 1)), $text, isset($payload->value)]);
            PHP;
        // Put a literal trailing space inside the multiline string without storing
        // trailing whitespace in this test's own source file.
        $input = str_replace('"line with space " . "\n"', "'line with space \n'", $input);
        file_put_contents($this->fixture, $input);

        $process = $this->fix();
        self::assertSame(0, $process->getExitCode(), $process->getOutput() . $process->getErrorOutput());

        $execution = $this->runPhp([$this->fixture]);
        self::assertSame(0, $execution->getExitCode(), $execution->getErrorOutput());
        self::assertSame([2, 'c3', "line with space \n", false], json_decode($execution->getOutput(), true, flags: JSON_THROW_ON_ERROR));
    }

    public function testMigratesDateTimeToDateTimeImmutable(): void
    {
        file_put_contents($this->fixture, '<?php echo (new DateTime("2000-01-01"))->format("Y-m-d");');

        $process = $this->fix();
        self::assertSame(0, $process->getExitCode(), $process->getOutput() . $process->getErrorOutput());
        self::assertStringContainsString('new DateTimeImmutable(', file_get_contents($this->fixture));

        $execution = $this->runPhp([$this->fixture]);
        self::assertSame(0, $execution->getExitCode(), $execution->getErrorOutput());
        self::assertSame('2000-01-01', $execution->getOutput());
    }

    private function fix(string ...$arguments): Process
    {
        return $this->runPhp([
            \dirname(__DIR__, 2) . '/vendor/bin/php-cs-fixer', 'fix',
            '--config=' . $this->config, '--using-cache=no', ...$arguments,
        ]);
    }

    private function runPhp(array $arguments): Process
    {
        $process = new Process([PHP_BINARY, ...$arguments], $this->directory, timeout: 10);
        $process->run();

        return $process;
    }
}
