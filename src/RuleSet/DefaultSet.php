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

namespace CoiSA\PhpCsFixer\RuleSet;

use PhpCsFixer\RuleSet\RuleSetDefinitionInterface;

/**
 * Class DefaultSet.
 *
 * @package CoiSA\PhpCsFixer\RuleSet
 */
final class DefaultSet implements RuleSetDefinitionInterface
{
    /**
     * @var string
     */
    public const NAME = '@CoiSA/default';

    /**
     * @var string File header
     */
    private string $header;

    /**
     * Normalizes an optional file header, resolving its year placeholder.
     *
     * @param string $header raw header text or a complete block comment
     */
    public function __construct(string $header = '')
    {
        $wrapped = trim($header);

        if ('' === $wrapped) {
            $this->header = '';

            return;
        }

        if (str_starts_with($wrapped, '/*') && str_ends_with($wrapped, '*/')
            && strpos($wrapped, '*/') === \strlen($wrapped) - 2) {
            $header = substr($wrapped, 2, -2);

            if (str_starts_with($header, '*')) {
                $header = substr($header, 1);
            }

            $header = trim(preg_replace('/^[\t ]*\*[\t ]?/m', '', $header) ?? '');
        }

        $year = date('Y');
        $header = str_replace(['%year%-%year%', $year . '-%year%', '%year%-' . $year], '%year%', $header);
        $header = str_replace('%year%', $year, $header);

        $header = preg_replace_callback(
            '/(\bcopyright[\t ]+(?:(?:\(c\)|©)[\t ]+)?)(\d{4})(?:[\t ]*(?:-|–)[\t ]*(\d{4}))?\b(?![\t ]*(?:-|–)[\t ]*\d)/i',
            static function (array $match) use ($year): string {
                if ((int) $match[2] > (int) $year) {
                    return $match[0];
                }

                return $match[1] . $match[2] . ($match[2] === $year ? '' : '-' . $year);
            },
            $header,
        ) ?? $header;

        $this->header = $header;
    }

    /**
     * Returns the custom preset name accepted by PHP CS Fixer.
     *
     * @return string the CoiSA rule-set identifier
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * Returns rules following the consumer's Composer PHP minimum and current PER.
     *
     * @return array<string, array<string, mixed>|bool> nested presets and explicit rules
     */
    public function getRules(): array
    {
        $rules = [
            '@PhpCsFixer'                      => true,
            '@PhpCsFixer:risky'                => true,
            '@auto'                           => true,
            '@autoPHPMigration:risky'          => true,

            // Preserve PER layout and its available 3.1 additions across presets.
            'no_unneeded_braces'               => true,
            'protected_to_private'             => true,
            'no_unneeded_control_parentheses'  => [
                'statements' => [
                    'break',
                    'continue',
                    'echo_print',
                    'negative_instanceof',
                    'others',
                    'return',
                    'yield',
                    'yield_from',
                ],
            ],
            'trailing_comma_in_multiline' => [
                'after_heredoc' => true,
                'elements' => ['arguments', 'array_destructuring', 'arrays', 'match', 'parameters'],
            ],

            'date_time_immutable'              => true,
            'no_unset_on_property'             => false,
            'no_trailing_whitespace_in_string' => false,
            'mb_str_functions'                 => false,

            'general_phpdoc_annotation_remove' => [
                'annotations' => [
                    'author',
                ],
            ],
            'phpdoc_no_empty_return' => false,
            'phpdoc_no_package'      => false,
            'simplified_null_return' => true,
        ];

        if ('' === $this->header) {
            return $rules;
        }

        $rules['header_comment'] = [
            'header'       => $this->header,
            'comment_type' => 'PHPDoc',
            'location'     => 'after_open',
        ];

        return $rules;
    }

    /**
     * Describes the intended CoiSA coding-style policy.
     *
     * @return string the human-readable preset description
     */
    public function getDescription(): string
    {
        return 'Rule set as used by the CoiSA\PhpCsFixer development team, highly opinionated.';
    }

    /**
     * Declares that the preset includes behavior-changing rules.
     *
     * @return bool true because the preset intentionally includes risky rules
     */
    public function isRisky(): bool
    {
        return true;
    }
}
