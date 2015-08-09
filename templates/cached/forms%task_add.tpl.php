<h2><?php echo $this->autoEscape($this->vars['pageHeading']); ?></h2>
<form class="form-horizontal" action="<?php echo $this->autoEscape(_PATH_ROUTE_ARGS_); ?>" method="post" id="task-add-form">
    <div class="form-group">    
        <label for="task-add-title" class="col-sm-2 control-label"><span class="required">* </span>Název úkolu:</label>
        <div class="col-sm-3">
            <input type="text" name="title" class="form-control" id="task-add-title" <?php if(isset($this->vars['title'])) { ?>value="<?php echo $this->autoEscape($this->vars['title']); ?>" <?php } ?>/>
        </div>
    </div>
    <div class="form-group">    
        <label for="task-add-title" class="col-sm-2 control-label"><span class="required">* </span>Popis úkolu:</label>
        <div class="col-sm-3">
            <textarea name="description" class="form-control" id="task-add-description"><?php if(isset($this->vars['description'])) {  echo $this->autoEscape($this->vars['description']);  } ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="priority"><span class="required">* </span>Priorita:</label>
        <div class="col-sm-3">
            <input type="number" class="form-control"name="priority" <?php if(isset($this->vars['priority'])) { ?>value="<?php echo $this->autoEscape($this->vars['priority']); ?>" <?php } else { ?>value="0" <?php } ?>/>
        </div>
    </div>
    <div class="form-group">    
        <label for="task-add-due-date" class="col-sm-2 control-label">Datum dokončení:</label>
        <div class="col-sm-3">
            <input title="Například: <?php echo $this->autoEscape($this->modifier('date', $this->vars['current_date'], 'j.n.Y H:i')); ?>" type="text" name="due_date" class="form-control" id="task-due-date" <?php if(isset($this->vars['due_date'])) { ?>value="<?php echo $this->autoEscape($this->vars['due_date']); ?>" <?php } ?>/>
        </div>
    </div>
    <div class="form-group">    
        <label for="task-add-tags" class="col-sm-2 control-label">Tagy:</label>
        <div class="col-sm-3">
            <input type="text" name="tags" class="form-control" id="task-add-tags" <?php if(isset($this->vars['tags'])) { ?>value="<?php echo $this->autoEscape($this->vars['tags']); ?>" <?php } ?>/>
        </div>
    </div>
    <div class="form-group">    
        <label for="task-add-assignments" class="col-sm-2 control-label">Přiřadit uživatele:</label>
        <div class="col-sm-3">
            <select name="user_assignments" id="task-add-assignments" class="form-control" multiple>
                <?php foreach($this->vars['list_assigments'] as $this->vars['assignment']) { ?>
                    <?php if(isset($this->vars['user_assignments'])) { ?>
                        <?php if(is_array($this->vars['user_assignments'])) { ?>
                            <option <?php if(in_array($this->vars['assignment']['user_id'], $this->vars['user_assignments'])) { ?>selected <?php } ?>value="<?php echo $this->autoEscape($this->vars['assignment']['user_id']); ?>"><?php echo $this->autoEscape($this->vars['assignment']['nick']); ?></option>
                        <?php } else { ?>
                            <option <?php if($this->vars['user_assignments'] === $this->vars['assignment']['user_id']) { ?>selected <?php } ?>value="<?php echo $this->autoEscape($this->vars['assignment']['user_id']); ?>"><?php echo $this->autoEscape($this->vars['assignment']['nick']); ?></option>
                        <?php } ?>
                    <?php } else { ?>
                        <option value="<?php echo $this->autoEscape($this->vars['assignment']['user_id']); ?>"><?php echo $this->autoEscape($this->vars['assignment']['nick']); ?></option>
                    <?php } ?>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-4">
            <strong><span class="required">* </span>Položky formuláře označené hvězdičkou jsou povinné.</strong>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-3">
            <input class="btn btn-primary" type="submit" value="<?php echo $this->autoEscape($this->vars['submit']); ?>" />
        </div>
    </div>
</form>