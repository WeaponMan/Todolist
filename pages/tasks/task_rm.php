<?php

if (!isset($_GET['id'])) {
    $tmpl->renderBadLink();
    return;
}

$task = $db->query('SELECT list_id, user_id FROM tasks WHERE task_id = ' . $db->quote($_GET['id']))->fetch();

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
            $redirect = 'task?id=' . (int) $_GET['id'];
            break;
        case 'list':
        default:
            $redirect = 'list?id=' . $task['list_id'];
            break;
    }
}

if (isset($_POST['yes'])) {
    if ($db->exec('DELETE FROM tasks WHERE task_id =' . $db->quote($_GET['id'])) !== false) {
        if($redirect === ''){
            reload('?fb=task_rm_success');
        }else{
            reload($redirect.'&fb=task_rm_success');
        }
    } else {
        if($redirect === ''){
            reload('?fb=task_rm_failed');
        }else{
            reload($redirect.'&fb=task_rm_failed');
        }
    }
} elseif (isset($_POST['no'])) {
    if ($redirect === '')
        reload('');
    else
        reload($redirect);
}

$tmpl->addCss('confirmForm.css');
$tmpl->renderTop('Odstranění úkolu', $user);
$tmpl->assign(['question' => 'Opravdu chcete tento úkol odstranit?']);
$tmpl->render('forms/confirm.tpl');
$tmpl->renderBottom();
