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

use CoiSA\PhpCsFixer\RuleSet\DefaultSet;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(DefaultSet::class)]
final class DefaultSetTest extends TestCase
{
    #[DataProvider('provideNormalizesOnlyCompleteWrappedHeadersCases')]
    public function testNormalizesOnlyCompleteWrappedHeaders(string $input, string $expected): void
    {
        self::assertSame([
            'header' => $expected,
            'comment_type' => 'PHPDoc',
            'location' => 'after_open',
        ], (new DefaultSet($input))->getRules()['header_comment']);
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
        self::assertArrayNotHasKey('header_comment', (new DefaultSet($header))->getRules());
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
            (new DefaultSet($header))->getRules()['header_comment']['header'],
        );
    }

    public function testUsesAutomaticProjectMigrationsWithExplicitPer31Policies(): void
    {
        $set = new DefaultSet();
        $rules = $set->getRules();

        self::assertSame('@CoiSA/default', $set->getName());
        self::assertTrue($set->isRisky());
        self::assertTrue($rules['@auto']);
        self::assertTrue($rules['@autoPHPMigration:risky']);
        self::assertTrue($rules['no_unneeded_braces']);
        self::assertTrue($rules['protected_to_private']);
        self::assertTrue($rules['date_time_immutable']);
        self::assertFalse($rules['mb_str_functions']);
        self::assertFalse($rules['no_unset_on_property']);
        self::assertFalse($rules['no_trailing_whitespace_in_string']);
        self::assertTrue($rules['@PhpCsFixer']);
        self::assertTrue($rules['@PhpCsFixer:risky']);
        self::assertArrayNotHasKey('@DoctrineAnnotation', $rules);
        self::assertArrayNotHasKey('@auto:risky', $rules);
        self::assertArrayNotHasKey('@PHP8x3Migration', $rules);
        self::assertArrayNotHasKey('@PHP8x4Migration', $rules);
        self::assertArrayNotHasKey('@PHPUnit8x4Migration:risky', $rules);
    }

    #[DataProvider('provideRefreshesCopyrightYearsWithoutChangingOtherDatesCases')]
    public function testRefreshesCopyrightYearsWithoutChangingOtherDates(string $input, string $expected): void
    {
        self::assertSame($expected, (new DefaultSet($input))->getRules()['header_comment']['header']);
    }

    public static function provideRefreshesCopyrightYearsWithoutChangingOtherDatesCases(): iterable
    {
        $year = date('Y');
        $past = (string) ((int) $year - 2);
        $future = (string) ((int) $year + 1);

        yield 'stale range' => ['Copyright (c) 2020-2021 Example', 'Copyright (c) 2020-' . $year . ' Example'];

        yield 'spaced range' => ['Copyright 2020 – 2021 Example', 'Copyright 2020-' . $year . ' Example'];

        yield 'single creation year' => ['Copyright ' . $past . ' Example', 'Copyright ' . $past . '-' . $year . ' Example'];

        yield 'same year range' => ['Copyright ' . $year . '-' . $year . ' Example', 'Copyright ' . $year . ' Example'];

        yield 'copyright tag' => ['@copyright ' . $past . ' Example', '@copyright ' . $past . '-' . $year . ' Example'];

        yield 'copyright symbol' => ['Copyright © ' . $past . ' Example', 'Copyright © ' . $past . '-' . $year . ' Example'];

        yield 'placeholder' => ['Copyright 2020-%year% Example', 'Copyright 2020-' . $year . ' Example'];

        yield 'future start is preserved' => ['Copyright ' . $future . ' Example', 'Copyright ' . $future . ' Example'];

        yield 'other dates are preserved' => ['Released 2020-2021, reviewed 2022', 'Released 2020-2021, reviewed 2022'];

        yield 'literal same-year date is preserved' => ['Released ' . $year . '-' . $year, 'Released ' . $year . '-' . $year];

        yield 'full calendar date is preserved' => ['Copyright 2020-01-15 Example', 'Copyright 2020-01-15 Example'];
    }
}
