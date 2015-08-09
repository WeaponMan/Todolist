<h2>Uživatelé aplikace</h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Nick</th>
            <th>E-mail</th>
            <th>Datum posledního přihlášení</th>
            <th>adminem aplikace od</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($this->vars['users'] as $this->vars['_user']) { ?>
            <tr>
                <td><?php echo $this->autoEscape($this->vars['_user']['nick']); ?></td>
                <td><?php echo $this->autoEscape($this->vars['_user']['email']); ?></td>
                <td><?php echo $this->autoEscape($this->modifier('date', $this->vars['_user']['last_login'], 'j.n.Y v H:i')); ?></td>
                <td><?php if($this->vars['_user']['app_admin_from'] !== null) {  echo $this->autoEscape($this->modifier('date', $this->vars['_user']['list_admin_from'], 'j.n.Y v H:i'));  } else { ?> - <?php } ?></td>
                <td>
                    <div class="btn-group btn-group-xs">
                        <?php if($this->vars['_user']['app_admin_from'] !== null) { ?>
                            <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>admin/user/depose?id=<?php echo $this->autoEscape($this->vars['_user']['user_id']); ?>" title="Sesadit na uživatele">
                                <span class="glyphicon glyphicon-minus"></span> 
                            </a>
                        <?php } else { ?>
                            <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>admin/user/promote?id=<?php echo $this->autoEscape($this->vars['_user']['user_id']); ?>" title="Povýšit na admina aplikace">
                                <span class="glyphicon glyphicon-plus"></span> 
                            </a>
                        <?php } ?>
                        <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>admin/user/rm?id=<?php echo $this->autoEscape($this->vars['_user']['user_id']); ?>" title="Smazat uživatele">
                            <span class="glyphicon glyphicon-trash"></span> 
                        </a>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
