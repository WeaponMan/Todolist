<div id="list-heading">
    <?php if(isset($this->vars['list_name'])) { ?><h3><?php echo $this->autoEscape($this->vars['list_name']); ?></h3><?php } ?>
    <div class="btn-group btn-group-sm">
        <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>task/add?id=<?php echo $this->autoEscape($this->vars['current_menu_list']); ?>">
            <span class="glyphicon glyphicon-plus-sign" title="Přidat úkol"></span>
        </a>
        <?php if($this->vars['admin'] or $this->vars['owner']) { ?>  
            <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>list/member/add?id=<?php echo $this->autoEscape($this->vars['current_menu_list']); ?>">
                <span class="glyphicon glyphicon-plus" title="Přidat člena"></span>
            </a>
            <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>list/members?id=<?php echo $this->autoEscape($this->vars['current_menu_list']); ?>">
                <span class="glyphicon glyphicon-user" title="Členové listu"></span>
            </a>
        <?php } ?>   
        <?php if($this->vars['owner']) { ?>
            <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>list/edit?id=<?php echo $this->autoEscape($this->vars['current_menu_list']); ?>">
                <span class="glyphicon glyphicon glyphicon-edit" title="Upravit list"></span>
            </a>
            <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>list/rm?id=<?php echo $this->autoEscape($this->vars['current_menu_list']); ?>">
                <span class="glyphicon glyphicon glyphicon-trash" title="Smazat list"></span>
            </a>
        <?php } else { ?>
            <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>list/member/rm?list_id=<?php echo $this->autoEscape($this->vars['current_menu_list']); ?>&id=<?php echo $this->autoEscape($this->vars['user_id']); ?>&red=all_list">
                <span class="glyphicon glyphicon glyphicon-minus" title="Odejít z listu"></span>
            </a>
        <?php } ?>
    </div>
