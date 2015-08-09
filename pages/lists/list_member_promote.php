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

if ($list_member['list_admin_from'] !== null) {
    $tmpl->renderBadLink();
    return;
}

if (false !== $db->exec('UPDATE list_users SET list_admin_from = ' . $db->quote(time()) . ' WHERE list_id = ' . $db->quote($_GET['list_id']) . ' AND user_id = ' . $db->quote($_GET['id']))) {
    reload('list/members?id=' . (int) $_GET['list_id'] . '&fb=member_promote_success');
} else {
    reload('list/members?id=' . (int) $_GET['list_id'] . '&fb=member_promote_failed');
}