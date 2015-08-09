<?php
header('Content-type: text/html;charset=UTF-8');
if (!isset($title))
    $title = 'Stránka bez jména';
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?= $this->autoEscape($title) ?> - Todolist</title>
        <script type="text/javascript">var _PATH_ = '<?= _PATH_ ?>';</script>
        <?php if (!empty($this->js) and (isset($_SESSION['javascript']) and $_SESSION['javascript'] === true)) : foreach ($this->js as $javascript) : ?>
                <script type="text/javascript" src="<?= $javascript ?>"></script> <?php endforeach;
endif;
        ?>
        <?php if (!empty($this->css)) : foreach ($this->css as $cssFile) : ?> 
                <link rel="stylesheet" href="<?= $cssFile ?>" type="text/css" /> <?php endforeach;
endif;
        ?>  
<!--[if lt IE 9]>
    <link rel="stylesheet" type="text/css" href="<?= _PATH_ ?>css/ie.css" />
<![endif]-->
        <?php
        if (isset($_SESSION['javascript'])) {
            if ($_SESSION['javascript'] === true) {
                ?> 
                <noscript>
                <meta http-equiv="refresh" content="0; URL=<?= _PATH_ ?>javascript/disable?redirect=<?= _PATH_ROUTE_ARGS_ ?>">
                </noscript>
                <?php
            } else {
                ?>
                <script type="text/javascript">
                    location.href = '<?= _PATH_ ?>javascript/enable?redirect=<?= _PATH_ROUTE_ARGS_ ?>';
                </script>
                <?php
            }
        }
        ?>
    </head>
    <body>
        <div id="header">
            <div id="sub">
                <div id="logo"><a title="Domovská stránka" href="<?= _PATH_ ?>"><strong>Todolist</strong></a></div>
<?php if ($user !== false): ?>
                    <div id="user-area">
                        <a class="headers-links" href="<?= _PATH_; ?>logout">
                            <span class="glyphicon glyphicon-log-out" title="Odhlášení"></span>
                        </a>
                        <a class="headers-links" href="<?= _PATH_ ?>settings">
                            <span class="glyphicon glyphicon-cog" title="Nastavení"></span>
                        </a>
    <?php if ($user->isAppAdmin()) { ?>
                            <a class="headers-links" href="<?= _PATH_ ?>admin/users">
                                <span class="glyphicon glyphicon-user" title="Uživatelé"></span>
                            </a>
                            <a class="headers-links" href="<?= _PATH_ ?>admin/lists">
                                <span class="glyphicon glyphicon-list" title="Listy"></span>
                            </a>
                    <?php } ?>
                        <div title="Uživatelské jméno"><?= $this->autoEscape($user->nick) ?></div>
                    </div>
<?php endif; ?>
            </div>
        </div>
        <div id="content-container">