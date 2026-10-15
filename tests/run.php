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

use CoiSA\PhpCsFixer\PhpCsFixer;
use CoiSA\PhpCsFixer\RuleSet\RuleSets;
use CoiSA\PhpCsFixer\RuleSet\Sets\CustomSet;
use PhpCsFixer\RuleSet\RuleSet;
use PhpCsFixer\RuleSet\RuleSetDefinitionInterface;

require dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Runs the fixer or synthetic PHP fixture with bounded time and captured output.
 *
 * @param list<string> $arguments the PHP command arguments
 *
 * @return array{int, string} the exit status and diagnostic output
 */
function runPhp(array $arguments): array
{
    $process = proc_open([PHP_BINARY, ...$arguments], [
        0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w'],
    ], $pipes);
    if (!is_resource($process)) {
        throw new RuntimeException('Cannot start the fixture process.');
    }
    fclose($pipes[0]);
    stream_set_blocking($pipes[1], false);
    stream_set_blocking($pipes[2], false);
    $output   = '';
    $deadline = microtime(true) + 10;
    $bounded  = true;
    do {
        $output .= stream_get_contents($pipes[1]) . stream_get_contents($pipes[2]);
        $status = proc_get_status($process);
        if (microtime(true) > $deadline || strlen($output) > 262144) {
            proc_terminate($process, 9);
            $bounded = false;

            break;
        }
        if ($status['running']) {
            usleep(1000);
        }
    } while ($status['running']);
    $output .= stream_get_contents($pipes[1]) . stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    $closed = proc_close($process);
    verify($bounded, 'Fixture exceeded its time or output bound.');

    return [$status['exitcode'] >= 0 ? $status['exitcode'] : $closed, $output];
}

/**
 * Verifies an observable contract without relying on PHP's disabled assertions.
 *
 * @param bool   $condition whether the expected behavior occurred
 * @param string $message   the failure context
 */
function verify(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

$root   = dirname(__DIR__);
$names  = \PhpCsFixer\RuleSet\RuleSets::getSetDefinitionNames();
$first  = PhpCsFixer::create([], '/** First %year% */', ['concat_space' => false]);
$second = PhpCsFixer::create([], '/** Second header */');
verify($names === \PhpCsFixer\RuleSet\RuleSets::getSetDefinitionNames(), 'Construction mutated the global registry.');
verify($first->getCustomRuleSets()[0] instanceof RuleSetDefinitionInterface, 'The definition must use the current public interface.');
verify($first->getCustomRuleSets()[0]->getRules()['header_comment'] !== $second->getCustomRuleSets()[0]->getRules()['header_comment'], 'Headers leaked between configurations.');
verify(false === $first->getRules()['concat_space'], 'Application overrides must win.');
verify($first->getRiskyAllowed(), 'The existing opt-in CoiSA risky policy changed.');
verify($first->getCustomRuleSets()[0]->getRules()['header_comment']['header'] === 'First ' . date('Y'), 'One-line PHPDoc header normalization failed.');
verify('Raw header' === (new CustomSet('Raw header'))->getRules()['header_comment']['header'], 'Raw header normalization failed.');
foreach (['* Important', 'Raw header */', '/** Incomplete raw', "/** first */\n/** second */", "Raw\n * Important\nraw suffix */", '  Raw header  '] as $rawHeader) {
    verify($rawHeader === (new CustomSet($rawHeader))->getRules()['header_comment']['header'], 'Raw header contents were stripped: ' . $rawHeader);
}
verify('Plain block' === (new CustomSet('/* Plain block */'))->getRules()['header_comment']['header'], 'Plain block header normalization failed.');
verify('0' === (new CustomSet('0'))->getRules()['header_comment']['header'], 'A nonempty zero header was lost.');
verify((new CustomSet())->isRisky(), 'The definition must declare its risky rules.');
(new RuleSets())->setRuleSetDefinition(new CustomSet());
verify(isset((new RuleSet([CustomSet::NAME => true]))->getRules()['concat_space']), 'The explicit compatibility adapter failed.');

$temp = sys_get_temp_dir() . '/coisa-style-' . bin2hex(random_bytes(8));
mkdir($temp . '/input/excluded', 0o700, true);

try {
    $fixture  = $temp . '/input/sample.php';
    $excluded = $temp . '/input/excluded/untouched.php';
    $input    = '<?php $message="ok";echo $message;';
    file_put_contents($fixture, $input);
    file_put_contents($excluded, $input);
    $config = $temp . '/config.php';
    $header = "/**\n * Synthetic header %year%\n */";
    file_put_contents($config, "<?php\nrequire " . var_export($root . '/vendor/autoload.php', true)
        . ";\nreturn CoiSA\\PhpCsFixer\\PhpCsFixer::create("
        . var_export([$temp . '/input', '!excluded'], true) . ', ' . var_export($header, true) . ");\n");
    $files = iterator_to_array(PhpCsFixer::create([$temp . '/input', '!excluded'])->getFinder());
    verify(1 === count($files), 'The documented !directory exclusion did not work.');
    $command           = [$root . '/vendor/bin/php-cs-fixer', 'fix', '--config=' . $config, '--using-cache=no'];
    [$status, $output] = runPhp([...$command, '--dry-run', '--diff']);
    verify(8 === $status, 'Initial dry-run must identify changes: ' . $output);
    [$status, $output] = runPhp($command);
    verify(0 === $status, 'Applying the preset failed: ' . $output);
    $formatted = file_get_contents($fixture);
    verify(str_contains($formatted, 'Synthetic header ' . date('Y')), 'Header placeholders were not resolved.');
    verify(!str_contains($formatted, '%year%'), 'A raw year placeholder remained.');
    verify(file_get_contents($excluded) === $input, 'Excluded input was changed.');
    [$status, $output] = runPhp([...$command, '--dry-run', '--diff']);
    verify(0 === $status, 'Second pass must be idempotent: ' . $output);
    [$status, $output] = runPhp(['-l', $fixture]);
    verify(0 === $status, 'Formatted PHP does not compile: ' . $output);
    [$status, $output] = runPhp([$fixture]);
    verify(0 === $status && 'ok' === $output, 'Formatting changed the synthetic behavior.');
    echo "PASS public definitions, isolated headers, overrides, risky policy, exclusions, CLI, lint and idempotency\n";
} finally {
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($temp, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST,
    );
    foreach ($files as $file) {
        $file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname());
    }
    rmdir($temp);
}
