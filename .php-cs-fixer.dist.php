<?php

$finder = (new PhpCsFixer\Finder())
    ->in(dirs: [
                   __DIR__ . '/src',
                   __DIR__ . '/tests'
               ]);

return (new PhpCsFixer\Config())
    ->setRules([
                   '@Symfony' => true,
               ])
    ->setFinder($finder)
;
