<?php

if (!isset($_GET['id'])) {
    $tmpl->renderBadLink();
    return;
}

$task = $db->query('SELECT list_id, task_id FROM tasks WHERE task_id = ' . $db->quote($_GET['id']))->fetch();

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
            $redirect = '?fb=comment_add_success';
            break;
        case 'task':
            $redirect = 'task?id=' . $task['task_id'].'&fb=comment_add_success';
            break;
        case 'list':
        default:
            $redirect = 'list?id=' . $task['list_id'].'&fb=comment_add_success';
            break;
    }
}

if (isset($_POST['text'])) {
    if (!preg_match('~^.{5,}$~Ds', $_POST['text'])) {
        $tmpl->addMessage('Zadejte text dlouhý nejmíň 5 znaků.', Template::MESSAGE_ERROR);
        $tmpl->assign(['text' => $_POST['text']]);
    } else {
        if ($db->insert('replies', ['task_id' => $task['task_id'], 'user_id' => $user->user_id, 'posted' => time(), 'text' => $_POST['text']]) !== false) {
                reload($redirect);
        }else {
            $tmpl->addMessage('Komentář se nepodařilo přidat.', Template::MESSAGE_ERROR);
            $tmpl->assign(['text' => $_POST['text']]);
        }
    }
}

$tmpl->assign(['submit' => 'Přidat',
    'pageHeading' => 'Přidání komentáře',
    'current_menu_list' => (int) $task['list_id']]);
$tmpl->renderTop('Přidání komentáře', $user);
$tmpl->render('forms/reply.tpl');
$tmpl->renderBottom();
