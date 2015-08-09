<?php
if (!isset($_GET['id'])) {
    $tmpl->renderBadLink();
    return;
}

if(!$user->isAppAdmin()){
    $tmpl->renderBadLink();
    return;
}

$dp_user = $db->query('SELECT user_id, app_admin_from FROM users WHERE user_id = ' . $db->quote($_GET['id']))->fetch();

if ($dp_user === false and $dp_user['app_admin_from'] !== null) {
    $tmpl->renderBadLink();
    return;
}

if($db->update('users', ['app_admin_from' => time()],'user_id = '.$db->quote($_GET['id'])) !== false)
    reload('admin/users?fb=user_promote_success');
else
    reload('admin/users?fb=user_promote_failed');
