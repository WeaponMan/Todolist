<?php
session_start();
$_SESSION['javascript'] = false;
if(isset($_GET['redirect']))
    reload(str_replace (_PATH_, '', $_GET['redirect']));
else
    reload('');
