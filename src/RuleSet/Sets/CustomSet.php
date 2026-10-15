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

/**
 * This file is part of coisa/php-cs-fixer.
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 *
 * @see      https://github.com/coisa/php-cs-fixer
 *
 * @copyright Copyright (c) 2020-2026 Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

namespace CoiSA\PhpCsFixer\RuleSet\Sets;

use PhpCsFixer\RuleSet\RuleSetDefinitionInterface;

/**
 * Class CustomSet.
 *
 * @package CoiSA\PhpCsFixer\RuleSet\Sets
 */
final class CustomSet implements RuleSetDefinitionInterface
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
     * @param string $header header text or a complete PHPDoc block
     */
    public function __construct(string $header = '')
    {
        $header       = preg_replace('!\A/\*\*?|\*/\z!', '', trim($header)) ?? '';
        $header       = preg_replace('/^[\t ]*\*[\t ]?/m', '', $header) ?? '';
        $year         = date('Y');
        $header       = str_replace('%year%', $year, $header);
        $this->header = trim(str_replace($year . '-' . $year, $year, $header));
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
     * Returns rules targeting the fixed PHP 8.3 minimum on every host runtime.
     *
     * @return array<string, array<string, mixed>|bool> nested presets and explicit rules
     */
    public function getRules(): array
    {
        $rules = [
            '@DoctrineAnnotation'        => true,
            '@PHP8x3Migration'           => true,
            '@PHP8x3Migration:risky'     => true,
            '@PhpCsFixer'                => true,
            '@PhpCsFixer:risky'          => true,
            '@PER-CS3x0'                 => true,
            '@PER-CS3x0:risky'           => true,
            'date_time_immutable'              => false,
            'general_phpdoc_annotation_remove' => [
                'annotations' => [
                    'author',
                ],
            ],
            'mb_str_functions'       => true,
            'phpdoc_no_empty_return' => false,
            'phpdoc_no_package'      => false,
            'simplified_null_return' => true,
        ];

        if ('' !== $this->header) {
            $rules['header_comment'] = [
                'header'       => $this->header,
                'comment_type' => 'PHPDoc',
                'location'     => 'after_open',
            ];
        }

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
     * Declares that the preserved preset includes behavior-changing rules.
     *
     * @return bool true because the preset intentionally includes risky rules
     */
    public function isRisky(): bool
    {
        return true;
    }
}
