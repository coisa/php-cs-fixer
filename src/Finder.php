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

use PhpCsFixer\Finder as PhpCsFixerFinder;

/**
 * Class PhpCsFixer.
 *
 * @package CoiSA\PhpCsFixer
 */
final class Finder extends PhpCsFixerFinder
{
    /**
     * Finds owned files while honoring directory exclusions before existence checks.
     *
     * @param list<string> $paths files, directories and !directory exclusions relative to a root
     */
    public function __construct(array $paths = [])
    {
        parent::__construct();

        $this
            ->ignoreDotFiles(false)
            ->ignoreVCSIgnored(true)
        ;

        foreach ($paths as $path) {
            if (str_starts_with($path, '!')) {
                $this->exclude(substr($path, 1));

                continue;
            }

            if (!file_exists($path)) {
                continue;
            }

            if (is_file($path)) {
                $this->append([$path]);

                continue;
            }

            $this->in($path);
        }
    }
}
