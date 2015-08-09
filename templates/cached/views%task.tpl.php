<?php  
    $this->vars['user_assigned'] = false; 
    $bb = new NBBC\BBCode(); 
    $bb->SetTagMarker("["); 
    $bb->SetAllowAmpersand(true); 
    $bb->SetEnableSmileys(false); 
    $bb->SetDetectURLs(true); 
    $bb->SetPlainMode(false); 
    $bb->SetWikiURL("http://cs.wikipedia.org/wiki/"); 
 ?>
<?php if(isset($this->vars['tasks_assignment'])) { ?>
    <?php foreach($this->vars['tasks_assignment'] as $this->vars['assigment_user_id'] => $this->vars['val']) { ?>
        <?php  $this->vars['user_assigned'] = $this->vars['assigment_user_id'] === $this->vars['current_user_id']; ?>
    <?php } ?>   
<?php } ?>
<div style="width: 100%; height: 5px;"></div>
<div class="task panel panel-default" id="task_<?php echo $this->autoEscape($this->vars['task']['task_id']); ?>">
    <div class="panel-heading">
        <span class="btn btn-default active btn-xs" title="Priorita"><?php echo $this->autoEscape($this->modifier('sprintf', $this->vars['task']['priority'], '%+d')); ?></span>
        <span class="task-title"><?php echo $this->autoEscape($this->vars['task']['title']);  if($this->vars['task']['due_date'] !== null) { ?>, <small>deadline: <?php echo $this->autoEscape($this->modifier('date', $this->vars['task']['due_date'], 'j.n.Y v H:i'));  } ?></small></span>
        <span class="task-heading-text"><strong><?php echo $this->autoEscape($this->vars['task']['nick']); ?></strong>, <?php echo $this->autoEscape($this->modifier('date', $this->vars['task']['create_date'], 'j.n.Y v H:i')); ?></span>
    </div>
    <div class="panel-body">
        <?php echo $bb->Parse($this->vars['task']['description']); ?>
    </div>
    <div class="panel-footer">
        <?php if(isset($this->vars['task_tags'])) { ?>
            <span class="task-tags" title="Tagy">
                <?php foreach($this->vars['task_tags'] as $this->vars['tag_id'] => $this->vars['tag']) { ?>
                    <a class="label label-default" href="<?php echo $this->autoEscape(_PATH_); ?>list/tag?id=<?php echo $this->autoEscape($this->vars['tag_id']); ?>">
                        <span class="glyphicon glyphicon-tag"></span> 
                        <?php echo $this->autoEscape($this->vars['tag']); ?>
                    </a>  
                <?php } ?> 
            </span>
        <?php } ?>
        <div class="btn-group btn-group-xs task-buttons">
            <?php if($this->vars['task']['done_date'] === null and ($this->vars['user_assigned'] or $this->vars['admin'] or (int)$this->vars['task']['user_id'] === $this->vars['current_user_id'])) { ?>
                <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>task/done?id=<?php echo $this->autoEscape($this->vars['task']['task_id']); ?>&red=task" title="Hotový?">
                    <span class="glyphicon glyphicon-check"></span> 
                </a>
            <?php } else { ?>
                <a class="btn btn-success" href="<?php echo $this->autoEscape(_PATH_); ?>task/undone?id=<?php echo $this->autoEscape($this->vars['task']['task_id']); ?>&red=rask" title="Dokončeno">
                    <span class="glyphicon glyphicon-check"></span> <?php echo $this->autoEscape($this->modifier('date', $this->vars['task']['done_date'], 'j.n.Y v H:i')); ?>
                </a>
            <?php } ?>

            <?php if(!$this->vars['user_assigned'] and $this->vars['task']['done_date'] === null) { ?>
                <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>task/assignment/add?id=<?php echo $this->autoEscape($this->vars['task']['task_id']); ?>&red=task" title="Přiřadit se k úkolu">
                    <span class="glyphicon glyphicon-plus"></span> 
                </a>
            <?php } ?>
            <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>task/reply/add?id=<?php echo $this->autoEscape($this->vars['task']['task_id']); ?>&red=task" title="Komentovat">
                <span class="glyphicon glyphicon-comment"></span> 
            </a> 
            <?php if($this->vars['admin'] or $this->vars['current_user_id'] === (int)$this->vars['task']['user_id']) { ?>
                <?php if($this->vars['task']['done_date'] === null) { ?>
                    <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>task/edit?id=<?php echo $this->autoEscape($this->vars['task']['task_id']); ?>&red=task" title="Upravit">
                        <span class="glyphicon glyphicon-edit"></span> 
                    </a>
                <?php } ?>
                <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>task/rm?id=<?php echo $this->autoEscape($this->vars['task']['task_id']); ?>&red=list" title="Smazat">
                    <span class="glyphicon glyphicon-trash"></span> 
                </a>
            <?php } ?>
        </div>
        <?php if(isset($this->vars['tasks_assignment'])) { ?>
            <span class="task-assignment" title="Přiřazení uživatelé">
                <?php foreach($this->vars['tasks_assignment'] as $this->vars['assignment_user_id'] => $this->vars['nick']) { ?>
                    <?php if($this->vars['task']['done_date'] === null and ($this->vars['admin'] or $this->vars['current_user_id'] === (int)$this->vars['task']['user_id'] or (int)$this->vars['assignment_user_id'] === $this->vars['current_user_id'])) { ?>
                        <a class="label label-default" href="<?php echo $this->autoEscape(_PATH_); ?>task/assignment/rm?task_id=<?php echo $this->autoEscape($this->vars['task']['task_id']); ?>&id=<?php echo $this->autoEscape($this->vars['assignment_user_id']); ?>&red=task">
                            <span class="glyphicon glyphicon-user"></span> 
                            <span class="glyphicon glyphicon-remove"></span> 
                            <?php echo $this->autoEscape($this->vars['nick']); ?>
                        </a>
                    <?php } else { ?>
                        <div class="label label-default">
                            <span class="glyphicon glyphicon-user"></span>
                            <?php echo $this->autoEscape($this->vars['nick']); ?>
                        </div>
                    <?php } ?>
                <?php } ?>  
            </span>
        <?php } ?>
    </div>
