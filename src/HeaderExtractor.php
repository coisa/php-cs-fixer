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

/**
 * Extracts the opening block comment without executing configuration code.
 *
 * @package CoiSA\PhpCsFixer
 */
final class HeaderExtractor
{
    /**
     * Prevents instantiation of this static utility.
     */
    private function __construct() {}

    /**
     * Reads a configuration file and extracts its opening block comment.
     *
     * @param string $path the configuration file to read
     *
     * @return string the opening block comment, or an empty string when absent
     *
     * @throws \RuntimeException if the file cannot be opened for reading
     * @throws \LogicException   if the path points to a directory
     */
    public static function extractFromFile(string $path): string
    {
        $file = new \SplFileObject($path, 'rb');
        $size = $file->getSize();

        if (0 === $size) {
            return '';
        }

        return self::extract($file->fread($size));
    }

    /**
     * Extracts only the first block comment before declarations or executable code.
     *
     * @param string $source the PHP source to inspect without executing it
     *
     * @return string the opening block comment, or an empty string when absent
     */
    public static function extract(string $source): string
    {
        foreach (token_get_all($source) as $token) {
            if (!\is_array($token)) {
                return '';
            }

            if (\in_array($token[0], [T_OPEN_TAG, T_WHITESPACE], true)) {
                continue;
            }

            if (!\in_array($token[0], [T_COMMENT, T_DOC_COMMENT], true)
                || !str_starts_with($token[1], '/*') || !str_ends_with($token[1], '*/')) {
                return '';
            }

            return $token[1];
        }

        return '';
    }
}
