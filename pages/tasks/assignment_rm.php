<?php

if (!isset($_GET['id'], $_GET['task_id'])) {
    $tmpl->renderBadLink();
    return;
}

$list = $db->query('SELECT list_id, done_date FROM tasks WHERE task_id = ' . $db->quote($_GET['task_id']))->fetch();
if ($list === false) {
    $tmpl->renderBadLink();
    return;
}

if ($list['done_date'] !== null) {
    $tmpl->renderBadLink();
    return;
}

if ($user->listOwnerOrAdmin($list['list_id']) === false and $user->listMember($list['list_id']) === false) {
    $tmpl->renderBadLink();
    return;
}

$task_assignment = $db->query('SELECT user_id FROM tasks_assignment WHERE task_id = ' . $db->quote($_GET['task_id']) . ' and user_id = ' . $db->quote($_GET['id']))->fetch();
if ($task_assignment === false) {
    $tmpl->renderBadLink();
    return;
}

$redirect = '';
if (isset($_GET['red'])) {
    switch ($_GET['red']) {
        case 'all_list':
            $redirect = '';
            break;
        case 'task':
            $redirect = 'task?id=' . (int)$_GET['id'];
            break;
        case 'list':
        default:
            $redirect = 'list?id=' . $list['list_id'];
            break;
    }
}

$append = '';
if(false !== $db->exec('DELETE FROM tasks_assignment WHERE task_id = ' . $db->quote($_GET['task_id']) . ' and user_id = ' . $db->quote($_GET['id']))){
    $append = 'fb=task_assignment_rm_success';
}else{
    $append = 'fb=task_assignment_rm_failed';
}

if ($redirect === '')
    reload('?'.$append);
else
    reload($redirect.'&'.$append);

