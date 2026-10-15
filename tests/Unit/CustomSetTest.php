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

namespace CoiSA\PhpCsFixer\Tests\Unit;

use CoiSA\PhpCsFixer\RuleSet\Sets\CustomSet;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(CustomSet::class)]
final class CustomSetTest extends TestCase
{
    #[DataProvider('provideNormalizesOnlyCompleteWrappedHeadersCases')]
    public function testNormalizesOnlyCompleteWrappedHeaders(string $input, string $expected): void
    {
        self::assertSame([
            'header' => $expected,
            'comment_type' => 'PHPDoc',
            'location' => 'after_open',
        ], (new CustomSet($input))->getRules()['header_comment']);
    }

    public static function provideNormalizesOnlyCompleteWrappedHeadersCases(): iterable
    {
        yield 'raw text' => ['Raw header', 'Raw header'];

        yield 'raw leading star' => ['* Important', '* Important'];

        yield 'raw closing delimiter' => ['Raw header */', 'Raw header */'];

        yield 'incomplete block' => ['/** Incomplete raw', '/** Incomplete raw'];

        yield 'multiple blocks' => ["/** first */\n/** second */", "/** first */\n/** second */"];

        yield 'raw multiline' => ["Raw\n * Important\nraw suffix */", "Raw\n * Important\nraw suffix */"];

        yield 'raw whitespace' => ['  Raw header  ', '  Raw header  '];

        yield 'zero' => ['0', '0'];

        yield 'plain block' => ['/* Plain block */', 'Plain block'];

        yield 'one-line PHPDoc' => ['/** PHPDoc header */', 'PHPDoc header'];

        yield 'multiline PHPDoc' => ["/**\n * First line\n * Second line\n */", "First line\nSecond line"];

        yield 'wrapped whitespace' => [" \t/** Header */\n", 'Header'];
    }

    #[DataProvider('provideOmitsTheHeaderRuleForEmptyHeadersCases')]
    public function testOmitsTheHeaderRuleForEmptyHeaders(string $header): void
    {
        self::assertArrayNotHasKey('header_comment', (new CustomSet($header))->getRules());
    }

    public static function provideOmitsTheHeaderRuleForEmptyHeadersCases(): iterable
    {
        yield 'empty string' => [''];

        yield 'whitespace' => [" \t\n"];

        yield 'empty block' => ['/** */'];
    }

    public function testResolvesYearPlaceholdersAndCollapsesTheCurrentYearRange(): void
    {
        $year = date('Y');
        $previousYear = (string) ((int) $year - 1);
        $header = 'Copyright %year% / %year%-%year% / ' . $previousYear . '-%year%';

        self::assertSame(
            'Copyright ' . $year . ' / ' . $year . ' / ' . $previousYear . '-' . $year,
            (new CustomSet($header))->getRules()['header_comment']['header'],
        );
    }

    public function testTargetsPhp83AndTheVersionedPerStyleWithRiskyRules(): void
    {
        $set = new CustomSet();
        $rules = $set->getRules();

        self::assertSame('@CoiSA/default', $set->getName());
        self::assertTrue($set->isRisky());
        self::assertTrue($rules['@PHP8x3Migration']);
        self::assertTrue($rules['@PHP8x3Migration:risky']);
        self::assertTrue($rules['@PER-CS3x0']);
        self::assertTrue($rules['@PER-CS3x0:risky']);
        self::assertTrue($rules['date_time_immutable']);
        self::assertArrayNotHasKey('@PHP8x4Migration', $rules);
        self::assertArrayNotHasKey('@PHPUnit8x4Migration:risky', $rules);
    }
}
