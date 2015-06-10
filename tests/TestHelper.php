<?php

define('LIB_PATH', realpath(__DIR__ . '/../lib/') . '/');

define('PHPTEST_PATH', realpath(__DIR__ . '/../lib/PHPTest/') . '/');
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . PHPTEST_PATH);

require_once PHPTEST_PATH . 'Util.php';
require_once PHPTEST_PATH . 'TestCase.php';

PHP_CodeCoverage_Filter::getInstance()->addDirectoryToWhitelist(
  LIB_PATH, '.php'
);

