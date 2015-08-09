<?php

if (!isset($_GET['id'])) {
    $tmpl->renderBadLink();
    return;
}

$task = $db->query('SELECT list_id, user_id, title, description, due_date, priority, done_date FROM tasks WHERE task_id = ' . $db->quote($_GET['id']))->fetch();

if ($task === false) {
    $tmpl->renderBadLink();
    return;
}

if ($task['done_date'] !== null) {
    $tmpl->renderBadLink();
    return;
}

$task_assigments = $db->query('SELECT user_id FROM tasks_assignment WHERE task_id = ' . $db->quote($_GET['id']))->fetchAll(\Snabb\Database\Connection::FETCH_COLUMN, 0);
$task_tags = $db->query('SELECT tag FROM tags JOIN task_tags USING(tag_id) WHERE task_id = ' . $db->quote($_GET['id']))->fetchAll(\Snabb\Database\Connection::FETCH_COLUMN, 0);

if (!$user->isAppAdmin()) {
    if (((int) $task['user_id']) !== $user->user_id and $user->listOwnerOrAdmin($task['list_id']) === false) {
        $tmpl->renderBadLink();
        return;
    }
}

$list_assigments = $db->query('SELECT users.user_id, users.nick FROM users JOIN lists USING(user_id) WHERE lists.list_id = ' . (int)$task['list_id'])->fetchAll();
$list_members = $db->query('SELECT users.user_id, users.nick FROM users JOIN list_users USING(user_id) WHERE list_users.list_id = ' . (int)$task['list_id'])->fetchAll();

foreach ($list_members as $value) {
    $list_assigments[] = $value;
}

