<?php

if (!isset($_GET['id'])) {
    $tmpl->renderBadLink();
    return;
}

$list = $db->query('SELECT list_id, done_date FROM tasks WHERE task_id = ' . $db->quote($_GET['id']))->fetch();
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

$task_assignment = $db->query('SELECT user_id FROM tasks_assignment WHERE task_id = ' . $db->quote($_GET['id']) . ' and user_id = ' . (int) $user->user_id)->fetch();
if ($task_assignment !== false) {
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
if(false !== $db->insert('tasks_assignment', ['task_id' => $_GET['id'], 'user_id' => $user->user_id, 'assign_date' => time()])){
    $append = 'fb=task_assignment_add_success';
}else{
    $append = 'fb=task_assignment_add_failed';
}
if ($redirect === '')
    reload('?'.$append);
else
    reload($redirect.'&'.$append);
