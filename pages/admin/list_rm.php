<?php

if (!isset($_GET['id'])) {
    $tmpl->renderBadLink();
    return;
}

if(!$user->isAppAdmin()){
    $tmpl->renderBadLink();
    return;
}

$list = $db->query('SELECT user_id FROM lists WHERE list_id = ' . $db->quote($_GET['id']))->fetch();

if ($list === false) {
    $tmpl->renderBadLink();
    return;
}

if (isset($_POST['yes'])) {
    $db->beginTransaction();
    try{
        $db->exec('DELETE FROM tasks WHERE list_id = '. $db->quote($_GET['id']));
        $db->exec('DELETE FROM list_users WHERE list_id =' . $db->quote($_GET['id']));
        $db->exec('DELETE FROM lists WHERE list_id =' . $db->quote($_GET['id']));
        $db->commit();
        reload('admin/lists?fb=list_rm_success');
    }catch(\Snabb\Database\Exception $e){
        $db->rollback();
        $tmpl->addMessage('List se nepodařilo smazat.', Template::MESSAGE_ERROR);
    }        
}
elseif (isset($_POST['no']))
    reload('admin/lists');

$tmpl->addCss('confirmForm.css');
$tmpl->renderTop('Smazání listu', $user);
$tmpl->assign(['question' => 'Opravdu chcete smazat list?']);
$tmpl->render('forms/confirm.tpl');
$tmpl->renderBottom();