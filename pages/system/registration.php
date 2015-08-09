<?php

if (isset($_POST['nick'], $_POST['password'], $_POST['re-password'], $_POST['email'], $_POST['re-email'])) {
    $hlasky = [];
    if (!preg_match('~^[\\w\\.\\-]{3,32}$~Ds', $_POST['nick']))
        $hlasky[] = 'Zadejte jméno dlouhé 3-32 znaků. Povolené znaky jsou písmena abecedy, číslice, pomlčka, tečka a podtržítko.';
    else if ($db->query('SELECT nick FROM users WHERE nick = ' . $db->quote($_POST['nick']))->fetch() !== false)
        $hlasky[] = 'Toto užitelské jméno je už zabráno.';

    if (!preg_match('~^.{4,}$~Ds', $_POST['password']))
        $hlasky[] = 'Zadejte heslo dlouhé minimálně 6 znaků.';
    else if ($_POST['password'] !== $_POST['re-password'])
        $hlasky[] = 'Hesla se musí shodovat.';

    if (!preg_match('~^[\\w\\.\\-]+@[a-z\\d\\.\\-]+\\.[a-z]{2,4}$~Dsi', $_POST['email']) or !preg_match('~^.{0,60}$~Ds', $_POST['email']))
        $hlasky[] = 'Zadejte e‑mailovou adresu dlouhou maximálně 60 znaků a ve správném formátu, např. jan.novak@email.cz.';
    else {
        if ($_POST['email'] !== $_POST['re-email'])
            $hlasky[] = 'E-mailové adresy se musí shodovat.';

        if ($db->query('SELECT email FROM users WHERE email = ' . $db->quote($_POST['email']))->fetch() !== false)
            $hlasky[] = 'Tato emailová adresa je přiřazena již jinému uživateli.';
    }

    if ($hlasky) {
        $tmpl->assign(['nick' => $_POST['nick'], 'email' => $_POST['email']]);
        $tmpl->addMessage($hlasky, Template::MESSAGE_ERROR);
    } else {
        if (false !== $db->insert('users', \Snabb\Tools\Arrays::selective_keys($_POST, ['nick', 'email']) + ['password' => User::hashPassword($_POST['nick'], $_POST['password'])])) {
            $tmpl->addMessage('Registrace proběhla úšpěšně.', Template::MESSAGE_SUCCESS);
        } else {
            $tmpl->assign(['nick' => $_POST['nick'], 'email' => $_POST['email']]);
            $tmpl->addMessage('Registrace neproběhla úšpěšně.', Template::MESSAGE_ERROR);
        }
    }
}

$tmpl->renderTop('Registrace');
$tmpl->render('forms/registration.tpl');
$tmpl->renderBottom();
