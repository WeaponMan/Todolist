<?php

if (!isset($_GET['id'], $_GET['list_id'])) {
    $tmpl->renderBadLink();
    return;
}

$list_member = $db->query('SELECT user_id FROM list_users WHERE list_id = ' . $db->quote($_GET['list_id']) . ' AND user_id = ' . $db->quote($_GET['id']))->fetch();

if ($list_member === false) {
    $tmpl->renderBadLink();
    return;
}
if ($user->user_id !== (int) $_GET['id']) {
    if ($user->listOwnerOrAdmin($_GET['list_id']) === false) {
        $tmpl->renderBadLink();
        return;
    }
}

if (isset($_POST['yes'])) {
    if ($db->exec('DELETE FROM list_users WHERE list_id =' . $db->quote($_GET['list_id']) . ' AND user_id = ' . $db->quote($_GET['id'])) !== false) {
        if ((int) $user->user_id === (int) $list_member['user_id'])
            reload('?fb=list_member_rm_success_self');
        else
            reload('list/members?id=' . (int) $_GET['list_id'].'&fb=list_member_rm_success');
    } else
        $tmpl->addMessage('Vyloučení člena z listu se nezdařilo.', Template::MESSAGE_ERROR);
}
elseif (isset($_POST['no']))
    reload('list/members?id=' . (int) $_GET['list_id']);

$tmpl->addCss('confirmForm.css');
$tmpl->renderTop('Vyloučení člena z listu', $user);
$tmpl->assign(['question' => 'Opravdu chcete tohoto člena vyloučit z listu?']);
$tmpl->render('forms/confirm.tpl');
$tmpl->renderBottom();
