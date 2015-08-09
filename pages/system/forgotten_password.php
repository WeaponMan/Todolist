<?php
if (isset($_POST['nick'], $_POST['email'])) {
    $user_fp = $db->query('SELECT user_id FROM users WHERE nick = ' . $db->quote($_POST['nick']) . ' AND email = ' . $db->quote($_POST['email']))->fetch();
    if ($user_fp !== false) {
        $previous = $db->query('SELECT event_key FROM events WHERE event_type = ' . $db->quote(Event::PASSWORD_CHANGE) . ' AND event_complete = 0 AND event_expire > ' . $db->quote(time()))->fetch();
        if ($previous === false) {
            $event_key = Event::newKey();
            try {
                $db->beginTransaction();
                Event::add($db, $event_key, $user_fp['user_id'], Event::PASSWORD_CHANGE, time() + 3600 + 3600); // 2 hours
                require_once 'lib/send_mail.php';
                $html = '<html>
  <head></head>
  <body>
    <div>
      Vážený uživateli,<br><br>
      Požádal ste o zapomenuté heslo, které si mužete změnit <a href="' . _DOMAIN_ . _PATH_ . 'password/change?key=' . $event_key . '&nick=' . e_html($_POST['nick']) . '">tady</a>.<br>
      <br>    
      S pozdravem,<br>
      Tým Todolist
    </div>
  </body>
</html>';
                $alt = "Vážený uživateli,\r\n"
                        . "Požádal ste o zapomenuté heslo, které si mužete změnit na této adrese:\r\n"
                        . _DOMAIN_ . _PATH_ . 'password/change?key=' . $event_key . '&nick=' . $_POST['nick'] . "\r\n\r\n"
                        . "S pozdravem,\r\n Tým Todolist";
                if (send_mail($_POST['email'], 'Zapomenuté heslo', $html, $alt)) {
                    $db->commit();
                    $tmpl->addMessage('Adresa na změnu hesla odeslána na váš email.', Template::MESSAGE_SUCCESS);
                } else {
                    $db->rollback();
                    $tmpl->addMessage('Adresu na změnu hesla se nepodařilo odeslat na váš email.', Template::MESSAGE_ERROR);
                }
            } catch (\Snabb\Database\Exception $e) {
                $db->rollback();
                $tmpl->addMessage('Adresu na změnu hesla se nepodařilo odeslat na váš email.', Template::MESSAGE_ERROR);
            }
        } else {
            $tmpl->addMessage('O zapomenuté heslo ste již žádal. Prosím zkontrolujte si emailovou schránku.', Template::MESSAGE_ERROR);
        }
    } else {
        $tmpl->addMessage('Pro tuto kombinaci uživatelského jména a emailové adresy neexistuje žádný uživatel.', Template::MESSAGE_ERROR);
    }
}

$tmpl->renderTop('Zapomenuté heslo');
$tmpl->render('forms/forgotten_password.tpl');
$tmpl->renderBottom();
