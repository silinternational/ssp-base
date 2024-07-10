<?php

use SimpleSAML\Session;

ini_set('display_errors', 1);

$sessionType = 'sildisco:authentication';
$sessionKey = 'beta_tester';

$session = Session::getSessionFromRequest();
$session->setData($sessionType, $sessionKey, 1, Session::DATA_TIMEOUT_SESSION_END);

echo "<h1>Start Beta Testing</h1>";
echo "<p>You have been given a cookie to allow you to test beta-enabled IDPs.</p>";
echo "<p>To remove the cookie, just close your browser.</p>";
