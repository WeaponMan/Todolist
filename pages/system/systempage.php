<?php

$created_by_current_user = $db->query('SELECT list_id, name FROM lists WHERE user_id = ' . $db->quote($user->user_id))->fetchAll();
$lists = $db->query('SELECT lists.list_id, list_users.list_admin_from, lists.name FROM list_users JOIN lists ON lists.list_id = list_users.list_id WHERE list_users.user_id = ' . $db->quote($user->user_id))->fetchAll();

$display_lists = [];

foreach ($lists as $value) {
    $display_lists[(int) $value['list_id']]['admin'] = ($value['list_admin_from'] != null);
    $display_lists[(int) $value['list_id']]['name'] = $value['name'];
}

foreach ($created_by_current_user as $value) {
    $display_lists[(int) $value['list_id']]['admin'] = true;
    $display_lists[(int) $value['list_id']]['name'] = $value['name'];
}

if ($display_lists) {
    $all_tasks = $db->query('SELECT tasks.task_id, tasks.list_id, users.nick, users.user_id, tasks.title, tasks.description, tasks.priority, tasks.create_date, tasks.due_date, tasks.done_date FROM tasks JOIN users ON tasks.user_id = users.user_id  WHERE tasks.list_id IN(' . implode(',', array_keys($display_lists)) . ') ORDER BY create_date DESC, priority')->fetchAll();
    if ($all_tasks) {
        $tmpl->assign(['tasks' => $all_tasks]);
        $task_ids = [];
        foreach ($all_tasks as $task)
            $task_ids[] = (int) $task['task_id'];
        $task_assignments = $db->query('SELECT users.nick,tasks_assignment.task_id, users.user_id FROM users JOIN tasks_assignment ON users.user_id = tasks_assignment.user_id WHERE tasks_assignment.task_id IN(' . implode(',', $task_ids) . ')')->fetchAll();
        $task_tags = $db->query('SELECT tags.tag, tags.tag_id, task_tags.task_id FROM task_tags JOIN tags ON task_tags.tag_id = tags.tag_id WHERE task_id IN(' . implode(',', $task_ids) . ')')->fetchAll();
        if ($task_assignments) {
            $assignments = [];
            foreach ($task_assignments as $assignment)
                $assignments[(int) $assignment['task_id']][] = ['nick' => $assignment['nick'], 'user_id' => $assignment['user_id']];
            $tmpl->assign(['tasks_assignment' => $assignments]);
        }

        if ($task_tags) {
            $tags = [];
            foreach ($task_tags as $tag)
                $tags[(int) $tag['task_id']][] = ['tag_id' => (int) $tag['tag_id'], 'tag' => $tag['tag']];
            $tmpl->assign(['task_tags' => $tags]);
        }

        $count_comments_query = $db->query('SELECT replies.task_id, COUNT(replies.reply_id) "comments_count" FROM replies LEFT OUTER JOIN tasks USING(task_id) WHERE tasks.task_id IN(' . implode(',', $task_ids) . ') GROUP BY replies.task_id')->fetchAll();
        $count_comments = [];
        if ($count_comments_query) {
            foreach ($count_comments_query as $count_task_comments) {
                if ((int) $count_task_comments['comments_count'] > 0)
                    $count_comments[(int) $count_task_comments['task_id']] = $count_task_comments['comments_count'];
            }
            $tmpl->assign(['count_comments' => $count_comments]);
        }
    }
}
$tmpl->addCss('list.css');
$tmpl->renderTop('Hlavní stránka', $user);
$tmpl->assign(['display_lists' => $display_lists, 'user_id' => (int) $user->user_id]);
$tmpl->render('views/all_lists.tpl');
$tmpl->renderBottom();
