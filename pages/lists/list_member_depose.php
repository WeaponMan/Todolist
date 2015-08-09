<?php

if (!isset($_GET['id'], $_GET['list_id'])) {
    $tmpl->renderBadLink();
    return;
}

if ($user->listOwnerOrAdmin($_GET['list_id']) === false) {
    $tmpl->renderBadLink();
    return;
}

$list_member = $db->query('SELECT user_id, list_admin_from FROM list_users WHERE list_id = ' . $db->quote($_GET['list_id']) . ' AND user_id = ' . $db->quote($_GET['id']))->fetch();

if ($list_member === false) {
    $tmpl->renderBadLink();
    return;
}

if ($list_member['list_admin_from'] === null) {
    $tmpl->renderBadLink();
    return;
}

if(false !== $db->exec('UPDATE list_users SET list_admin_from = null WHERE list_id = ' . $db->quote($_GET['list_id']) . ' AND user_id = ' . $db->quote($_GET['id']))){
    if($user->user_id === (int)$_GET['id'])
        reload('list?id=' . (int) $_GET['list_id'].'&fb=member_depose_success_self');
    else
        reload('list/members?id=' . (int) $_GET['list_id'].'&fb=member_depose_success');
}else{
    reload('list/members?id=' . (int) $_GET['list_id'].'&fb=member_depose_failed');
}