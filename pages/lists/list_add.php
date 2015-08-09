<?php

if (isset($_POST['list-name'])) {
    $messages = [];
    if (!preg_match('~^[a-ž ,\\.\'\\-]{4,50}$~Dsi', $_POST['list-name']))
        $messages[] = 'Zadejte název listu dlouhý 4-50 znaků. Povolené znaky jsou písmena abecedy, čárka, mezera, tečka, pomlčka a apostrof.';
    else if ($db->countRows('lists', ['user_id' => $user->user_id, 'name' => $_POST['list-name']]) > 0)
        $messages[] = 'Jiný váš list už vlastní tohle jméno.';

    if ($messages) {
        $tmpl->addMessage($messages, Template::MESSAGE_ERROR);
        $tmpl->assign(['list_name' => $_POST['list-name']]);
    } else
    if ($db->insert('lists', ['user_id' => $user->user_id, 'name' => $_POST['list-name']]) !== false){
        $list = $db->query('SELECT list_id FROM lists WHERE user_id = '.$user->user_id.' AND name = '.$db->quote($_POST['list-name']))->fetch();
        reload('list?id='.(int)$list['list_id'].'&fb=list_add_success');
    } else {
        $tmpl->addMessage('Chyba vytvoření listu.', Template::MESSAGE_ERROR);
        $tmpl->assign(['list_name' => $_POST['list-name']]);
    }
}
$tmpl->assign(['submit' => 'Přidat', 'heading' => 'Přidání listu']);
$tmpl->renderTop('Přidání listu', $user);
$tmpl->render('forms/list_add.tpl');
$tmpl->renderBottom();
