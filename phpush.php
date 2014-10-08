#!/usr/bin/php
<?php

include __DIR__ . '/autoload.php';

$actions = array(
    'push',
);
$short = '';
$short .= 'p:';
$short .= 'h:';
$short .= 'a:';
$long = array(
    
);

$args = getopt($short, $long);
$errors = array();

if (!isset($args['p'])) {
    $errors[] = "missing password";
}

if (!isset($args['h'])) {
    $errors[] = "missing url";
}

if (!isset($args['a'])) {
    $errors[] = "missing action";
} elseif (!Phpush_Action_Abstract::isValidAction($args['a'])) {
    $errors[] = "invalid action '{$args['a']}'";
} else {
    $action = Phpush_Action_Abstract::getAction($args['a']);
    $action->setArgs($args);
    $errors = $action->getParametersErrors($errors);
}




if (count($errors)) {
    echo "errors:\n";
    foreach ($errors as $error) {
        echo "\t" . $error . PHP_EOL;
    }
    
    show_usage();
    die();
}

echo $action->run($result) . PHP_EOL;

return $result;

die();

function show_usage() {
    $more = Phpush_Action_Abstract::getUsage();
    echo "usage\n\tphpush -p[password-phrase] -h[url] $more\n";
}