<?php
session_start();
$_SESSION['javascript'] = true;
if(isset($_GET['redirect']))
    reload(str_replace (_PATH_, '', $_GET['redirect']));
else
    reload('');

