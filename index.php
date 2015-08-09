<?php

$start = microtime(true);
ob_start();

if(PHP_VERSION_ID < 50400)
    exit('This project is written for php version 5.4 and above.');

if (isset($_GET['css'])) {
    if (file_exists('css/' . $_GET['css'] . '.css'))
        require 'css/' . $_GET['css'] . '.css';
    else
        echo '/* FILE NOT EXISTS */';

    header('Content-Type: text/css; charset=utf-8');
    exit();
}

if (isset($_GET['js'])) {
    if (file_exists('js/' . $_GET['js'] . '.js'))
        require 'js/' . $_GET['js'] . '.js';
    else
        echo '/* FILE NOT EXISTS */';

    header('Content-Type: text/javascript; charset=utf-8');
    exit();
}

require_once './lib/Snabb/Object.class.php';
require_once './lib/Snabb/Autoloading/Loader.class.php';
require_once './lib/Snabb/Autoloading/LightLoader.class.php';

new Snabb\Autoloading\LightLoader('lib/');

define('_DOMAIN_', \Snabb\Http\Request::$full_domain);
define('_PATH_', \Snabb\Routing\Router::$path);
define('_PATH_ROUTE_', \Snabb\Routing\Router::$path_route);
define('_PATH_ROUTE_ARGS_', \Snabb\Routing\Router::$path_route_args);
define('AJAX_REQUEST', isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') : false);

const USER_COOKIE_NAME = 'todolist';
function dump($what) {
    echo '<pre>';
    var_dump($what);
    echo '</pre>';
}

function format_string(){
    $args = func_get_args();
    return vsprintf(array_shift($args), $args);
}

if(!(_PATH_ROUTE_ === _PATH_.'javascript/enable' || _PATH_ROUTE_ === _PATH_.'javascript/disable')){
    session_start();
    if(!isset($_SESSION['javascript']))
        require './pages/system/javascript.php'; 
}

function e_html($what) {
    if (defined('ENT_HTML5'))
        return htmlspecialchars($what, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return htmlspecialchars($what, ENT_QUOTES, 'UTF-8');
}

function reload($where) {
    header('Location: ' . _DOMAIN_ . _PATH_ . $where);
    exit();
}

function message_by_get($var, $tmpl){
    $messages = [
        'task_add_success'            => ['Úkol úspěšně přidán.', Template::MESSAGE_SUCCESS],
        'task_edit_success'           => ['Úkol úspěšně upraven.', Template::MESSAGE_SUCCESS],
        'list_member_add_success'     => ['Uživatel úspěšně přidán do listu.', Template::MESSAGE_SUCCESS],
        'list_member_rm_success_self' => ['Úspěšně ste se vyloučil z listu.', Template::MESSAGE_SUCCESS],
        'list_member_rm_success'      => ['Úspěšně ste vyloučil uživatele z listu.', Template::MESSAGE_SUCCESS],
        'member_promote_success'      => ['Úspěšně ste povýšil uživatele na admina.', Template::MESSAGE_SUCCESS],
        'member_promote_failed'       => ['Nepodařilo se povýšit uživatele na admina.', Template::MESSAGE_ERROR],
        'list_edit_success'           => ['List upraven.', Template::MESSAGE_SUCCESS],
        'list_rm_success'             => ['List smazán.', Template::MESSAGE_SUCCESS],
        'member_depose_success'       => ['Úspěšně ste sesadil admina na uživatele.', Template::MESSAGE_SUCCESS],
        'member_depose_failed'        => ['Nepodařilo se sesadit admina na uživatele.', Template::MESSAGE_ERROR],
        'comment_add_success'         => ['Komentář přidán.', Template::MESSAGE_SUCCESS],
        'comment_edit_success'        => ['Komentář upraven.', Template::MESSAGE_SUCCESS],
        'comment_rm_success'          => ['Komentář smazán.', Template::MESSAGE_SUCCESS],
        'comment_rm_failed'           => ['Komentář se nepodařilo smazat.', Template::MESSAGE_ERROR],
        'task_assignment_add_success' => ['Přiřazení k úkolu úspěšné.', Template::MESSAGE_SUCCESS],
        'task_assignment_add_failed'  => ['Přiřazení k úkolu se nezdařilo.', Template::MESSAGE_ERROR],
        'task_assignment_rm_failed'   => ['Zrušení přiřazení k úkolu se nezdařilo.', Template::MESSAGE_ERROR],
        'task_assignment_rm_success'  => ['Zrušení přiřazení k úkolu bylo úspěšné.', Template::MESSAGE_SUCCESS],
        'task_rm_success'             => ['Úkol smazán.', Template::MESSAGE_SUCCESS],
        'task_rm_failed'              => ['Úkol se nepodařilo smazat.', Template::MESSAGE_ERROR],
        'task_done_success'           => ['Úkol nastaven jako dokončený.', Template::MESSAGE_SUCCESS],
        'task_done_failed'            => ['Úkol se nepodařilo nastavit jako dokončený.', Template::MESSAGE_ERROR],
        'task_undone_success'         => ['Úkol nastaven jako nedokončený.', Template::MESSAGE_SUCCESS],
        'task_undone_failed'          => ['Úkol se nepodařilo nastavit jako nedokončený.', Template::MESSAGE_ERROR],
        'user_rm_success'             => ['Uživatel smazán.', Template::MESSAGE_SUCCESS],
        'member_depose_success_self'  => ['Úspěšně ste se sesadil na uživatele listu.', Template::MESSAGE_SUCCESS],
        'user_depose_success_self'    => ['Úspěšně ste se sesadil z admina aplikace na uživatele.', Template::MESSAGE_SUCCESS],
        'user_depose_success'         => ['Úspěšně ste sesadil admina aplikace na uživatele.', Template::MESSAGE_SUCCESS],
        'user_depose_failed'          => ['Nepodařilo se sesadit admina aplikace na uživatele.', Template::MESSAGE_ERROR],
        'change_password_success'     => ['Heslo změněno.', Template::MESSAGE_SUCCESS],
        'change_email_failed'         => ['Při potvrzení změny e-mailové adresy došlo k chybě.', Template::MESSAGE_ERROR],
        'change_email_new_success'    => ['Email se podařilo změnit.', Template::MESSAGE_SUCCESS],
        'change_email_old_success'    => ['První část potvrzení změny e‑mailové adresy byla úspěšná. Pro dokončení změny, potvrďte změnu i na Vaší budoucí e‑mailové adrese, potvrzovací odkaz tam byl odeslán.', Template::MESSAGE_SUCCESS],
        'list_add_success'            => ['List vytvořen.', Template::MESSAGE_SUCCESS],
        
    ];
    if(isset($messages[$var]))
        $tmpl->addMessage($messages[$var][0], $messages[$var][1]);
}

$tmpl = new Template('templates', 'templates/cached', null, [
    'date' => function ($timestamp, $format) {
return date($format, $timestamp);
},
    'nltobr' => function($string) {
return nl2br($string);
},
    'sprintf' => function($string, $format) {
return sprintf($format, $string);
}
        ], 'e_html');      
try {
    $cfg = new Config('lib/database.ini');
    $driver = '\\Snabb\\Database\\Drivers\\' . $cfg['Database']['use'] . '\\Connection';
    if (strpos(strtolower($cfg['Database']['use']), 'sqlite') !== false)
        $db = new $driver($cfg[$cfg['Database']['use']]['file']);
    else if (isset($cfg[$cfg['Database']['use']]['port']))
        $db = new $driver($cfg[$cfg['Database']['use']]['host'], $cfg[$cfg['Database']['use']]['user'], $cfg[$cfg['Database']['use']]['password'], $cfg[$cfg['Database']['use']]['database'], $cfg[$cfg['Database']['use']]['port']);
    else
        $db = new $driver($cfg[$cfg['Database']['use']]['host'], $cfg[$cfg['Database']['use']]['user'], $cfg[$cfg['Database']['use']]['password'], $cfg[$cfg['Database']['use']]['database']);
} catch (\Snabb\Database\Exception $e) {
    $tmpl->renderTop('Připojení s databází selhalo');
    $tmpl->addMessage('Připojení s databází selhalo.', Template::MESSAGE_ERROR);
    $tmpl->renderBottom();
    exit();
}

$user = false;

if (isset($_COOKIE[USER_COOKIE_NAME])) {
    if (sha1($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']) === substr($_COOKIE[USER_COOKIE_NAME], 40) and ($auth = $db->query('SELECT user_id, nick, email, app_admin_from FROM users WHERE password = ' . $db->quote(substr($_COOKIE[USER_COOKIE_NAME], 0, 40)))->fetch()))
        $user = new User($db, $auth);
    else
        setcookie(USER_COOKIE_NAME, $_COOKIE[USER_COOKIE_NAME], time() - 1);
}

require './lib/RouterTable.php';
$router = new Snabb\Routing\PrivilegedRouter($table, 'pages/', 'errors/not_found.php', 'errors/access_denied.php');
$to_require = $router->findRoute(function($routePrivilege) use ($user) {
    if($routePrivilege === ROUTE_ALL) return true;
    return ($user === false ? ($routePrivilege === false ? true : false) : ($routePrivilege === true ? true : false));
});
unset($table);
call_user_func(function () use ($to_require, $user, $db, $tmpl) {
    require_once $to_require;
});

new DebugWindow($db, $start);

ob_end_flush();