</div>
<?php if(empty($this->vars['tasks'])) { ?>
    <h4>Nejsou zde žádné úkoly.</h4>
<?php } else { ?>    
    <?php 
    $bb = new NBBC\BBCode(); 
    $bb->SetTagMarker("["); 
    $bb->SetAllowAmpersand(true); 
    $bb->SetEnableSmileys(false); 
    $bb->SetDetectURLs(true); 
    $bb->SetPlainMode(false); 
    $bb->SetWikiURL("http://cs.wikipedia.org/wiki/"); 
     ?>
    <?php foreach($this->vars['tasks'] as $this->vars['task']) { ?>
        <?php 
    $this->vars['task_id'] = $this->vars['task']['task_id'];
    $this->vars['user_assigned'] = false;
         ?>
        <?php if(isset($this->vars['tasks_assignment'],$this->vars['tasks_assignment'][$this->vars['task_id']])) { ?>
            <?php foreach($this->vars['tasks_assignment'][$this->vars['task_id']] as $this->vars['assignment']) { ?>
                <?php  $this->vars['user_assigned'] = (int)$this->vars['assignment']['user_id'] === $this->vars['user_id']; ?>
            <?php } ?>   
        <?php } ?>
        <div class="task panel panel-default" id="task_<?php echo $this->autoEscape($this->vars['task']['task_id']); ?>">
            <div class="panel-heading">
                <span class="btn btn-default active btn-xs" title="Priorita"><?php echo $this->autoEscape($this->modifier('sprintf', $this->vars['task']['priority'], '%+d')); ?></span>
                <span class="task-title"><a href="<?php echo $this->autoEscape(_PATH_); ?>task?id=<?php echo $this->autoEscape($this->vars['task']['task_id']); ?>"><?php echo $this->autoEscape($this->vars['task']['title']); ?></a><?php if($this->vars['task']['due_date'] !== null) { ?>, <small>deadline: <?php echo $this->autoEscape($this->modifier('date', $this->vars['task']['due_date'], 'j.n.Y v H:i'));  } ?></small></span>
                <?php if($this->vars['task']['editor'] !== null and $this->vars['task']['task_edit_date'] !== null) { ?>
                    <span title="Naposledy upraveno" class="t-edit-label label label-info"><span class="glyphicon glyphicon-edit"></span> <?php echo $this->autoEscape($this->modifier('date', $this->vars['task']['task_edit_date'], 'j.n.Y v H:i')); ?>, <?php echo $this->autoEscape($this->vars['task']['editor']); ?></span>
                <?php } ?>
                <span class="task-heading-text"><strong><?php echo $this->autoEscape($this->vars['task']['nick']); ?></strong>, <?php echo $this->autoEscape($this->modifier('date', $this->vars['task']['create_date'], 'j.n.Y v H:i')); ?></span>
            </div>
            <div class="panel-body">
                <?php 
                    echo $bb->Parse($this->vars['task']['description']);
                 ?>
            </div>
            <div class="panel-footer">
                <?php if(isset($this->vars['task_tags'],$this->vars['task_tags'][$this->vars['task_id']])) { ?>
                    <span class="task-tags" title="Tagy">
                        <?php foreach($this->vars['task_tags'][$this->vars['task_id']] as $this->vars['tag']) { ?>
                            <a class="label label-default" href="<?php echo $this->autoEscape(_PATH_); ?>list/tag?id=<?php echo $this->autoEscape($this->vars['tag']['tag_id']); ?>">
                                <span class="glyphicon glyphicon-tag"></span> 
                                <?php echo $this->autoEscape($this->vars['tag']['tag']); ?>
                            </a>  
                        <?php } ?> 
                    </span>
                <?php } ?>
                <div class="btn-group btn-group-xs task-buttons">
                    <?php if(($this->vars['user_assigned'] or $this->vars['admin'] or (int)$this->vars['task']['user_id'] === $this->vars['user_id'])) { ?>
                        <?php if($this->vars['task']['done_date'] === null) { ?>
                            <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>task/done?id=<?php echo $this->autoEscape($this->vars['task_id']); ?>&red=list" title="Hotový?">
                                <span class="glyphicon glyphicon-check"></span> 
                            </a>
                        <?php } else { ?>
                            <a class="btn btn-success" href="<?php echo $this->autoEscape(_PATH_); ?>task/undone?id=<?php echo $this->autoEscape($this->vars['task_id']); ?>&red=list" title="Dokončeno">
                                <span class="glyphicon glyphicon-check"></span> <?php echo $this->autoEscape($this->modifier('date', $this->vars['task']['done_date'], 'j.n.Y v H:i')); ?>
                            </a>
                        <?php } ?>
                    <?php } ?>

                    <?php if(!$this->vars['user_assigned'] and $this->vars['task']['done_date'] === null) { ?>
                        <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>task/assignment/add?id=<?php echo $this->autoEscape($this->vars['task_id']); ?>&red=list" title="Přiřadit se k úkolu">
                            <span class="glyphicon glyphicon-plus"></span> 
                        </a>
                    <?php } ?>
                    <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>task/reply/add?id=<?php echo $this->autoEscape($this->vars['task_id']); ?>&red=list" title="Komentovat">
                        <span class="glyphicon glyphicon-comment"></span> <?php if(isset($this->vars['count_comments'][$this->vars['task_id']])) {  echo $this->autoEscape($this->vars['count_comments'][$this->vars['task_id']]);  } ?>
                    </a> 
                    <?php if($this->vars['admin'] or $this->vars['user_id'] === (int)$this->vars['task']['user_id']) { ?>
                        <?php if($this->vars['task']['done_date'] === null) { ?>
                            <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>task/edit?id=<?php echo $this->autoEscape($this->vars['task_id']); ?>&red=list" title="Upravit">
                                <span class="glyphicon glyphicon-edit"></span> 
                            </a>
                        <?php } ?>
                        <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>task/rm?id=<?php echo $this->autoEscape($this->vars['task_id']); ?>&red=list" title="Smazat">
                            <span class="glyphicon glyphicon-trash"></span> 
                        </a>
                    <?php } ?>
                </div>
                <?php if(isset($this->vars['tasks_assignment'],$this->vars['tasks_assignment'][$this->vars['task_id']])) { ?>
                    <span class="task-assignment" title="Přiřazení uživatelé">
                        <?php foreach($this->vars['tasks_assignment'][$this->vars['task_id']] as $this->vars['assignment']) { ?>
                            <?php if($this->vars['task']['done_date'] === null and ($this->vars['admin'] or $this->vars['user_id'] === (int)$this->vars['task']['user_id'] or (int)$this->vars['assignment']['user_id'] === $this->vars['user_id'])) { ?>
                                <a class="label label-default" href="<?php echo $this->autoEscape(_PATH_); ?>task/assignment/rm?task_id=<?php echo $this->autoEscape($this->vars['task_id']); ?>&id=<?php echo $this->autoEscape($this->vars['assignment']['user_id']); ?>&red=list">
                                    <span class="glyphicon glyphicon-user"></span> 
                                    <span class="glyphicon glyphicon-remove"></span> 
                                    <?php echo $this->autoEscape($this->vars['assignment']['nick']); ?>
                                </a>
                            <?php } else { ?>
                                <div class="label label-default">
                                    <span class="glyphicon glyphicon-user"></span>
                                    <?php echo $this->autoEscape($this->vars['assignment']['nick']); ?>
                                </div>
                            <?php } ?>
                        <?php } ?>  
                    </span>
                <?php } ?>
            </div>
        </div>
        <?php if(isset($this->vars['comments'][$this->vars['task_id']])) { ?>
            <?php foreach($this->vars['comments'][$this->vars['task_id']] as $this->vars['comment']) { ?>
                <div class="reply task panel panel-default" id="reply_<?php echo $this->autoEscape($this->vars['comment']['reply_id']); ?>">
                    <div class="panel-heading">
                        <span><strong><?php echo $this->autoEscape($this->vars['comment']['nick']); ?></strong>, <?php echo $this->autoEscape($this->modifier('date', $this->vars['comment']['posted'], 'j.n.Y v H:i')); ?></span>
                        <div class="btn-group btn-group-xs task-buttons">
                            <?php if($this->vars['admin'] or $this->vars['user_id'] === (int)$this->vars['comment']['user_id']) { ?>
                                <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>task/reply/edit?id=<?php echo $this->autoEscape($this->vars['comment']['reply_id']); ?>&red=list" title="Upravit">
                                    <span class="glyphicon glyphicon-edit"></span> 
                                </a>
                                <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>task/reply/rm?id=<?php echo $this->autoEscape($this->vars['comment']['reply_id']); ?>&red=list" title="Smazat">
                                    <span class="glyphicon glyphicon-trash"></span> 
                                </a>
                            <?php } ?>
                        </div>
                        <?php if($this->vars['comment']['editor'] !== null and $this->vars['comment']['reply_edit_date'] !== null) { ?>
                            <span title="Naposledy upraveno" class="edit-label label label-info"><span class="glyphicon glyphicon-edit"></span> <?php echo $this->autoEscape($this->modifier('date', $this->vars['comment']['reply_edit_date'], 'j.n.Y v H:i')); ?>, <?php echo $this->autoEscape($this->vars['comment']['editor']); ?></span>
                        <?php } ?>
                    </div>
                    <div class="panel-body">
                        <?php 
                        echo $bb->Parse($this->vars['comment']['text']);
                         ?>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>    
    <?php } ?>
<?php } ?>