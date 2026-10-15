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

use CoiSA\PhpCsFixer\RuleSet\DefaultSet;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\RuleSet\RuleSet;
use PhpCsFixer\Tokenizer\Tokens;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RequiresPhp;
use PHPUnit\Framework\TestCase;

#[CoversClass(DefaultSet::class)]
final class PerStyleTest extends TestCase
{
    #[DataProvider('per31Examples')]
    public function testFormatsAvailablePer31Rules(string $input, string $expected): void
    {
        $formatted = $this->format($input);

        self::assertStringContainsString($expected, $formatted);
        self::assertSame($formatted, $this->format($formatted));
    }

    public static function per31Examples(): iterable
    {
        yield 'case braces removed' => [
            "<?php\nswitch (\$value) {\n    case 1: {\n        break;\n    }\n}",
            "case 1:\n        break;",
        ];
        yield 'private enum constants' => [
            '<?php enum Example { case One; protected const Hidden = self::One; }',
            'private const Hidden = self::One;',
        ];
        yield 'clone parentheses retained' => [
            '<?php $copy = clone($original);',
            'clone($original)',
        ];
        yield 'multiline case parentheses retained' => [
            "<?php switch (true) { case (\n\$first\n&& \$second\n): break; }",
            "case (\n",
        ];
        yield 'array bracket remains with return' => [
            "<?php return [\n'first',\n'second',\n];",
            "return [\n",
        ];
    }

    public function testCompactsEmptyClosures(): void
    {
        $formatted = $this->format("<?php \$noop = function () {\n};");

        self::assertMatchesRegularExpression('/function \(\)(?:: void)? \{\}/', $formatted);
        self::assertSame($formatted, $this->format($formatted));
    }

    #[RequiresPhp('>=8.5.0')]
    public function testPlacesMultilinePipeOperatorsFirst(): void
    {
        $formatted = $this->format("<?php \$result = \$input |>\n    trim(...) |>\n    strtoupper(...);");

        self::assertStringContainsString("\$input\n    |> trim(...)\n    |> strtoupper(...)", $formatted);
        self::assertSame($formatted, $this->format($formatted));
    }

    private function format(string $source): string
    {
        Tokens::clearCache();
        $tokens = Tokens::fromCode($source);
        $factory = (new FixerFactory())->registerBuiltInFixers()->useRuleSet(new RuleSet((new DefaultSet())->getRules()));

        foreach ($factory->getFixers() as $fixer) {
            $fixer->fix(new \SplFileInfo(__FILE__), $tokens);
        }

        return $tokens->generateCode();
    }
}
