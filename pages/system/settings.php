<?php

if (isset($_POST['password'], $_POST['new_password'], $_POST['re_new_password'])) {
    $hlasky = [];
    $password_old = User::hashPassword($user->nick, $_POST['password'], $user->email);
    $user_pwd = $db->query('SELECT nick FROM users WHERE user_id = ' . $db->quote($user->user_id) . ' AND password = ' . $db->quote($password_old))->fetch();
    if ($user_pwd === false)
        $hlasky[] = 'Nesprávné současné heslo.';

    if (!preg_match('~^.{6,}$~Ds', $_POST['new_password']))
        $hlasky[] = 'Zadejte heslo dlouhé minimálně 6 znaků.';
    else if ($_POST['new_password'] !== $_POST['re_new_password'])
        $hlasky[] = 'Hesla se musí shodovat.';
    else {
        $password = User::hashPassword($user->nick, $_POST['new_password']);
        if ($password === $password_old)
            $hlasky[] = 'Současné heslo a nové heslo jsou totožné.';
    }

    if ($hlasky) {
        $tmpl->addMessage($hlasky, Template::MESSAGE_ERROR);
    } else {
        if ($db->update('users', ['password' => $password], 'user_id = ' . $db->quote($user->user_id)) !== false) {
            setcookie('todolist', $password . sha1($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']), time() + 7200);
            $tmpl->addMessage('Heslo změněno.', Template::MESSAGE_SUCCESS);
        } else
            $tmpl->addMessage('Heslo se nepodařilo změnit.', Template::MESSAGE_ERROR);
    }
}

if (isset($_POST['new_email'], $_POST['re_new_email'], $_POST['email_password'])) {
    $hlasky = [];
    $previous = $db->query('SELECT event_key FROM events WHERE event_type IN('.$db->quote(Event::EMAIL_CHANGE_OLD).','.Event::EMAIL_CHANGE_NEW.') AND user_id ='.$db->quote($user->user_id).' AND event_complete = 0 AND event_expire > '.$db->quote(time()))->fetch();
    if($previous === false){
        $user_cp = $db->query('SELECT password FROM users WHERE user_id = '.$db->quote($user->user_id))->fetch();
        
        if($user_cp['password'] !== User::hashPassword($user->nick, $_POST['email_password']))
              $hlasky[] = 'Nesprávné heslo.';
        
        if (!preg_match('~^[\\w\\.\\-]+@[a-z\\d\\.\\-]+\\.[a-z]{2,4}$~Dsi', $_POST['new_email']) or !preg_match('~^.{0,60}$~Ds', $_POST['new_email']))
            $hlasky[] = 'Zadejte e‑mailovou adresu dlouhou maximálně 60 znaků a ve správném formátu, např. jan.novak@email.cz.';
        else {
            if ($_POST['new_email'] !== $_POST['re_new_email'])
                $hlasky[] = 'E-mailové adresy se musí shodovat.';

            if ($db->query('SELECT email FROM users WHERE email = ' . $db->quote($_POST['new_email']))->fetch() !== false)
                $hlasky[] = 'Tato emailová adresa je přiřazena již jinému uživateli.';
        }
    }else{
        $hlasky[] = 'Již ste o změnu emailu žádal.';
    }
    if($hlasky){
        $tmpl->addMessage($hlasky, Template::MESSAGE_ERROR);
    }else{
        try{
            $db->beginTransaction();
            $event_key = Event::newKey();
            Event::add($db, $event_key, $user->user_id, Event::EMAIL_CHANGE_OLD, time() + (24 * 3600), $_POST['new_email']); // 1 day
            $html = '<html>
    <head></head>
    <body>
      <div>
        Vážený uživateli,<br><br>

        '.format_string('na Vašem uživatelském účtě %s byla změněna e‑mailová adresa na %s.', '<b>'.e_html($user->nick).'</b>', '<b>'.e_html($_POST['new_email'])).'</b><br>Pro potvrzení změny e‑mailové adresy přejděte na následující odkaz:<br><br>

        <a href="'.e_html(_DOMAIN_._PATH_).'email/old?key='.e_html($event_key).'&nick='.e_html($user->nick).'">'.e_html(_DOMAIN_._PATH_).'email/old?key='.e_html($event_key).'&nick='.e_html($user->nick).'</a><br><br>

        <b>Pro dokončení změny e‑mailové adresy, je nutné změnu potvrdit i na nové e‑mailové adrese.<br><br>
        S pozdravem,<br>
        Tým Todolist
      </div>
    </body>
  </html>';
            $alt = 'Vážený uživateli,\r\n \r\n'
                    . format_string('na Vašem uživatelském účtě %s byla změněna e‑mailová adresa na %s.', $user->nick, $_POST['new_email']).'\r\n'
                    . 'Pro potvrzení změny e‑mailové adresy přejděte na následující odkaz:\r\n \r\n'
                    . _DOMAIN_._PATH_.'email/old?key='.$event_key.'&nick='.$user->nick.'\r\n \r\n'
                    . 'S pozdravem, \r\n'
                    . 'Tým Todolist';
            require_once 'lib/send_mail.php';
            if(send_mail($user->email, 'První část potvrzení změny e‑mailové adresy', $html, $alt)){
                $tmpl->addMessage('Změnu e‑mailové adresy, je nutné potvrdit na současné i nové e‑mailové adrese, jinak bude změna ignorována.', Template::MESSAGE_SUCCESS);
                $db->commit();
            }else{
                $tmpl->addMessage('Nepodařilo se změnit email.',  Template::MESSAGE_ERROR);
                $db->rollback();
            }
        }  catch (\Snabb\Database\Exception $e){
            $db->rollback();
            $tmpl->addMessage('Nepodařilo se změnit email.',  Template::MESSAGE_ERROR);
        }
    }
}

$tmpl->renderTop('Nastavení', $user);
$tmpl->assign(['email' => $user->email]);
$tmpl->render('forms/settings.tpl');
$tmpl->renderBottom();
