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

use PhpCsFixer\Finder as PhpCsFixerFinder;

/**
 * Class PhpCsFixer.
 *
 * @package CoiSA\PhpCsFixer
 */
final class Finder extends PhpCsFixerFinder
{
    /**
     * Finder constructor.
     */
    public function __construct(array $paths = [])
    {
        parent::__construct();

        $this
            ->ignoreDotFiles(false)
            ->ignoreVCSIgnored(true)
        ;

        foreach ($paths as $path) {
            if (!file_exists($path)) {
                continue;
            }

            if (is_file($path)) {
                $this->append([$path]);

                continue;
            }

            if ('!' === mb_substr($path, 0, 1)) {
                $this->exclude(mb_substr($path, 1));

                continue;
            }

            $this->in($path);
        }
    }
}
