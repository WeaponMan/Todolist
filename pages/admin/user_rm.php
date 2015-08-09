<?php
if (!isset($_GET['id'])) {
    $tmpl->renderBadLink();
    return;
}

if(!$user->isAppAdmin()){
    $tmpl->renderBadLink();
    return;
}

$rm_user = $db->query('SELECT user_id FROM users WHERE user_id = ' . $db->quote($_GET['id']))->fetch();

if ($rm_user === false) {
    $tmpl->renderBadLink();
    return;
}

if (isset($_POST['yes'])) {
    $db->beginTransaction();
    try{
        $lists = $db->query('SELECT list_id FROM lists WHERE user_id = '.$db->quote($_GET['id']))->fetchAll(\Snabb\Database\Connection::FETCH_COLUMN,0);
        if($lists !== false){
            $db->exec('DELETE FROM list_users WHERE list_id IN('.implode(',',$lists).')');
        }
        $db->exec('DELETE FROM tasks WHERE user_id = ' .$db->quote($_GET['id']));
        $db->exec('DELETE FROM lists WHERE user_id = ' . $db->quote($_GET['id']));
        $db->exec('DELETE FROM users WHERE user_id = ' . $db->quote($_GET['id']));
        $db->commit();
        if ((int) $user->user_id === (int) $rm_user['user_id'])
            reload('');
        else
            reload('admin/users&fb=user_rm_success');
    }catch(\Snabb\Database\Exception $e){
        $db->rollback();
        $tmpl->addMessage('Smazání uživatele se nezdařilo.', Template::MESSAGE_ERROR);
    }
}
elseif (isset($_POST['no']))
    reload('admin/users');

$tmpl->addCss('confirmForm.css');
$tmpl->renderTop('Smazání uživatele', $user);
$tmpl->assign(['question' => 'Opravdu chcete smazat uživatele?']);
$tmpl->render('forms/confirm.tpl');
$tmpl->renderBottom();
