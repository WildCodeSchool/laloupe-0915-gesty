<?php
/**
 * @see http://symfony.com/doc/current/cookbook/testing/bootstrap.html
 * 
 * The difference with the reference : we only got rid of the 'Env superglobal' as the only
 * environnement need for this application is "test".
 */
passthru(sprintf(
    'clear && php "%s/console" cache:clear --env=test --no-warmup',
    __DIR__
));

passthru(sprintf(
    'php "%s/console" doctrine:schema:update --force --env=test',
    __DIR__
));

require __DIR__.'/autoload.php';
