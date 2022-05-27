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

use PhpCsFixer\RuleSet\RuleSet;

$ruleSet = new RuleSet([
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
]);

$rules = $ruleSet->getRules();

return array_merge($rules, [
    'align_double_arrow'     => true,
    'binary_operator_spaces' => [
        'operators' => [
            '='   => 'align',
            '=>'  => 'align_single_space_minimal',
            '===' => 'align_single_space_minimal',
        ],
    ],
    'blank_line_before_return'    => true,
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
    'header_comment' => [
        'header' => $header ?? '',
    ],
    'heredoc_indentation'               => false,
    'mb_str_functions'                  => true,
    'no_extra_consecutive_blank_lines'  => true,
    'no_short_echo_tag'                 => true,
    'no_unneeded_curly_braces'          => true,
    'phpdoc_inline_tag'                 => true,
    'phpdoc_no_empty_return'            => false,
    'phpdoc_no_package'                 => false,
    'simplified_null_return'            => true,
    'trailing_comma_in_multiline_array' => true,
]);