</div>
<?php if(isset($this->vars['comments'])) { ?>
    <?php foreach($this->vars['comments'] as $this->vars['comment']) { ?>
        <div class="reply task panel panel-default" id="reply_<?php echo $this->autoEscape($this->vars['comment']['reply_id']); ?>">
            <div class="panel-heading">
                <span>
                    <strong><?php echo $this->autoEscape($this->vars['comment']['nick']); ?></strong>, 
                    <?php echo $this->autoEscape($this->modifier('date', $this->vars['comment']['posted'], 'j.n.Y v H:i')); ?></span>
                <div class="btn-group btn-group-xs task-buttons">
                    <?php if($this->vars['admin'] or $this->vars['current_user_id'] === (int)$this->vars['comment']['user_id']) { ?>
                        <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>task/reply/edit?id=<?php echo $this->autoEscape($this->vars['comment']['reply_id']); ?>&red=task" title="Upravit">
                            <span class="glyphicon glyphicon-edit"></span> 
                        </a>
                        <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>task/reply/rm?id=<?php echo $this->autoEscape($this->vars['comment']['reply_id']); ?>&red=task" title="Smazat">
                            <span class="glyphicon glyphicon-trash"></span> 
                        </a>
                    <?php } ?>
                </div>
                <?php if($this->vars['comment']['editor'] !== null and $this->vars['comment']['reply_edit_date'] !== null) { ?>
                    <span title="Naposledy upraveno" class="edit-label label label-info"><span class="glyphicon glyphicon-edit"></span> <?php echo $this->autoEscape($this->modifier('date', $this->vars['comment']['reply_edit_date'], 'j.n.Y v H:i')); ?>, <?php echo $this->autoEscape($this->vars['comment']['editor']); ?></span>
                <?php } ?>
            </div>
            <div class="panel-body">
                <?php echo $bb->Parse($this->vars['comment']['text']); ?>
            </div>
        </div>
    <?php } ?>
<?php } ?>