if (isset($_POST['title'], $_POST['description'], $_POST['priority'], $_POST['due_date'])) {
    $hlasky = [];
    $tags_from_form = [];
    $user_assigment_ids = [];

    if (!preg_match('~^[a-ž ,\\.\'\\-]{3,50}$~Dsi', $_POST['title']))
        $hlasky[] = 'Zadejte název dlouhý 3-50 znaků. Povolené znaky jsou písmena abecedy, čárka, mezera, tečka, pomlčka a apostrof.';
    else if (false !== $db->query('SELECT list_id FROM tasks WHERE list_id = ' . $db->quote($_GET['id']) . ' AND title = ' . $db->quote($_POST['title']))->fetch() and $_POST['title'] !== $task['title'])
        $hlasky[] = 'Tento název úkolu už vlastní jiný úkol v tomto listu.';

    if (!preg_match('~^.{5,}$~Ds', $_POST['description']))
        $hlasky[] = 'Zadejte text dlouhý nejmíň 5 znaků.';

    if (!preg_match('~^-?\d+$~i', $_POST['priority']))
        $hlasky[] = 'Priorita musí být celočíselná hodnota.';

    if ($_POST['due_date'] !== '') {
        if (($due_date = DateTime::createFromFormat('d. m. Y H:i', $_POST['due_date'])) === false)
            $hlasky[] = 'Datum vyřešení úkolu musí být ve formátu ' . date('d. m. Y H:i') . ' .';
        elseif (($due_date = $due_date->getTimestamp()) < time())
            $hlasky[] = 'Datum vyřešení úkolu musí být v budoucnosti.';
    }

    if ($_POST['tags'] !== '') {
        $tags_from_form = explode(',', $_POST['tags']);
        foreach ($tags_from_form as $tag) {
            if (!preg_match('~^[\\w]{3,50}$~Ds', $tag)) {
                $hlasky[] = 'Tag musí být dlouhý 3-50 znaků. Zadávejte tagy ve formátu tag,tag2,tag3.';
                break;
            }
        }
    }
    
    if (isset($_POST['user_assignments']) and $_POST['user_assignments'] !== '') {
        if (is_array($_POST['user_assignments'])) {
            foreach ($_POST['user_assignments'] as $user_id_assigment) {
                $user_assigment_ids[] = $user_id_assigment;
                if (!User::list_owner_or_admin($db, $user_id_assigment, $task['list_id']) and !User::list_member($db, $user_id_assigment, $task['list_id'])) {
                    $hlasky[] = 'Jeden z přiřazených uživatelů není členem listu.';
                    break;
                }
            }
        } else {
            $user_assigment_ids[] = (int) $_POST['user_assignments'];
            if (!User::list_owner_or_admin($db, $_POST['user_assignments'], $task['list_id']) and !User::list_member($db, $_POST['user_assignments'], $task['list_id'])) {
                $hlasky[] = 'Jeden z přiřazených uživatelů není členem listu.';
            }
        }
    }

    if ($hlasky) {
        $tmpl->addMessage($hlasky, Template::MESSAGE_ERROR);
        $toSelect = ['title', 'description', 'priority', 'due_date', 'tags'];
        if ($user_assigment_ids) {
            $toSelect[] = 'user_assignments';
        }
        $tmpl->assign(\Snabb\Tools\Arrays::selective_keys($_POST, $toSelect));
    } else {
        try {
            $db->beginTransaction();
            $db->update('tasks', \Snabb\Tools\Arrays::leave_empty(\Snabb\Tools\Arrays::selective_keys($_POST, ['title', 'description', 'priority'])) + ['due_date' => isset($due_date) ? $due_date : new \Snabb\Database\Literal('null')], 'task_id = ' . $db->quote($_GET['id']));
            if ($tags_from_form) {
                foreach ($tags_from_form as $tag) {
                    if (!in_array($tag, $task_tags)) {
                        $tag_id = $db->query('SELECT tag_id FROM tags WHERE list_id  = ' . $db->quote($task['list_id']) . ' and tag = ' . $db->quote($tag))->fetch();
                        if ($tag_id === false) {
                            $db->insert('tags', ['tag' => $tag, 'list_id' => $task['list_id']]);
                            $tag_id = $db->query('SELECT tag_id FROM tags WHERE list_id  = ' . $db->quote($task['list_id']) . ' and tag = ' . $db->quote($tag))->fetch();
                            $db->insert('task_tags', ['tag_id' => $tag_id['tag_id'], 'task_id' => $_GET['id']]);
                        } else
                            $db->insert('task_tags', ['tag_id' => $tag_id['tag_id'], 'task_id' => $_GET['id']]);
                    }
                }
            }

            if ($task_tags) {
                foreach ($task_tags as $tag) {
                    if (!($tags_from_form and in_array($tag, $tags_from_form))) {
                        $tag_id = $db->query('SELECT tag_id FROM tags WHERE list_id  = ' . $db->quote($task['list_id']) . ' and tag = ' . $db->quote($tag))->fetch();
                        $count_tags_assigned = $db->countRows('task_tags', 'tag_id = ' . $db->quote($tag_id['tag_id']));
                        if ($count_tags_assigned > 1)
                            $db->exec('DELETE FROM task_tags WHERE tag_id = ' . $db->quote($tag_id['tag_id']) . ' and task_id = ' . $db->quote($_GET['id']));
                        else {
                            $db->exec('DELETE FROM task_tags WHERE tag_id = ' . $db->quote($tag_id['tag_id']));
                            $db->exec('DELETE FROM tags WHERE tag_id = ' . $db->quote($tag_id['tag_id']) . ' AND list_id = ' . $db->quote($task['list_id']));
                        }
                    }
                }
            }

            if ($user_assigment_ids) {
                foreach ($user_assigment_ids as $user_id) {
                    if (!in_array($user_id, $task_assigments)) {
                        $db->insert('tasks_assignment', ['task_id' => $_GET['id'], 'user_id' => $user_id, 'assigned_by' => $user->user_id, 'assign_date' => time()]);
                    }
                }
            }

            if ($task_assigments) {
                foreach ($task_assigments as $user_id) {
                    if (!($user_assigment_ids and in_array($user_id, $user_assigment_ids))) {
                        $db->exec('DELETE FROM tasks_assignment WHERE user_id = ' . $db->quote($user_id) . ' AND task_id =' . $db->quote($_GET['id']));
                    }
                }
            }
            $db->insert('task_edits',['user_id' => $user->user_id, 'task_id' => $_GET['id'], 'task_edit_date' => time()]);
            $db->commit();

            $redirect = '';
            if (isset($_GET['red'])) {
                switch ($_GET['red']) {
                    case 'all_list':
                        $redirect = '?fb=task_edit_success';
                        break;
                    case 'task':
                        $redirect = 'task?id=' . (int) $_GET['id'].'&fb=task_edit_success';
                        break;
                    case 'list':
                    default:
                        $redirect = 'list?id=' . $task['list_id'].'&fb=task_edit_success';
                        break;
                }
            }

            reload($redirect);
        } catch (\Snabb\Database\Exception $e) {
            $db->rollback();
            $toSelect = ['title', 'description', 'priority', 'due_date', 'tags'];
            if ($user_assigment_ids) {
                $toSelect[] = 'user_assignments';
            }
            $tmpl->assign(\Snabb\Tools\Arrays::selective_keys($_POST, $toSelect));
            $tmpl->addMessage('Upravení úkolu se nezdařilo.', Template::MESSAGE_ERROR);
        }
    }
} else {
    if ($task['due_date'] !== null) {
        $task['due_date'] = date('d.m.Y H:i', $task['due_date']);
    } else {
        $task['due_date'] = "";
    }
    $tmpl->assign(\Snabb\Tools\Arrays::selective_keys($task, ['title', 'description', 'priority', 'due_date']));
    if ($task_assigments) {
        $tmpl->assign(['user_assignments' => $task_assigments]);
    }
    if ($task_tags) {
        $tmpl->assign(['tags' => implode(',', $task_tags)]);
    }
}

$tmpl->assign(['submit' => 'Upravit', 'pageHeading' => 'Upravení úkolu', 'list_assigments' => $list_assigments, 'current_menu_list' => (int) $task['list_id']]);
$tmpl->renderTop('Upravení úkolu', $user);
$tmpl->render('forms/task_add.tpl');
$tmpl->renderBottom();
