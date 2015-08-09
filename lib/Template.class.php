<?php

class Template extends \Snabb\Templating\Template {

    private $js;
    private $css;
    private $messages = [];

    const MESSAGE_ERROR = 'danger';
    const MESSAGE_WARNING = 'warning';
    const MESSAGE_INFO = 'info';
    const MESSAGE_SUCCESS = 'success';

    public function renderTop($title, $user = false) {
        $this->addCss('bootstrap.min.css');
        $this->addCss('bootstrap-theme.min.css');
        $this->addCss('top.css');
        require $this->tpl_dir . '/core/top.php';
        if ($user !== false) {
            $menu_lists = [];
            $lists = $user->db->query('SELECT DISTINCT lists.list_id, name FROM lists LEFT JOIN list_users USING(list_id) WHERE list_users.user_id = ' . (int) $user->user_id . ' OR lists.user_id = ' . (int) $user->user_id.' ORDER BY list_id DESC');
            foreach ($lists as $list) {
                $menu_lists[$list['list_id']] = $list['name'];
            }
            require $this->tpl_dir . '/core/menu.php';
        }
        $this->renderMessages();
    }

    public function renderBadLink() {
        global $user;
        $this->renderTop('Špatný odkaz', $user);
        $this->render('errors/invalid_link.tpl');
        $this->renderBottom();
    }

    public function renderBottom() {
        require $this->tpl_dir . '/core/bottom.php';
    }

    public function addMessage($message, $type = self::MESSAGE_ERROR) {
        $this->messages[] = [$message, $type];
    }

    public function renderMessages() {
        if (isset($_GET['fb']))
            message_by_get($_GET['fb'], $this);
        if ($this->messages)
        require $this->tpl_dir . '/core/messages.php';
    }

    public function addJavascript($name, $prefixPath = null) {
        $this->js[] = ($prefixPath === null ? _PATH_ . 'js/' : $prefixPath) . $name;
    }

    public function addCss($name, $prefixPath = null) {
        $this->css[] = ($prefixPath === null ? _PATH_ . 'css/' : $prefixPath) . $name;
    }

}
