<?php

$_CONFIG['Host'] = 'localhost';
$_CONFIG['User'] = 'root';
$_CONFIG['Pass'] = '';
$_CONFIG['Database'] ='Studies';


$mysqli = new mysqli($_CONFIG['Host'],$_CONFIG['User'],$_CONFIG['Pass'],$_CONFIG['Database']);

//$mssql_connect = new PDO('mssql:host='.$_CONFIG['Host'].';dbname='.$_CONFIG['Database'], $_CONFIG['User'], $_CONFIG['Pass']);
/* check connection */
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

if (!$mysqli->set_charset("utf8")) {
    printf("Error loading character set utf8: %s\n", $mysqli->error);
    exit();
}

?>