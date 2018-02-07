<?php
require_once '../vendors/StringParser.php';

$string = trim(strip_tags($_REQUEST['text']));
$stringParser = new StringParser();
?>
<html>
    <head>
        <title>Parse some string</title>
        <link rel="stylesheet" href="../style.css">
    </head>
    <body>
        <header>
            <a href="/index.html">Home</a>
            <a href="/pages/okta-login.php">Login with Okta</a>
            <a href="/pages/parsing-form.html">Parse some string</a>
        </header>
        <main>
            <?php print_r($stringParser->getParsedString($string))?>
        </main>
    </body>
</html>