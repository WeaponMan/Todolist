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

if ($dp_user === false and $dp_user['app_admin_from'] === null) {
    $tmpl->renderBadLink();
    return;
}

if($db->update('users', ['app_admin_from' => new \Snabb\Database\Literal('null')],'user_id = '.$db->quote($_GET['id'])) !== false){
    if($user->user_id === (int) $_GET['id'])
        reload('?fb=user_depose_success_self');
    else 
        reload('admin/users?fb=user_depose_success');
}else
    reload('admin/users?fb=user_depose_failed');
