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

use CoiSA\PhpCsFixer\Config;
use CoiSA\PhpCsFixer\RuleSet\Sets\CustomSet;
use PhpCsFixer\RuleSet\RuleSetDefinitionInterface;
use PhpCsFixer\RuleSet\RuleSets;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(Config::class)]
final class ConfigTest extends TestCase
{
    public function testRegistersThePresetOnTheConfiguration(): void
    {
        $config = new Config();

        self::assertSame([CustomSet::NAME => true], $config->getRules());
        self::assertCount(1, $config->getCustomRuleSets());
        self::assertInstanceOf(RuleSetDefinitionInterface::class, $config->getCustomRuleSets()[0]);
        self::assertSame(CustomSet::NAME, $config->getCustomRuleSets()[0]->getName());
        self::assertTrue($config->getRiskyAllowed());
    }

    public function testConstructionDoesNotChangeTheGlobalRegistry(): void
    {
        $names = RuleSets::getSetDefinitionNames();

        new Config('Local header');

        self::assertSame($names, RuleSets::getSetDefinitionNames());
    }

    public function testHeadersAreIndependentBetweenConfigurations(): void
    {
        $first = new Config('First header');
        $second = new Config('Second header');

        self::assertSame('First header', $first->getCustomRuleSets()[0]->getRules()['header_comment']['header']);
        self::assertSame('Second header', $second->getCustomRuleSets()[0]->getRules()['header_comment']['header']);
    }

    public function testApplicationRulesOverrideThePreset(): void
    {
        $overrides = ['concat_space' => ['spacing' => 'one'], 'header_comment' => false];
        $config = new Config('Preset header', $overrides);

        self::assertSame([CustomSet::NAME => true, ...$overrides], $config->getRules());
    }

    public function testApplicationCanDisableThePreset(): void
    {
        $config = new Config('', [CustomSet::NAME => false, 'concat_space' => false]);

        self::assertSame([CustomSet::NAME => false, 'concat_space' => false], $config->getRules());
    }
}
