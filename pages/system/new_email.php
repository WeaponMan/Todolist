<?php

if(!isset($_GET['key'], $_GET['nick'])){
    $tmpl->renderBadLink();
    return;
}

$event = $db->query('SELECT user_id FROM events WHERE event_key = ' . $db->quote($_GET['key']) . ' AND event_type = ' . $db->quote(Event::EMAIL_CHANGE_NEW))->fetch();
if ($event === false) {
    $tmpl->renderBadLink();
    return;
}

if (!Event::isActive($db, $_GET['key'])) {
    $tmpl->renderBadLink();
    return;
}

$user_ch = $db->query('SELECT user_id, nick, email FROM users WHERE nick = ' . $db->quote($_GET['nick']))->fetch();
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
    $db->update('users', ['email' => $value], 'user_id = '.$db->quote($user_ch['user_id']));
    $db->commit();
    reload('?fb=change_email_new_success');
}catch(\Snabb\Database\Exception $e){
    $db->rollback();
    reload('?fb=change_email_failed');
}