<?php

ini_set('xdebug.var_display_max_depth', '10');

include __DIR__.'/../src/bootstrap.php';

require_once __DIR__.'/../vendor/splitbrain/dokuwiki/_test/bootstrap.php';
require_once __DIR__.'/../syntax.php';

define('PROJECT_ROOT_DIR_PATH', dirname(__DIR__));

return $loader;
