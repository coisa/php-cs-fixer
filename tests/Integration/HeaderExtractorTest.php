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

use CoiSA\PhpCsFixer\HeaderExtractor;
use CoiSA\PhpCsFixer\RuleSet\Sets\CustomSet;
use CoiSA\PhpCsFixer\Tests\FilesystemTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(HeaderExtractor::class)]
final class HeaderExtractorTest extends FilesystemTestCase
{
    public function testReadsTheConfigurationHeaderWithoutExecutingTheFile(): void
    {
        $header = '/** Copyright (c) 2020-%year% Example */';
        $path = $this->writeFile('config.php', "<?php\n" . $header . "\nthrow new RuntimeException('Must not execute.');");

        self::assertSame($header, (new HeaderExtractor())->extractFromFile($path));
    }

    public function testReturnsNoHeaderForAnEmptyFile(): void
    {
        $path = $this->writeFile('empty.php', '');

        self::assertSame('', (new HeaderExtractor())->extractFromFile($path));
    }

    public function testReportsAnUnreadableConfiguration(): void
    {
        $extractor = new HeaderExtractor();
        $this->expectException(\RuntimeException::class);

        $extractor->extractFromFile($this->directory . '/missing.php');
    }

    public function testRenderedCopyrightRemainsDynamicWhenReadAgain(): void
    {
        $path = $this->writeFile('config.php', '<?php /** Copyright (c) 2020-2021 Example */ return [];');
        $extractor = new HeaderExtractor();
        $first = (new CustomSet($extractor->extractFromFile($path)))->getRules()['header_comment']['header'];

        self::assertSame('Copyright (c) 2020-' . date('Y') . ' Example', $first);

        file_put_contents($path, '<?php /** ' . $first . ' */ return [];');
        $second = (new CustomSet($extractor->extractFromFile($path)))->getRules()['header_comment']['header'];

        self::assertSame($first, $second);
    }
}
