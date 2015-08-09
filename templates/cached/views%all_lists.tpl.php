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
    $this->vars['list_id'] = $this->vars['task']['list_id'];
    $this->vars['user_assigned'] = false;
         ?>
        <?php if(isset($this->vars['tasks_assignment'],$this->vars['tasks_assignment'][$this->vars['task_id']])) { ?>
            <?php foreach($this->vars['tasks_assignment'][$this->vars['task_id']] as $this->vars['assignment']) { ?>
                <?php  $this->vars['user_assigned'] = (int)$this->vars['assignment']['user_id'] === $this->vars['user_id']; ?>
            <?php } ?>   
        <?php } ?>
        <div class="space"></div>
        <div class="task panel panel-default" id="task_<?php echo $this->autoEscape($this->vars['task']['task_id']); ?>">
            <div class="panel-heading">
                <span class="btn btn-default active btn-xs" title="Priorita"><?php echo $this->autoEscape($this->modifier('sprintf', $this->vars['task']['priority'], '%+d')); ?></span>
                <span class="task-title"><a href="<?php echo $this->autoEscape(_PATH_); ?>task?id=<?php echo $this->autoEscape($this->vars['task']['task_id']); ?>"><?php echo $this->autoEscape($this->vars['task']['title']); ?></a><?php if($this->vars['task']['due_date'] !== null) { ?>, <small>deadline: <?php echo $this->autoEscape($this->modifier('date', $this->vars['task']['due_date'], 'j.n.Y v H:i'));  } ?></small></span>
                <a href="<?php echo $this->autoEscape(_PATH_); ?>list?id=<?php echo $this->autoEscape($this->vars['list_id']); ?>" class="label label-primary list-label" title="Z listu"><span class="glyphicon glyphicon-list"></span> <?php echo $this->autoEscape($this->vars['display_lists'][$this->vars['list_id']]['name']); ?></a>
                <span class="task-heading-text"><strong><?php echo $this->autoEscape($this->vars['task']['nick']); ?></strong>, <?php echo $this->autoEscape($this->modifier('date', $this->vars['task']['create_date'], 'j.n.Y v H:i')); ?></span>
            </div>
            <div class="panel-body">
                <?php echo $bb->Parse($this->vars['task']['description']); ?>
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
                    <?php if(($this->vars['user_assigned'] or $this->vars['display_lists'][$this->vars['list_id']]['admin'] or (int)$this->vars['task']['user_id'] === $this->vars['user_id'])) { ?>
                        <?php if($this->vars['task']['done_date'] === null) { ?>
                            <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>task/done?id=<?php echo $this->autoEscape($this->vars['task_id']); ?>&red=all_list" title="Hotový?">
                                <span class="glyphicon glyphicon-check"></span> 
                            </a>
                        <?php } else { ?>
                            <a class="btn btn-success" href="<?php echo $this->autoEscape(_PATH_); ?>task/undone?id=<?php echo $this->autoEscape($this->vars['task_id']); ?>&red=all_list" title="Dokončeno">
                                <span class="glyphicon glyphicon-check"></span> <?php echo $this->autoEscape($this->modifier('date', $this->vars['task']['done_date'], 'j.n.Y v H:i')); ?>
                            </a>
                        <?php } ?>
                    <?php } ?>

                    <?php if(!$this->vars['user_assigned'] and $this->vars['task']['done_date'] === null) { ?>
                        <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>task/assignment/add?id=<?php echo $this->autoEscape($this->vars['task_id']); ?>&red=all_list" title="Přiřadit se k úkolu">
                            <span class="glyphicon glyphicon-plus"></span> 
                        </a>
                    <?php } ?>
                    <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>task?id=<?php echo $this->autoEscape($this->vars['task_id']); ?>" title="Zobrazit komentáře">
                        <span class="glyphicon glyphicon-comment"></span> <?php if(isset($this->vars['count_comments'][$this->vars['task_id']])) {  echo $this->autoEscape($this->vars['count_comments'][$this->vars['task_id']]);  } ?>
                    </a> 
                    <?php if($this->vars['display_lists'][$this->vars['list_id']]['admin'] or $this->vars['user_id'] === (int)$this->vars['task']['user_id']) { ?>
                        <?php if($this->vars['task']['done_date'] === null) { ?>
                            <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>task/edit?id=<?php echo $this->autoEscape($this->vars['task_id']); ?>&red=all_list" title="Upravit">
                                <span class="glyphicon glyphicon-edit"></span> 
                            </a>
                        <?php } ?>
                        <a class="btn btn-default" href="<?php echo $this->autoEscape(_PATH_); ?>task/rm?id=<?php echo $this->autoEscape($this->vars['task_id']); ?>&red=all_list" title="Smazat">
                            <span class="glyphicon glyphicon-trash"></span> 
                        </a>
                    <?php } ?>
                </div>
                <?php if(isset($this->vars['tasks_assignment'],$this->vars['tasks_assignment'][$this->vars['task_id']])) { ?>
                    <span class="task-assignment" title="Přiřazení uživatelé">
                        <?php foreach($this->vars['tasks_assignment'][$this->vars['task_id']] as $this->vars['assignment']) { ?>
                            <?php if($this->vars['task']['done_date'] === null and ($this->vars['display_lists'][$this->vars['list_id']]['admin'] or $this->vars['user_id'] === (int)$this->vars['task']['user_id'] or (int)$this->vars['assignment']['user_id'] === $this->vars['user_id'])) { ?>
                                <a class="label label-default" href="<?php echo $this->autoEscape(_PATH_); ?>task/assignment/rm?task_id=<?php echo $this->autoEscape($this->vars['task_id']); ?>&id=<?php echo $this->autoEscape($this->vars['assignment']['user_id']); ?>&red=all_list">
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
    <?php } ?>
<?php } ?>