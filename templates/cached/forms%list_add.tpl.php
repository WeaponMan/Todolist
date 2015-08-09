<h2><?php echo $this->autoEscape($this->vars['heading']); ?></h2>
<form class="form-horizontal" role="form" action="<?php echo $this->autoEscape(_PATH_ROUTE_ARGS_); ?>" method="post" id="list-add-form">
    <div class="form-group">
        <label for="list-add-name" class="col-sm-2 control-label">Jméno listu:</label>
        <div class="col-sm-3">
            <input class="form-control" type="text" name="list-name" id="list-add-name" <?php if(isset($this->vars['list_name'])) { ?>value="<?php echo $this->autoEscape($this->vars['list_name']); ?>" <?php } ?>/>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-3">
            <input class="btn btn-primary" type="submit" value="<?php echo $this->autoEscape($this->vars['submit']); ?>" />
        </div>
    </div>
</form>