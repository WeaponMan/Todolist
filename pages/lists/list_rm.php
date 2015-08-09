<?php

if (!isset($_GET['id'])) {
    $tmpl->renderBadLink();
    return;
}

$list = $db->query('SELECT user_id FROM lists WHERE list_id = ' . $db->quote($_GET['id']))->fetch();

if ($list === false) {
    $tmpl->renderBadLink();
    return;
}

if (((int) $list['user_id']) !== (int) $user->user_id) {
    $tmpl->renderBadLink();
    return;
}


if (isset($_POST['yes'])) {
    if ($db->countRows('tasks', 'list_id = ' . $db->quote($_GET['id'])) > 0) {
        $tmpl->addMessage('List musí být prázdný, aby se dal smazat.', Template::MESSAGE_ERROR);
    } else {
        $db->beginTransaction();
        try {
            $db->exec('DELETE FROM list_users WHERE list_id =' . $db->quote($_GET['id']));
            $db->exec('DELETE FROM lists WHERE list_id =' . $db->quote($_GET['id']));
            $db->commit();
            reload('?fb=list_rm_success');
        } catch (\Snabb\Database\Exception $e) {
            $db->rollback();
            $tmpl->addMessage('List se nepodařilo smazat.', Template::MESSAGE_ERROR);
        }
    }
} elseif (isset($_POST['no']))
    reload('list?id=' . (int) $_GET['id']);

$tmpl->addCss('confirmForm.css');
$tmpl->renderTop('Odstranění listu', $user);
$tmpl->assign(['question' => 'Opravdu chcete tento list odstranit?']);
$tmpl->render('forms/confirm.tpl');
$tmpl->renderBottom();
