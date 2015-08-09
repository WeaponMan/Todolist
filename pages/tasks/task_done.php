<?php

if (!isset($_GET['id'])) {
    $tmpl->renderBadLink();
    return;
}

$task = $db->query('SELECT list_id, user_id, done_date FROM tasks WHERE task_id = ' . $db->quote($_GET['id']))->fetch();

if ($task === false) {
    $tmpl->renderBadLink();
    return;
}

if ($task['done_date'] !== null) {
    $tmpl->renderBadLink();
    return;
}


if ($user->listOwnerOrAdmin($task['list_id']) === false and $user->listMember($task['list_id']) === false) {
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
            $redirect = 'list?id=' . $task['list_id'];
            break;
    }
}
$append = '';
if(false !== $db->update('tasks', ['done_date' => time()], 'list_id = ' . $task['list_id'] . ' AND task_id =' . $_GET['id'])){
    $append = 'fb=task_done_success';
}else{
    $append = 'fb=task_done_failed';
}
if ($redirect === '')
    reload('?'.$append);
else
    reload($redirect.'&'.$append);

