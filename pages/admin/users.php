<?php
if(!$user->isAppAdmin()){
    $tmpl->renderBadLink();
    return;
}

$users = $db->query('SELECT user_id, nick, email, last_login, app_admin_from FROM users');
$tmpl->assign(['users' => $users]);
$tmpl->renderTop('Uživatelé', $user);
$tmpl->render('views/users.tpl');
$tmpl->renderBottom();
