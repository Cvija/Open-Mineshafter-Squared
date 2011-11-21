<?php

require_once 'scripts/config.php';
require_once 'scripts/functions.php';

echo getVersion($_GET['name']);

if($_GET['name'] == "client"){
    echo checkForGameUpdate($_GET['build']);
}
?>