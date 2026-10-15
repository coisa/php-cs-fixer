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

/** Extracts the opening block comment without executing the configuration. */
final class HeaderExtractor
{
    public function extractFromFile(string $path): string
    {
        $file = new \SplFileObject($path, 'rb');
        $size = $file->getSize();

        return $this->extract($size > 0 ? $file->fread($size) : '');
    }

    public function extract(string $source): string
    {
        foreach (token_get_all($source) as $token) {
            if (is_array($token) && in_array($token[0], [T_OPEN_TAG, T_WHITESPACE], true)) {
                continue;
            }

            if (is_array($token) && in_array($token[0], [T_COMMENT, T_DOC_COMMENT], true)
                && str_starts_with($token[1], '/*') && str_ends_with($token[1], '*/')) {
                return $token[1];
            }

            return '';
        }

        return '';
    }
}
