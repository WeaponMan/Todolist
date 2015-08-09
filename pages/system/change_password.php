<?php

if (!isset($_GET['key'], $_GET['nick'])) {
    $tmpl->renderBadLink();
    return;
}

$event = $db->query('SELECT user_id FROM events WHERE event_key = ' . $db->quote($_GET['key']) . ' AND event_type = ' . $db->quote(Event::PASSWORD_CHANGE))->fetch();
if ($event === false) {
    $tmpl->renderBadLink();
    return;
}

if (!Event::isActive($db, $_GET['key'])) {
    $tmpl->renderBadLink();
    return;
}

$user_ch = $db->query('SELECT user_id, nick FROM users WHERE nick = ' . $db->quote($_GET['nick']))->fetch();
if ($user_ch === false) {
    $tmpl->renderBadLink();
    return;
}

if ((int) $event['user_id'] !== (int) $user_ch['user_id']) {
    $tmpl->renderBadLink();
    return;
}

if (isset($_POST['password'], $_POST['re_password'])) {
    $hlasky = [];
    if (!preg_match('~^.{6,}$~Ds', $_POST['password']))
        $hlasky[] = 'Zadejte heslo dlouhé minimálně 6 znaků.';
    else if ($_POST['password'] !== $_POST['re_password'])
        $hlasky[] = 'Hesla se musí shodovat.';

    if ($hlasky) {
        $tmpl->addMessage($hlasky, Template::MESSAGE_ERROR);
    } else {
        $password = User::hashPassword($user_ch['nick'], $_POST['password']);
        if ($db->update('users', ['password' => $password], 'user_id = ' . $db->quote($event['user_id'])) !== false){
            Event::setComplete($db, $_GET['key']);
            reload('?fb=change_password_success');
        }
        else
            $tmpl->addMessage('Heslo se nepodařilo změnit.', Template::MESSAGE_ERROR);
    }
}

$tmpl->renderTop('Změna hesla');
$tmpl->render('forms/change_password.tpl');
$tmpl->renderBottom();
