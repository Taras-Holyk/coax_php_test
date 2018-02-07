<?php
require_once '../vendors/OktaHandler.php';

$oktaHandler = new OktaHandler();
?>
<html>
    <head>
        <title>Login with Okta</title>
        <link rel="stylesheet" href="../style.css">
    </head>
    <body>
        <header>
            <a href="/index.html">Home</a>
            <a href="/pages/okta-login.php">Login with Okta</a>
            <a href="/pages/parsing-form.html">Parse some string</a>
        </header>
        <main>
            <?php if ($username = $oktaHandler->getUserName()) { ?>
                Welcome, <?=$username?>
            <?php } else { ?>
                User was not found
            <?php } ?>
        </main>
    </body>
</html>
