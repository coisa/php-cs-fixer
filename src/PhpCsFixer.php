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
     */
    public static function create(array $paths, string $header = '', array $extraRules = []): ConfigInterface
    {
        $finder = new Finder($paths);
        $config = new Config($header, $extraRules);

        return $config->setFinder($finder);
    }
}
