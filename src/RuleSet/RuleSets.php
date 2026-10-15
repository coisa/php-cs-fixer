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

/**
 * This file is part of coisa/php-cs-fixer.
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 *
 * @see      https://github.com/coisa/php-cs-fixer
 *
 * @copyright Copyright (c) 2020-2026 Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

namespace CoiSA\PhpCsFixer\RuleSet;

use PhpCsFixer\RuleSet\RuleSetDefinitionInterface;
use PhpCsFixer\RuleSet\RuleSets as PhpCsFixerRuleSets;

/**
 * Class RuleSets.
 *
 * @package CoiSA\PhpCsFixer\RuleSet
 */
final class RuleSets
{
    /**
     * Explicitly registers a custom definition through the supported public API.
     *
     * Prefer Config's per-instance registration for ordinary consumer configuration.
     *
     * @param RuleSetDefinitionInterface $ruleSet the definition to register globally
     *
     * @throws \InvalidArgumentException if the name is invalid or already registered
     */
    public function setRuleSetDefinition(RuleSetDefinitionInterface $ruleSet): void
    {
        PhpCsFixerRuleSets::registerCustomRuleSet($ruleSet);
    }
}
