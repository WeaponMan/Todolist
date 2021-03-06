<?php

if (!isset($_GET['id'])) {
    $tmpl->renderBadLink();
    return;
}

if (!$user->isAppAdmin()) {
    if (($admin = $user->listOwnerOrAdmin($_GET['id'])) === false and $user->listMember($_GET['id']) === false) {
        $tmpl->renderBadLink();
        return;
    }
} else {
    $admin = true;
}

$list = $db->query('SELECT name, user_id FROM lists WHERE list_id = ' . $db->quote($_GET['id']))->fetch();

if ($list === false) {
    $tmpl->renderBadLink();
    return;
}

$tasks = $db->query('SELECT editors.nick as editor, task_edits.task_edit_date, tasks.task_id, users.user_id, tasks.title, tasks.description, tasks.create_date, tasks.due_date, tasks.done_date, users.nick, tasks.priority FROM tasks '
        . 'JOIN users USING(user_id) '
        . 'LEFT OUTER JOIN task_edits ON (tasks.task_id = task_edits.task_id AND task_edits.task_edit_date = (SELECT MAX(task_edits.task_edit_date) FROM task_edits WHERE task_edits.task_id = tasks.task_id)) '
        . 'LEFT OUTER JOIN users editors ON editors.user_id = task_edits.user_id '
        . 'WHERE tasks.list_id = ' . $db->quote($_GET['id']) . ' ORDER BY create_date DESC, priority')->fetchAll();
if ($tasks) {
    $task_ids = [];
    foreach ($tasks as $task)
        $task_ids[] = (int) $task['task_id'];

    $count_comments_query = $db->query('SELECT replies.task_id, COUNT(replies.reply_id) "comments_count" FROM replies LEFT OUTER JOIN tasks USING(task_id) WHERE tasks.task_id IN(' . implode(',', $task_ids) . ') GROUP BY replies.task_id')->fetchAll();
    $count_comments = [];
    if ($count_comments_query) {
        foreach ($count_comments_query as $count_task_comments) {
            if ((int) $count_task_comments['comments_count'] > 0)
                $count_comments[(int) $count_task_comments['task_id']] = $count_task_comments['comments_count'];
        }
        $tmpl->assign(['count_comments' => $count_comments]);

        $comments_task_ids = array_keys($count_comments);
       
       $replies_query = $db->query('SELECT editors.nick AS editor, reply_edits.reply_edit_date, users.nick, users.user_id, replies.task_id, replies.reply_id, replies.text, replies.posted FROM replies '
        . 'JOIN users USING(user_id) '
        . 'LEFT OUTER JOIN reply_edits ON (replies.reply_id = reply_edits.reply_id AND reply_edits.reply_edit_date = (SELECT MAX(reply_edits.reply_edit_date) FROM reply_edits WHERE reply_edits.reply_id = replies.reply_id)) '
        . 'LEFT OUTER JOIN users editors ON editors.user_id = reply_edits.user_id '
        . 'WHERE replies.task_id IN(' . implode(',', $comments_task_ids) . ') '
        . 'ORDER BY replies.posted')->fetchAll();
        if ($replies_query) {
            $replies = [];
            foreach ($replies_query as $reply) {
                $replies[(int) $reply['task_id']][] = $reply;
            }

            foreach ($replies as &$task_replies) {
                $count = count($task_replies);
                if ($count > 2) {
                    while (count($task_replies) > 2)
                        array_shift($task_replies);
                }
            }
            $tmpl->assign(['comments' => $replies]);
            
        }
    }

    $task_assignments = $db->query('SELECT users.nick,tasks_assignment.task_id, users.user_id FROM users JOIN tasks_assignment USING(user_id) WHERE tasks_assignment.task_id IN(' . implode(',', $task_ids) . ')')->fetchAll();
    if ($task_assignments) {
        $assignments = [];
        foreach ($task_assignments as $assignment)
            $assignments[(int) $assignment['task_id']][] = ['nick' => $assignment['nick'], 'user_id' => $assignment['user_id']];
        $tmpl->assign(['tasks_assignment' => $assignments]);
    }

    $task_tags = $db->query('SELECT tags.tag, tags.tag_id, task_tags.task_id FROM task_tags JOIN tags USING(tag_id) WHERE task_id IN(' . implode(',', $task_ids) . ')')->fetchAll();
    if ($task_tags) {
        $tags = [];
        foreach ($task_tags as $tag)
            $tags[(int) $tag['task_id']][] = ['tag_id' => (int) $tag['tag_id'], 'tag' => $tag['tag']];
        $tmpl->assign(['task_tags' => $tags]);
    }
}

$tmpl->assign(['current_menu_list' => $_GET['id'],
    'user_id' => (int) $user->user_id,
    'admin' => $admin,
    'tasks' => $tasks,
    'owner' => ($list['user_id'] == $user->user_id)
]);
$tmpl->addCss('list.css');
$tmpl->renderTop($list['name'], $user);
$tmpl->render('views/list.tpl');
$tmpl->renderBottom();
