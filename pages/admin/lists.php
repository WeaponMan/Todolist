<?php
if(!$user->isAppAdmin()){
    $tmpl->renderBadLink();
    return;
}

$lists = $db->query('SELECT user_id, nick, name, list_id FROM users JOIN lists USING(user_id)')->fetchAll();
$tmpl->assign(['lists' => $lists]);
$tmpl->renderTop('Listy', $user);
$tmpl->render('views/admin_lists.tpl');
$tmpl->renderBottom();
