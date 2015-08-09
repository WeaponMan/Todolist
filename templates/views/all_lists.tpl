{if empty($tasks)}
    <h4>Nejsou zde žádné úkoly.</h4>
{else}    
   {php}
        $bb = new NBBC\BBCode(); 
        $bb->SetTagMarker("["); 
        $bb->SetAllowAmpersand(true); 
        $bb->SetEnableSmileys(false); 
        $bb->SetDetectURLs(true); 
        $bb->SetPlainMode(false); 
        $bb->SetWikiURL("http://cs.wikipedia.org/wiki/"); 
    {/php}
    {foreach $tasks as $task}
        {php}        
    $this->vars['task_id'] = $this->vars['task']['task_id'];
    $this->vars['list_id'] = $this->vars['task']['list_id'];
    $this->vars['user_assigned'] = false;
        {/php}
        {if isset($tasks_assignment,$tasks_assignment.$task_id)}
            {foreach $tasks_assignment.$task_id as $assignment}
                {php} $this->vars['user_assigned'] = (int)$this->vars['assignment']['user_id'] === $this->vars['user_id'];{/php}
            {/foreach}   
        {/if}
        <div class="space"></div>
        <div class="task panel panel-default" id="task_{$task.task_id}">
            <div class="panel-heading">
                <span class="btn btn-default active btn-xs" title="Priorita">{$task.priority|sprintf:'%+d'}</span>
                <span class="task-title"><a href="{#_PATH_#}task?id={$task.task_id}">{$task.title}</a>{if $task.due_date !== null}, <small>deadline: {$task.due_date|date:'j.n.Y v H:i'}{/if}</small></span>
                <a href="{#_PATH_#}list?id={$list_id}" class="label label-primary list-label" title="Z listu"><span class="glyphicon glyphicon-list"></span> {$display_lists.$list_id.name}</a>
                <span class="task-heading-text"><strong>{$task.nick}</strong>, {$task.create_date|date:'j.n.Y v H:i'}</span>
            </div>
            <div class="panel-body">
                {php}echo $bb->Parse($this->vars['task']['description']);{/php}
            </div>
            <div class="panel-footer">
                {if isset($task_tags,$task_tags.$task_id)}
                    <span class="task-tags" title="Tagy">
                        {foreach $task_tags.$task_id as $tag}
                            <a class="label label-default" href="{#_PATH_#}list/tag?id={$tag.tag_id}">
                                <span class="glyphicon glyphicon-tag"></span> 
                                {$tag.tag}
                            </a>  
                        {/foreach} 
                    </span>
                {/if}
                <div class="btn-group btn-group-xs task-buttons">
                    {if ($user_assigned or $display_lists.$list_id.admin or (int)$task.user_id === $user_id)}
                        {if $task.done_date === null}
                            <a class="btn btn-default" href="{#_PATH_#}task/done?id={$task_id}&red=all_list" title="Hotový?">
                                <span class="glyphicon glyphicon-check"></span> 
                            </a>
                        {else}
                            <a class="btn btn-success" href="{#_PATH_#}task/undone?id={$task_id}&red=all_list" title="Dokončeno">
                                <span class="glyphicon glyphicon-check"></span> {$task.done_date|date:'j.n.Y v H:i'}
                            </a>
                        {/if}
                    {/if}

                    {if !$user_assigned and $task.done_date === null}
                        <a class="btn btn-default" href="{#_PATH_#}task/assignment/add?id={$task_id}&red=all_list" title="Přiřadit se k úkolu">
                            <span class="glyphicon glyphicon-plus"></span> 
                        </a>
                    {/if}
                    <a class="btn btn-default" href="{#_PATH_#}task?id={$task_id}" title="Zobrazit komentáře">
                        <span class="glyphicon glyphicon-comment"></span> {if isset($count_comments.$task_id)}{$count_comments.$task_id}{/if}
                    </a> 
                    {if $display_lists.$list_id.admin or $user_id === (int)$task.user_id}
                        {if $task.done_date === null}
                            <a class="btn btn-default" href="{#_PATH_#}task/edit?id={$task_id}&red=all_list" title="Upravit">
                                <span class="glyphicon glyphicon-edit"></span> 
                            </a>
                        {/if}
                        <a class="btn btn-default" href="{#_PATH_#}task/rm?id={$task_id}&red=all_list" title="Smazat">
                            <span class="glyphicon glyphicon-trash"></span> 
                        </a>
                    {/if}
                </div>
                {if isset($tasks_assignment,$tasks_assignment.$task_id)}
                    <span class="task-assignment" title="Přiřazení uživatelé">
                        {foreach $tasks_assignment.$task_id as $assignment}
                            {if $task.done_date === null and ($display_lists.$list_id.admin or $user_id === (int)$task.user_id or (int)$assignment.user_id === $user_id)}
                                <a class="label label-default" href="{#_PATH_#}task/assignment/rm?task_id={$task_id}&id={$assignment.user_id}&red=all_list">
                                    <span class="glyphicon glyphicon-user"></span> 
                                    <span class="glyphicon glyphicon-remove"></span> 
                                    {$assignment.nick}
                                </a>
                            {else}
                                <div class="label label-default">
                                    <span class="glyphicon glyphicon-user"></span>
                                    {$assignment.nick}
                                </div>
                            {/if}
                        {/foreach}  
                    </span>
                {/if}
            </div>
        </div>
    {/foreach}
{/if}