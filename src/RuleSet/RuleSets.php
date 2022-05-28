<?php

declare(strict_types=1);

/**
 * This file is part of coisa/php-cs-fixer.
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 *
 * @link      https://github.com/coisa/php-cs-fixer
 * @copyright Copyright (c) 2020-2022 Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

namespace CoiSA\PhpCsFixer\RuleSet;

use PhpCsFixer\RuleSet\RuleSetDescriptionInterface;
use PhpCsFixer\RuleSet\RuleSets as PhpCsFixerRuleSets;

/**
 * Class RuleSets.
 *
 * @package CoiSA\PhpCsFixer\RuleSet
 */
final class RuleSets
{
    /** @var RuleSetDescriptionInterface[] */
    private array $setDefinitions;

    /**
     * RuleSets constructor.
     */
    public function __construct()
    {
        $this->setDefinitions = PhpCsFixerRuleSets::getSetDefinitions();
    }

    /**
     * Set definition for a custom rule set.
     */
    public function setRuleSetDefinition(RuleSetDescriptionInterface $ruleSet): void
    {
        $this->setDefinitions[$ruleSet->getName()] = $ruleSet;

        // Workaround for setDefinition() not being available in PhpCsFixer
        $reflectionProperty = new \ReflectionProperty(PhpCsFixerRuleSets::class, 'setDefinitions');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue(null, $this->setDefinitions);
    }
}
