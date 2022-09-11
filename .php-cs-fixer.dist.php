<?php

$finder = (new PhpCsFixer\Finder())
    ->in(dirs: [
                   __DIR__ . '/src',
                   __DIR__ . '/tests'
               ]);

return (new PhpCsFixer\Config())
    ->setRules([
                   '@Symfony' => true,
                   'concat_space' => ['spacing' => 'one'],
                   'final_class' => true,
                   'trailing_comma_in_multiline' => ['elements' => ['arrays', 'arguments', 'parameters']],
                   'array_indentation' => true,
                   'multiline_whitespace_before_semicolons' => ['strategy' => 'new_line_for_chained_calls'],
                   'declare_strict_types' => true,
                   'class_definition' => ['space_before_parenthesis' => true],
               ])
    ->setFinder($finder)
;
