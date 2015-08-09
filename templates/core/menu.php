<ul class="nav nav-tabs" id="list-menu">
    <?php
    if ($menu_lists) {
        foreach ($menu_lists as $id => $name) {
            ?>
            <li<?php if (isset($this->vars['current_menu_list']) and (int)$this->vars['current_menu_list'] === (int)$id) { ?> class="active"<?php } ?>>
                <a href="<?= _PATH_ ?>list?id=<?= $id ?>"><?= $name ?></a>
            </li><?php
        }
    }
    ?>
    <li>
        <a id="list-add-link" href="<?= _PATH_ ?>list/add">
            <span class="glyphicon glyphicon-plus-sign" title="Přidat list"></span>
        </a>
    </li>
</ul>