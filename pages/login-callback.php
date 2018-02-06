<?php
require_once '../vendors/OktaHandler.php';

$oktaHandler = new OktaHandler();?>
Welcome, <?=$oktaHandler->getUserName()?>