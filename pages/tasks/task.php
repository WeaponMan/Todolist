<?php

if (!isset($_GET['id'])) {
    $tmpl->renderBadLink();
    return;
}

$task = $db->query('SELECT tasks.task_id, tasks.list_id, tasks.user_id, tasks.description, tasks.title, users.nick, tasks.priority, tasks.create_date, tasks.due_date, tasks.done_date FROM tasks JOIN users USING(user_id) WHERE task_id = ' . $db->quote($_GET['id']))->fetch();

if ($task === false) {
    $tmpl->renderBadLink();
    return;
}

if (!$user->isAppAdmin()) {
    if (($admin = $user->listOwnerOrAdmin($task['list_id'])) === false and $user->listMember($task['list_id']) === false) {
        $tmpl->renderBadLink();
        return;
    }
} else {
    $admin = true;
}

$task_assignments = $db->query('SELECT users.nick, users.user_id FROM users JOIN tasks_assignment USING(user_id) WHERE tasks_assignment.task_id = ' . $db->quote($_GET['id']))->fetchAll();
if ($task_assignments) {
    $assignments = [];
    foreach ($task_assignments as $assignment)
        $assignments[$assignment['user_id']] = $assignment['nick'];
    $tmpl->assign(['tasks_assignment' => $assignments]);
}

$task_tags = $db->query('SELECT tags.tag, tags.tag_id FROM task_tags JOIN tags USING(tag_id) WHERE task_tags.task_id = ' . $db->quote($_GET['id']))->fetchAll();

if ($task_tags) {
    $tags = [];
    foreach ($task_tags as $tag)
        $tags[(int) $tag['tag_id']] = $tag['tag'];
    $tmpl->assign(['task_tags' => $tags]);
}

$replies_query = $db->query('SELECT editors.nick AS editor, reply_edits.reply_edit_date, users.nick, users.user_id, replies.reply_id, replies.text, replies.posted FROM replies '
        . 'JOIN users USING(user_id) '
        . 'LEFT OUTER JOIN reply_edits ON (replies.reply_id = reply_edits.reply_id AND reply_edits.reply_edit_date = (SELECT MAX(reply_edits.reply_edit_date) FROM reply_edits WHERE reply_edits.reply_id = replies.reply_id)) '
        . 'LEFT OUTER JOIN users editors ON editors.user_id = reply_edits.user_id '
        . 'WHERE replies.task_id = ' . $db->quote($_GET['id']) . '  '
        . 'ORDER BY replies.posted')->fetchAll();
if ($replies_query) {
    dump($replies_query);
    $tmpl->assign(['comments' => $replies_query]);
}

$tmpl->addCss('list.css');
$tmpl->assign(['admin' => $admin, 'task' => $task, 'current_user_id' => $user->user_id, 'current_menu_list' => $task['list_id']]);
$tmpl->renderTop('Úkol '.$task['title'], $user);
$tmpl->render('views/task.tpl');
$tmpl->renderBottom();
