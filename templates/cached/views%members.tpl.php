<h2>Členové listu</h2>
<?php if($this->vars['list_members']) { ?>    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nick</th>
                <th>přidán uživatelem</th>
                <th>členem od</th>
                <th>adminem od</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($this->vars['list_members'] as $this->vars['member']) { ?>
                <tr>
                    <td><?php echo $this->autoEscape($this->vars['member']['user_name']); ?></td>
                    <td><?php echo $this->autoEscape($this->vars['member']['added_user_name']); ?></td>
                    <td><?php echo $this->autoEscape($this->modifier('date', $this->vars['member']['member_from'], 'j.n.Y v H:i')); ?></td>
                    <td><?php if($this->vars['member']['list_admin_from'] !== null) {  echo $this->autoEscape($this->modifier('date', $this->vars['member']['list_admin_from'], 'j.n.Y v H:i'));  } else { ?> - <?php } ?></td>
                    <td>
                        <div class="btn-group btn-group-xs">
                            <?php if($this->vars['member']['list_admin_from'] !== null) { ?>
                                <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>list/member/depose?list_id=<?php echo $this->autoEscape($this->vars['current_menu_list']); ?>&id=<?php echo $this->autoEscape($this->vars['member']['user_id']); ?>" title="Sesadit na člena">
                                    <span class="glyphicon glyphicon-minus"></span> 
                                </a>
                            <?php } else { ?>
                                <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>list/member/promote?list_id=<?php echo $this->autoEscape($this->vars['current_menu_list']); ?>&id=<?php echo $this->autoEscape($this->vars['member']['user_id']); ?>" title="Povýšit na admina">
                                    <span class="glyphicon glyphicon-plus"></span> 
                                </a>
                            <?php } ?>
                            <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>list/member/rm?list_id=<?php echo $this->autoEscape($this->vars['current_menu_list']); ?>&id=<?php echo $this->autoEscape($this->vars['member']['user_id']); ?>" title="Vyloučit z listu">
                                    <span class="glyphicon glyphicon-trash"></span> 
                            </a>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } else { ?>
    <h4>Nemáte v listu žádné členy.</h4>
<?php } ?>