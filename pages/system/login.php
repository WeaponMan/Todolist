<?php

if (isset($_POST['nick'], $_POST['password'])) {
    $hlasky = [];
    if (!preg_match('~^[\\w\\.\\-]{3,32}$~Ds', $_POST['nick']) and (!preg_match('~^[\\w\\.\\-]+@[a-z\\d\\.\\-]+\\.[a-z]{2,4}$~Dsi', $_POST['nick']) or !preg_match('~^.{0,60}$~Ds', $_POST['nick'])))
        $hlasky[] = 'Zadejte jméno dlouhé 3-32 znaků. Povolené znaky jsou písmena abecedy, číslice, pomlčka, tečka a podtržítko. Nebo zadejte e‑mailovou adresu dlouhou maximálně 60 znaků a ve správném formátu, např. jan.novak@email.cz.';

    if (!preg_match('~^.{4,}$~Ds', $_POST['password']))
        $hlasky[] = 'Zadejte heslo dlouhé minimálně 6 znaků.';

    if ($hlasky) {
        $tmpl->assign(['nick' => $_POST['nick']]);
        $tmpl->addMessage($hlasky, Template::MESSAGE_ERROR);
    } else if (($preauth = $db->query('SELECT password,user_id ,nick, email FROM users WHERE (nick = ' . $db->quote($_POST['nick']) . ' OR email = ' . $db->quote($_POST['nick']) . ')')->fetch()) !== false) {
        if ($preauth['password'] === User::hashPassword($preauth['nick'], $_POST['password'])) {
            $db->exec('UPDATE users SET last_login = ' . time() . ' WHERE user_id =' . (int) $preauth['user_id']);
            if (setcookie('todolist', $preauth['password'] . sha1($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']), time() + 7200))
                reload('');
        }else {
            $tmpl->assign(['nick' => $_POST['nick']]);
            $tmpl->addMessage('Nesprávný email nebo jméno nebo heslo.', Template::MESSAGE_ERROR);
        }
    } else {
        $tmpl->assign(['nick' => $_POST['nick']]);
        $tmpl->addMessage('Nesprávný email nebo jméno nebo heslo.', Template::MESSAGE_ERROR);
    }
}

$tmpl->addCss('loginForm.css');
$tmpl->renderTop('Přihlášení');
$tmpl->render('forms/login.tpl');
$tmpl->renderBottom();
