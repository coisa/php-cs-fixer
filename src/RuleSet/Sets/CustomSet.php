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

namespace CoiSA\PhpCsFixer\RuleSet\Sets;

use PhpCsFixer\RuleSet\AbstractRuleSetDescription;

/**
 * Class CustomSet.
 *
 * @package CoiSA\PhpCsFixer\RuleSet\Sets
 */
final class CustomSet extends AbstractRuleSetDescription
{
    /**
     * @var string
     */
    public const NAME = '@CoiSA';

    /**
     * @var string File header
     */
    private string $header;

    /**
     * CustomSet constructor.
     */
    public function __construct(string $header = '')
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

        $this->header = $header;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * {@inheritDoc}
     */
    public function getRules(): array
    {
        $rules = [
            '@DoctrineAnnotation'       => true,
            '@PHP74Migration'           => true,
            '@PHP74Migration:risky'     => true,
            '@PHP80Migration'           => (float) PHP_VERSION >= 8,
            '@PHP80Migration:risky'     => (float) PHP_VERSION >= 8,
            '@PHP81Migration'           => (float) PHP_VERSION >= 8.1,
            '@PSR12'                    => true,
            '@PSR12:risky'              => true,
            '@PhpCsFixer'               => true,
            '@PhpCsFixer:risky'         => true,
            '@PHPUnit84Migration:risky' => true,
            'binary_operator_spaces'    => [
                'operators' => [
                    '='   => 'align',
                    '=>'  => 'align_single_space_minimal',
                    '===' => 'align_single_space_minimal',
                ],
            ],
            'class_attributes_separation' => true,
            'class_keyword_remove'        => false,
            'concat_space'                => [
                'spacing' => 'one',
            ],
            'date_time_immutable'              => false,
            'declare_parentheses'              => true,
            'general_phpdoc_annotation_remove' => [
                'annotations' => [
                    'author',
                ],
            ],
            'heredoc_indentation'      => false,
            'mb_str_functions'         => true,
            'no_unneeded_curly_braces' => true,
            'phpdoc_no_empty_return'   => false,
            'phpdoc_no_package'        => false,
            'simplified_null_return'   => true,
        ];

        if (!empty($this->header)) {
            $rules['header_comment'] = [
                'header'       => $this->header,
                'comment_type' => 'PHPDoc',
            ];
        }

        return $rules;
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(): string
    {
        return 'Rule set as used by the CoiSA\PhpCsFixer development team, highly opinionated.';
    }
}
