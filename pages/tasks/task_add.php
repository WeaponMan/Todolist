<?php

if (!isset($_GET['id'])) {
    $tmpl->renderBadLink();
    return;
}
if (!$user->isAppAdmin()) {
    if ($user->listOwnerOrAdmin($_GET['id']) === false and $user->listMember($_GET['id']) === false) {
        $tmpl->renderBadLink();
        return;
    }
}

$list_assigments = $db->query('SELECT users.user_id, users.nick FROM users JOIN lists ON users.user_id = lists.user_id WHERE lists.list_id = ' . $db->quote($_GET['id']))->fetchAll();
$list_members = $db->query('SELECT users.user_id, users.nick FROM users JOIN list_users ON list_users.user_id = users.user_id WHERE list_users.list_id = ' . $db->quote($_GET['id']))->fetchAll();
foreach ($list_members as $value) {
    $list_assigments[] = $value;
}

if (isset($_POST['title'], $_POST['description'], $_POST['priority'], $_POST['due_date'], $_POST['tags'])) {
    $hlasky = [];
    $tags_from_form = [];
    $user_assigment_ids = [];
    if (!preg_match('~^[a-ž ,\\.\'\\-]{3,50}$~Dsi', $_POST['title']))
        $hlasky[] = 'Zadejte název dlouhý 3-50 znaků. Povolené znaky jsou písmena abecedy, čárka, mezera, tečka, pomlčka a apostrof.';
    else if (false !== $db->query('SELECT list_id FROM tasks WHERE list_id = ' . $db->quote($_GET['id']) . ' AND title = ' . $db->quote($_POST['title']))->fetch())
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
                if (!User::list_owner_or_admin($db, $user_id_assigment, $_GET['id']) and !User::list_member($db, $user_id_assigment, $_GET['id'])) {
                    $hlasky[] = 'Jeden z přiřazených uživatelů není členem listu.';
                    break;
                }
            }
        } else {
            $user_assigment_ids[] = (int) $_POST['user_assignments'];
            if (!User::list_owner_or_admin($db, $_POST['user_assignments'], $_GET['id']) and !User::list_member($db, $_POST['user_assignments'], $_GET['id'])) {
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
            $db->insert('tasks', \Snabb\Tools\Arrays::leave_empty(\Snabb\Tools\Arrays::selective_keys($_POST, ['title', 'description', 'priority'])) + ['list_id' => $_GET['id'], 'user_id' => $user->user_id, 'create_date' => time(), 'due_date' => isset($due_date) ? $due_date : new \Snabb\Database\Literal('null')]);
            $task_id = $db->query('SELECT task_id FROM tasks WHERE title = ' . $db->quote($_POST['title']) . ' AND description = ' . $db->quote($_POST['description']) . ' AND user_id = ' . $db->quote($user->user_id) . ' AND list_id = ' . $db->quote($_GET['id']) . ' AND priority = ' . $db->quote($_POST['priority']))->fetch();
            if ($tags_from_form) {
                foreach ($tags_from_form as $tag) {
                    $tag_id = $db->query('SELECT tag_id FROM tags WHERE list_id  = ' . $db->quote($_GET['id']) . ' and tag = ' . $db->quote($tag))->fetch();
                    if ($tag_id === false) {
                        $db->insert('tags',['tag' => $tag, 'list_id' => $_GET['id']]);
                        $tag_id = $db->query('SELECT tag_id FROM tags WHERE list_id  = ' . $db->quote($_GET['id']) . ' and tag = ' . $db->quote($tag))->fetch();
                        $db->insert('task_tags', ['tag_id' => $tag_id['tag_id'], 'task_id' => $task_id['task_id']]);
                    } else
                        $db->insert('task_tags', ['tag_id' => $tag_id['tag_id'], 'task_id' => $task_id['task_id']]);
                }
            }
            if ($user_assigment_ids) {
                foreach ($user_assigment_ids as $user_id) {
                    $db->insert('tasks_assignment', ['task_id' => $task_id['task_id'], 'user_id' => $user_id, 'assigned_by' => $user->user_id, 'assign_date' => time()]);
                }
            }
            $db->commit();
            reload('list?id='.(int)$_GET['id'].'&fb=task_add_success');
        } catch (\Snabb\Database\Exception $e) {
            $db->rollback();
            $toSelect = ['title', 'description', 'priority', 'due_date', 'tags'];
            if ($user_assigment_ids) {
                $toSelect[] = 'user_assignments';
            }
            $tmpl->assign(\Snabb\Tools\Arrays::selective_keys($_POST, $toSelect));
            $tmpl->addMessage('Přidání úkolu se nezdařilo.', Template::MESSAGE_ERROR);
        }
    }
}

$tmpl->assign(['submit' => 'Přidat',
    'pageHeading' => 'Přidání úkolu do listu',
    'list_assigments' => $list_assigments,
    'current_menu_list' => (int) $_GET['id']]);
$tmpl->renderTop('Přidání úkolu do listu', $user);
$tmpl->render('forms/task_add.tpl');
$tmpl->renderBottom();
