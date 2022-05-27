<?php

declare(strict_types=1);

/*
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

use PhpCsFixer\Config;
use PhpCsFixer\ConfigInterface;
use PhpCsFixer\Finder;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerFactory;

/**
 * Class PhpCsFixer.
 *
 * @package CoiSA\PhpCsFixer
 */
abstract class PhpCsFixer
{
    public static function create(array $paths = [], string $header = ''): ConfigInterface
    {
        $rules  = self::createRules($header);
        $config = self::createConfig($rules);

        if (empty($paths)) {
            return $config;
        }

        $finder = self::createFinder($paths);

        return $config->setFinder($finder);
    }

    private static function createFinder(array $paths = []): Finder
    {
        $finder = Finder::create()
            ->files()
            ->ignoreDotFiles(false)
            ->ignoreVCSIgnored(true)
            ->name('*.php')
            ->notPath('vendor/')
        ;

        foreach ($paths as $path) {
            if (!file_exists($path)) {
                continue;
            }

            if (is_file($path)) {
                $finder->append([$path]);

                continue;
            }

            if ('!' === mb_substr($path, 0, 1)) {
                $finder->exclude(mb_substr($path, 1));

                continue;
            }

            $finder->in($path);
        }

        return $finder;
    }

    private static function createConfig(array $rules = []): ConfigInterface
    {
        $config = new Config();

        return $config
            ->setRiskyAllowed(true)
            ->setRules($rules)
        ;
    }

    /**
     * @param string $header
     *
     * @return array
     */
    private static function createRules($header = '')
    {
        if ($header) {
            $header = trim(
                preg_replace(
                    ['!^/\*\*\n!', '! \*/!', '! \* ?!', '!%year%!', '!' . date('Y-Y') . '!'],
                    [null, null, null, date('Y'), date('Y')],
                    $header
                )
            );
        }

        $rules = include __DIR__ . '/../config/rules.php';

        if (!$header) {
            $rules['header_comment'] = false;
        }

        $fixerFactory = new FixerFactory();
        $fixerFactory->registerBuiltInFixers();

        /** @var string[] $availableFixers */
        $availableFixers = array_map(fn (FixerInterface $fixer) => $fixer->getName(), $fixerFactory->getFixers());

        foreach ($rules as $fixer => $rule) {
            if (false === \in_array($fixer, $availableFixers, true)) {
                unset($rules[$fixer]);
            }
        }

        return $rules;
    }
}
