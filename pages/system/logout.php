<?php

if (isset($_COOKIE[USER_COOKIE_NAME])) {
    setcookie(USER_COOKIE_NAME, $_COOKIE[USER_COOKIE_NAME], time() - 1);
    reload('');
}