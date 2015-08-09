<h3>Listy v aplikaci</h3>
<?php if($this->vars['lists']) { ?>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Jméno</th>
            <th>Vlastník</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($this->vars['lists'] as $this->vars['list']) { ?>
            <tr>
                <td><a href="<?php echo $this->autoEscape(_PATH_); ?>list?id=<?php echo $this->autoEscape($this->vars['list']['list_id']); ?>"><?php echo $this->autoEscape($this->vars['list']['name']); ?></a></td>
                <td><?php echo $this->autoEscape($this->vars['list']['nick']); ?></td>
                <td>
                    <div class="btn-group btn-group-xs">
                        <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>list/members/?id=<?php echo $this->autoEscape($this->vars['list']['list_id']); ?>" title="Členové listu">
                            <span class="glyphicon glyphicon-user"></span> 
                        </a>
                        <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>admin/list/rm?id=<?php echo $this->autoEscape($this->vars['list']['user_id']); ?>" title="Smazat list">
                            <span class="glyphicon glyphicon-trash"></span> 
                        </a>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<?php } else { ?>
    <h4>V aplikaci není ani jeden list.</h4>
<?php } ?>