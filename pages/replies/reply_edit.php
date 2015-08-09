<?php

if (!isset($_GET['id'])) {
    $tmpl->renderBadLink();
    return;
}

$task = $db->query('SELECT tasks.list_id, tasks.task_id, replies.text FROM tasks JOIN replies USING(task_id) WHERE replies.reply_id = ' . $db->quote($_GET['id']))->fetch();

if ($task === false) {
    $tmpl->renderBadLink();
    return;
}
if (!$user->isAppAdmin()) {
    if ($user->listOwnerOrAdmin($task['list_id']) === false and $user->listMember($task['list_id']) === false) {
        $tmpl->renderBadLink();
        return;
    }
}
$redirect = '';
if (isset($_GET['red'])) {
    switch ($_GET['red']) {
        case 'all_list':
            $redirect = '?fb=comment_edit_success';
            break;
        case 'task':
            $redirect = 'task?id=' . $task['task_id'].'&fb=comment_edit_success';
            break;
        case 'list':
        default:
            $redirect = 'list?id=' . $task['list_id'].'&fb=comment_edit_success';
            break;
    }
}

if (isset($_POST['text'])) {
    if (!preg_match('~^.{5,}$~Ds', $_POST['text'])) {
        $tmpl->addMessage('Zadejte text dlouhý nejmíň 5 znaků.', Template::MESSAGE_ERROR);
        $tmpl->assign(['text' => $_POST['text']]);
    } else {
        $db->beginTransaction();
        try{
            $db->update('replies', ['text' => $_POST['text']], 'reply_id = ' . $db->quote($_GET['id']));
            $db->insert('reply_edits',['user_id' => $user->user_id, 'reply_id' => $_GET['id'], 'reply_edit_date' => time()]);
            $db->commit();
            reload($redirect);
        }catch(\Snabb\Database\Exception $e){
            $db->rollback();
            $tmpl->addMessage('Komentář se nepodařilo upravit.', Template::MESSAGE_ERROR);
            $tmpl->assign(['text' => $_POST['text']]);
        }
    }
} else {
    $tmpl->assign(['text' => $task['text']]);
}

$tmpl->assign(['submit' => 'Upravit', 'pageHeading' => 'Úprava komentáře', 'current_menu_list' => (int) $task['list_id']]);
$tmpl->renderTop('Úprava komentáře', $user);
$tmpl->render('forms/reply.tpl');
$tmpl->renderBottom();
