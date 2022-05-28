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

namespace CoiSA\PhpCsFixer;

use CoiSA\PhpCsFixer\RuleSet\RuleSets;
use CoiSA\PhpCsFixer\RuleSet\Sets\CustomSet;
use PhpCsFixer\Config as PhpCsFixerConfig;

/**
 * Class Config.
 *
 * @package CoiSA\PhpCsFixer
 */
final class Config extends PhpCsFixerConfig
{
    /**
     * Config constructor.
     */
    public function __construct(string $header = '', array $extraRules = [])
    {
        parent::__construct();

        $ruleSet = new CustomSet($header);

        $ruleSets = new RuleSets();
        $ruleSets->setRuleSetDefinition($ruleSet);

        $rules = array_merge(
            [$ruleSet->getName() => true],
            $extraRules
        );

        $this
            ->setRiskyAllowed(true)
            ->setRules($rules)
        ;
    }
}
