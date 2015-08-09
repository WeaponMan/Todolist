<?php
$tmpl->renderTop('Pro zobrazení této stránky nemáte dostatečná oprávnění', $user);
$tmpl->render('errors/access_denied.tpl');
$tmpl->renderBottom();