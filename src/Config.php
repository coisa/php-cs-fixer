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

namespace CoiSA\PhpCsFixer;

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
     * Builds the CoiSA preset without mutating PHP CS Fixer's global registry.
     *
     * @param string                                   $header     an optional raw or PHPDoc file header
     * @param array<string, array<string, mixed>|bool> $extraRules application overrides, applied last
     */
    public function __construct(string $header = '', array $extraRules = [])
    {
        parent::__construct();

        $ruleSet = new CustomSet($header);

        $rules = array_merge(
            [$ruleSet->getName() => true],
            $extraRules,
        );

        $this
            ->registerCustomRuleSets([$ruleSet])
            ->setRiskyAllowed(true)
            ->setRules($rules)
        ;
    }
}
