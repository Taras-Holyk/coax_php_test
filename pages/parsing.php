<?php
require_once '../vendors/StringParser.php';

//$string = 'TestKey1:TestValue1 TestKey2:"a value" TestKey3:"a value with \""';
$string = trim(strip_tags($_REQUEST['text']));

$stringParser = new StringParser();
print_r($stringParser->getParsedString($string));