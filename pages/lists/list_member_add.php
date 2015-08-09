<?php

if (!isset($_GET['id'])) {
    $tmpl->renderBadLink();
    return;
}

if ($user->listOwnerOrAdmin($_GET['id']) === false) {
    $tmpl->renderBadLink();
    return;
}

$list = $db->query('SELECT list_id FROM lists WHERE list_id = '.$db->quote($_GET['id']))->fetch();
if($list === false){
    $tmpl->renderBadLink();
    return;   
}

if (isset($_POST['name'])) {
    $member = $db->query('SELECT user_id FROM users WHERE (nick = ' . $db->quote($_POST['name']) . ' OR email = ' . $db->quote($_POST['name']) . ')')->fetch();

    $hlasky = [];

    if ($member === false)
        $hlasky[] = 'Tento uživatel neexistuje.';
    else if ((int) $member['user_id'] === $user->user_id)
        $hlasky[] = 'Nemůžete přidat sami sebe.';
    else if ($db->query('SELECT user_id FROM list_users WHERE user_id = ' . $db->quote($member['user_id']) . ' and list_id = ' . $db->quote($_GET['id']))->fetch() !== false)
        $hlasky[] = 'Tento uživatel je již členem tohoto listu.';

    if ($hlasky) {
        $tmpl->assign(['name' => $_POST['name']]);
        $tmpl->addMessage($hlasky, Template::MESSAGE_ERROR);
    } else {
        if ($db->insert('list_users', ['list_id' => $_GET['id'], 'user_id' => $member['user_id'], 'added_by' => $user->user_id, 'member_from' => time(), 'list_admin_from' => (isset($_POST['admin']) ? time() : new \Snabb\Database\Literal('null'))]) !== false) {
            reload('list?id=' . (int) $_GET['id'].'&fb=list_member_add_success');
        } else {
            $tmpl->addMessage('Přidání uživatele do listu se nezdařilo.', Template::MESSAGE_ERROR);
            $tmpl->assign(['name' => $_POST['name']]);
        }
    }
}
$tmpl->assign(['current_menu_list' => $_GET['id']]);
$tmpl->renderTop('Přidání uživatele do listu', $user);
$tmpl->render('forms/list_member_add.tpl');
$tmpl->renderBottom();
