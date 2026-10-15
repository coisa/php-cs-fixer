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

use CoiSA\PhpCsFixer\HeaderExtractor;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(HeaderExtractor::class)]
final class HeaderExtractorTest extends TestCase
{
    #[DataProvider('headers')]
    public function testExtractsOnlyTheOpeningBlockComment(string $source, string $expected): void
    {
        self::assertSame($expected, (new HeaderExtractor())->extract($source));
    }

    public static function headers(): iterable
    {
        yield 'PHPDoc' => ["<?php\n/** License %year% */\ndeclare(strict_types=1);", '/** License %year% */'];
        yield 'block comment' => ['<?php /* License */ return [];', '/* License */'];
        yield 'multiline whitespace' => ["<?php\n\t\n/**\n * License\n */\nreturn [];", "/**\n * License\n */"];
        yield 'first block only' => ['<?php /** First */ /** Second */ return [];', '/** First */'];
        yield 'no evaluation' => ['<?php /** License */ throw new RuntimeException();', '/** License */'];
        yield 'empty source' => ['', ''];
        yield 'no header' => ['<?php return [];', ''];
        yield 'body PHPDoc after declare' => ['<?php declare(strict_types=1); /** @var array $rules */ $rules = [];', ''];
        yield 'body PHPDoc after code' => ['<?php $rules = []; /** Internal note */ return $rules;', ''];
        yield 'comment inside a string' => ['<?php $header = "/** Not a comment */";', ''];
        yield 'line comment' => ["<?php // Internal note\nreturn [];", ''];
        yield 'incomplete block' => ['<?php /** Incomplete', ''];
        yield 'plain text' => ['/** Not PHP */', ''];
    }
}
