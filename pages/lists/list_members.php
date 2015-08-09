<?php

if (!isset($_GET['id'])) {
    $tmpl->renderBadLink();
    return;
}

if ($user->listOwnerOrAdmin($_GET['id']) === false and !$user->isAppAdmin()) {
    $tmpl->renderBadLink();
    return;
}

$list = $db->query('SELECT user_id, name FROM lists WHERE list_id = ' . $db->quote($_GET['id']))->fetch();
$list_members = $db->query('SELECT member.user_id, member.nick AS user_name, added.nick AS added_user_name, list_users.member_from, list_users.list_admin_from FROM list_users JOIN users member ON list_users.user_id = member.user_id JOIN users added ON list_users.added_by = added.user_id WHERE list_users.list_id =' . $db->quote($_GET['id']))->fetchAll();
$tmpl->assign(['list_members' => $list_members,
    'current_menu_list' => $_GET['id'],
    'owner' => ($user->user_id === $list['user_id'] or $user->isAppAdmin())]);
$tmpl->renderTop('Členové listu ' . $list['name'], $user);
$tmpl->render('views/members.tpl');
$tmpl->renderBottom();
