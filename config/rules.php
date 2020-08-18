<?php

/**
 * This file is part of coisa/php-cs-fixer.
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 *
 * @link      https://github.com/coisa/php-cs-fixer
 * @copyright Copyright (c) 2020 Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

return array(
    'align_multiline_comment' => true,
    'array_syntax'            => array(
        'syntax' => 'long'
    ),
    'binary_operator_spaces' => array(
        'align_double_arrow' => true,
        'align_equals'       => true
    ),
    'blank_line_after_namespace'   => true,
    'blank_line_after_opening_tag' => true,
    'blank_line_before_statement'  => array(
        'statements' => array(
            'break',
            'continue',
            'declare',
            'return',
            'throw',
            'try',
            'yield',
        ),
    ),
    'braces'                      => true,
    'cast_spaces'                 => true,
    'class_attributes_separation' => array(
        'elements' => array(
            'const',
            'method',
            'property'
        )
    ),
    'class_definition' => array(
        'multi_line_extends_each_single_line' => false,
        'single_item_single_line'             => false
    ),
    'class_keyword_remove'        => false,
    'combine_consecutive_issets'  => true,
    'combine_consecutive_unsets'  => true,
    'combine_nested_dirname'      => true,
    'compact_nullable_typehint'   => true,
    'concat_space'                => array(
        'spacing' => 'one'
    ),
    'date_time_immutable'         => false,
    'declare_equal_normalize'     => array(
        'space' => 'none'
    ),
    'dir_constant'                => true,
    'declare_strict_types'        => false,
    'elseif'                      => true,
    'encoding'                    => true,
    'ereg_to_preg'                => true,
    'error_suppression'           => array(
        'mute_deprecation_error'         => false,
        'noise_remaining_usages'         => false,
        'noise_remaining_usages_exclude' => array()
    ),
    'escape_implicit_backslashes' => array(
        'double_quoted'  => true,
        'heredoc_syntax' => true,
        'single_quoted'  => false,
    ),
    'explicit_indirect_variable'  => true,
    'explicit_string_variable'    => true,
    'final_internal_class'        => false,
    'fopen_flag_order'            => true,
    'fopen_flags'                 => array(
        'b_mode' => true,
    ),
    'full_opening_tag'             => true,
    'fully_qualified_strict_types' => true,
    'function_declaration'         => array(
        'closure_function_spacing' => 'one'
    ),
    'function_to_constant' => array(
        'functions' => array(
            'php_sapi_name',
            'phpversion',
            'pi'
        )
    ),
    'function_typehint_space'          => true,
    'general_phpdoc_annotation_remove' => array(
        'annotations' => array(
            'author'
        )
    ),
    'header_comment'               => array(
        'commentType' => 'PHPDoc',
        'header'      => $header,
        'location'    => 'after_open',
        'separate'    => 'both'
    ),
    'heredoc_indentation'                        => false,
    'heredoc_to_nowdoc'                          => true,
    'indentation_type'                           => true,
    'line_ending'                                => true,
    'list_syntax'                                => array(
        'syntax' => 'short'
    ),
    'lowercase_cast'                             => true,
    'lowercase_constants'                        => true,
    'lowercase_keywords'                         => true,
    'magic_constant_casing'                      => true,
    'method_argument_space'                      => array(
        'ensure_fully_multiline' => true
    ),
    'modernize_types_casting'                    => true,
    'native_function_casing'                     => true,
    'native_function_invocation'                 => true,
    'no_alias_functions'                         => true,
    'no_blank_lines_after_class_opening'         => true,
    'no_blank_lines_after_phpdoc'                => true,
    'no_closing_tag'                             => true,
    'no_empty_comment'                           => true,
    'no_empty_phpdoc'                            => true,
    'no_empty_statement'                         => true,
    'no_extra_consecutive_blank_lines'           => true,
    'no_homoglyph_names'                         => true,
    'no_leading_import_slash'                    => true,
    'no_leading_namespace_whitespace'            => true,
    'no_mixed_echo_print'                        => array(
        'use' => 'echo'
    ),
    'no_null_property_initialization'            => true,
    'no_short_bool_cast'                         => true,
    'no_short_echo_tag'                          => true,
    'no_singleline_whitespace_before_semicolons' => true,
    'no_spaces_after_function_name'              => true,
    'no_spaces_inside_parenthesis'               => true,
    'no_superfluous_elseif'                      => true,
    'no_trailing_comma_in_list_call'             => true,
    'no_trailing_comma_in_singleline_array'      => true,
    'no_trailing_whitespace'                     => true,
    'no_trailing_whitespace_in_comment'          => true,
    'no_unneeded_control_parentheses'            => true,
    'no_unneeded_curly_braces'                   => true,
    'no_unneeded_final_method'                   => true,
    'no_unreachable_default_argument_value'      => true,
    'no_unused_imports'                          => true,
    'no_useless_else'                            => true,
    'no_whitespace_before_comma_in_array'        => true,
    'no_whitespace_in_blank_line'                => true,
    'non_printable_character'                    => true,
    'normalize_index_brace'                      => true,
    'object_operator_without_whitespace'         => true,
    'ordered_class_elements'                     => array(
        'order' => array(
            'use_trait',
            'constant_public',
            'constant_protected',
            'constant_private',
            'property_public',
            'property_protected',
            'property_private',
            'construct',
            'destruct',
            'magic',
            'phpunit',
            'method_public',
            'method_protected',
            'method_private',
        ),
    ),
    'ordered_imports'                     => true,
    'phpdoc_add_missing_param_annotation' => true,
    'phpdoc_align'                        => true,
    'phpdoc_annotation_without_dot'       => true,
    'phpdoc_indent'                       => true,
    'phpdoc_no_access'                    => true,
    'phpdoc_no_empty_return'              => false,
    'phpdoc_no_package'                   => false,
    'phpdoc_order'                        => true,
    'phpdoc_return_self_reference'        => true,
    'phpdoc_scalar'                       => true,
    'phpdoc_separation'                   => true,
    'phpdoc_single_line_var_spacing'      => true,
    'phpdoc_to_comment'                   => true,
    'phpdoc_trim'                         => true,
    'phpdoc_types'                        => true,
    'phpdoc_types_order'                  => true,
    'phpdoc_var_without_name'             => true,
    'pow_to_exponentiation'               => true,
    'protected_to_private'                => true,
    'return_type_declaration'             => array(
        'space_before' => 'none'
    ),
    'self_accessor'                       => true,
    'short_scalar_cast'                   => true,
    'simplified_null_return'              => true,
    'single_blank_line_at_eof'            => true,
    'single_import_per_statement'         => true,
    'single_line_after_imports'           => true,
    'single_quote'                        => true,
    'standardize_not_equals'              => true,
    'ternary_to_null_coalescing'          => true,
    'trim_array_spaces'                   => true,
    'unary_operator_spaces'               => true,
    'visibility_required'                 => true,
    'void_return'                         => false,
    'whitespace_after_comma_in_array'     => true,
);