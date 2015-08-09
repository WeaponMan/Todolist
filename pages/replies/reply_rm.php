<?php

if (!isset($_GET['id'])) {
    $tmpl->renderBadLink();
    return;
}

$task = $db->query('SELECT tasks.list_id, replies.user_id, replies.task_id FROM replies JOIN tasks USING(task_id) WHERE replies.reply_id = ' . $db->quote($_GET['id']))->fetch();

if ($task === false) {
    $tmpl->renderBadLink();
    return;
}
if (!$user->isAppAdmin()) {
    if (((int) $task['user_id']) !== $user->user_id and $user->listOwnerOrAdmin($task['list_id']) === false) {
        $tmpl->renderBadLink();
        return;
    }
}
$redirect = '';
if (isset($_GET['red'])) {
    switch ($_GET['red']) {
        case 'all_list':
            $redirect = '';
            break;
        case 'task':
            $redirect = 'task?id=' . $task['task_id'];
            break;
        case 'list':
        default:
            $redirect = 'list?id=' . $task['list_id'];
            break;
    }
}

$append = '';
if(false !== $db->exec('DELETE FROM replies WHERE reply_id = ' . $db->quote($_GET['id']))){
    $append = 'fb=comment_rm_success';
}else{
    $append = 'fb=comment_rm_failed';
}

if($redirect === '')
    reload('?'.$append);
else
    reload ($redirect.'&'.$append);

