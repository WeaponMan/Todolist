<?php

if (!isset($_GET['id'])) {
    $tmpl->renderBadLink();
    return;
}

if ($user->listOwnerOrAdmin($_GET['id']) === false and $user->listMember($_GET['id']) === false) {
    $tmpl->renderBadLink();
    return;
}

$list = $db->query('SELECT name, list_id FROM lists WHERE list_id = ' . $db->quote($_GET['id']))->fetch();

if($list === false){
    $tmpl->renderBadLink();
    return;
}

if (isset($_POST['list-name'])) {
    $messages = [];
    if (!preg_match('~^[a-ž ,\\.\'\\-]{4,50}$~Dsi', $_POST['list-name']))
        $messages[] = 'Zadejte název listu dlouhý 4-50 znaků. Povolené znaky jsou písmena abecedy, čárka, mezera, tečka, pomlčka a apostrof.';
    else if ($db->countRows('lists', ['user_id' => $user->user_id, 'name' => $_POST['list-name']]) > 0 and $_POST['list-name'] !== $list['name'])
        $messages[] = 'Jiný váš list už vlastní tohle jméno.';

    if ($messages) {
        $tmpl->addMessage($messages, Template::MESSAGE_ERROR);
        $tmpl->assign(['list_name' => $_POST['list-name']]);
    } else {
        if ($db->update('lists', ['name' => $_POST['list-name']], 'list_id = '.(int)$list['list_id'].' AND user_id = '.$user->user_id) !== false)
            reload('list?id='.(int)$list['list_id'].'&fb=list_edit_success');
        else {
            $tmpl->addMessage('Chyba při úpravě listu.', Template::MESSAGE_ERROR);
            $tmpl->assign(['list_name' => $_POST['list-name']]);
        }
    }
} else {
    $tmpl->assign(['list_name' => $list['name']]);
}
$tmpl->assign(['submit' => 'Změnit', 'heading' => 'Úprava listu']);
$tmpl->renderTop('Úprava listu', $user);
$tmpl->render('forms/list_add.tpl');
$tmpl->renderBottom();
