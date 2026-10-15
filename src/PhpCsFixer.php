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

use PhpCsFixer\ConfigInterface;

/**
 * Class PhpCsFixer.
 *
 * @package CoiSA\PhpCsFixer
 */
abstract class PhpCsFixer
{
    /**
     * Create a PhpCsFixer configuration.
     *
     * @param list<string>                             $paths      owned files/directories and !directory exclusions
     * @param string                                   $header     optional raw or PHPDoc header text
     * @param array<string, array<string, mixed>|bool> $extraRules application rules applied last
     *
     * @return ConfigInterface the configured finder, rules and opt-in risky baseline
     */
    public static function create(array $paths, string $header = '', array $extraRules = []): ConfigInterface
    {
        $finder = new Finder($paths);
        $config = new Config($header, $extraRules);

        return $config->setFinder($finder);
    }
}
