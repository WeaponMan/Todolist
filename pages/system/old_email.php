<?php

if(!isset($_GET['key'], $_GET['nick'])){
    $tmpl->renderBadLink();
    return;
}

$event = $db->query('SELECT user_id FROM events WHERE event_key = ' . $db->quote($_GET['key']) . ' AND event_type = ' . $db->quote(Event::EMAIL_CHANGE_OLD))->fetch();
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

$value = Event::getData($db, $_GET['key']);

if($value === false){
    reload('?fb=change_email_failed');
}

$db->beginTransaction();
try{
    Event::setComplete($db, $_GET['key']);
}catch(\Snabb\Database\Exception $e){
    $db->rollback();
    reload('?fb=change_email_failed');
}

$event_key = Event::newKey();

try{
    Event::add($db, $event_key, $user_ch['user_id'], Event::EMAIL_CHANGE_NEW, time() + (24 * 3600), $value);
}catch(\Snabb\Database\Exception $e){
    $db->rollback();
    reload('?fb=change_email_failed');
}

$html = '<html>
  <head></head>
  <body>
    <div>
      Vážený uživateli,<br><br>

      '.  format_string('pro dokončení změny e‑mailové adresy na %s na Vašem uživatelském účtě %s, přejděte na následující odkaz:', '<b>'.e_html($value).'</b>', '<b>'.e_html($user_ch['nick']).'</b>').'<br><br>

      <a href="'.e_html(_DOMAIN_._PATH_).'email/new?key='.e_html($event_key).'&nick='.  e_html($user_ch['nick']).'">'.e_html(_DOMAIN_._PATH_).'email/new?key='.e_html($event_key).'&nick='.  e_html($user_ch['nick']).'</a><br><br>
      S pozdravem,<br>
      Tým Todolist
    </div>
  </body>
</html>';
$alt = 'Vážený uživateli'."\r\n \r\n"

      .format_string('pro dokončení změny e‑mailové adresy na %s na Vašem uživatelském účtě %s, přejděte na následující odkaz:', $value, $user_ch['nick'])."\r\n \r\n".

      _DOMAIN_._PATH_.'email/new?key='.$event_key.'&nick='.$user_ch['nick']."\r\n \r\n".
      'S pozdravem,'."\r\n".
      'Tým Todolist';

require_once 'lib/send_mail.php';
if(send_mail($value, 'Druhá část potvrzení změny e‑mailu', $html, $alt)){
    $db->commit();
    reload('?fb=change_email_old_success');
}else{
    $db->rollback();
    reload('?fb=change_email_failed');
}


