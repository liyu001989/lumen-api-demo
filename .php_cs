<?php

$finder = Symfony\Component\Finder\Finder::create()
	->notPath('bootstrap/cache')
	->notPath('storage')
	->notPath('vendor')
    ->in(__DIR__)
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

$fixers = [
    'braces',
    'class_definition',
    'concat_without_spaces',
    'declare_equal_normalize',
    'elseif',
    'encoding',
    'function_declaration',
    'function_typehint_space',
    'hash_to_slash_comment',
    'heredoc_to_nowdoc',
    'include',
    'lowercase_cast',
    'lowercase_constants',
    'lowercase_keywords',
    'method_argument_space',
    'method_separation',
    'native_function_casing',
    'no_blank_lines_after_class_opening',
    'no_empty_phpdoc',
    'no_empty_statement',
    'no_trailing_whitespace_in_comment',
    'no_useless_return',
    'phpdoc_indent',
    'phpdoc_inline_tag',
    'phpdoc_no_access',
    'phpdoc_no_package',
    'phpdoc_scalar',
    'phpdoc_single_line_var_spacing',
    'phpdoc_summary',
    'phpdoc_to_comment',
    'phpdoc_trim',
    'phpdoc_type_to_var',
    'phpdoc_types',
    'phpdoc_var_without_name',
    'print_to_echo',
    'self_accessor',
    'short_array_syntax',
    'short_scalar_cast',
    'single_blank_line_before_namespace',
    'single_line_after_imports',
    'single_quote',
    'switch_case_semicolon_to_colon',
    'switch_case_space',
    'trim_array_spaces',
    'unalign_equals',
];

return Symfony\CS\Config\Config::create()
    ->level(Symfony\CS\FixerInterface::NONE_LEVEL)
    ->fixers($fixers)
    ->finder($finder)
    ->setUsingCache(true);
