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
                   'trailing_comma_in_multiline' => ['elements' => ['arrays', 'arguments', 'parameters']]
               ])
    ->setFinder($finder)
;
