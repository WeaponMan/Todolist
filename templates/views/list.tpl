<div id="list-heading">
    {if isset($list_name)}<h3>{$list_name}</h3>{/if}
    <div class="btn-group btn-group-sm">
        <a class="btn btn-default" href="{#_PATH_#}task/add?id={$current_menu_list}">
            <span class="glyphicon glyphicon-plus-sign" title="Přidat úkol"></span>
        </a>
        {if $admin or $owner}  
            <a class="btn btn-default" href="{#_PATH_#}list/member/add?id={$current_menu_list}">
                <span class="glyphicon glyphicon-plus" title="Přidat člena"></span>
            </a>
            <a class="btn btn-default" href="{#_PATH_#}list/members?id={$current_menu_list}">
                <span class="glyphicon glyphicon-user" title="Členové listu"></span>
            </a>
        {/if}   
        {if $owner}
            <a class="btn btn-default" href="{#_PATH_#}list/edit?id={$current_menu_list}">
                <span class="glyphicon glyphicon glyphicon-edit" title="Upravit list"></span>
            </a>
            <a class="btn btn-default" href="{#_PATH_#}list/rm?id={$current_menu_list}">
                <span class="glyphicon glyphicon glyphicon-trash" title="Smazat list"></span>
            </a>
        {else}
            <a class="btn btn-default" href="{#_PATH_#}list/member/rm?list_id={$current_menu_list}&id={$user_id}&red=all_list">
                <span class="glyphicon glyphicon glyphicon-minus" title="Odejít z listu"></span>
            </a>
        {/if}
    </div>
</div>
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
    $this->vars['user_assigned'] = false;
        {/php}
        {if isset($tasks_assignment,$tasks_assignment.$task_id)}
            {foreach $tasks_assignment.$task_id as $assignment}
                {php} $this->vars['user_assigned'] = (int)$this->vars['assignment']['user_id'] === $this->vars['user_id'];{/php}
            {/foreach}   
        {/if}
        <div class="task panel panel-default" id="task_{$task.task_id}">
            <div class="panel-heading">
                <span class="btn btn-default active btn-xs" title="Priorita">{$task.priority|sprintf:'%+d'}</span>
                <span class="task-title"><a href="{#_PATH_#}task?id={$task.task_id}">{$task.title}</a>{if $task.due_date !== null}, <small>deadline: {$task.due_date|date:'j.n.Y v H:i'}{/if}</small></span>
                {if $task.editor !== null and $task.task_edit_date !== null}
                    <span title="Naposledy upraveno" class="t-edit-label label label-info"><span class="glyphicon glyphicon-edit"></span> {$task.task_edit_date|date:'j.n.Y v H:i'}, {$task.editor}</span>
                {/if}
                <span class="task-heading-text"><strong>{$task.nick}</strong>, {$task.create_date|date:'j.n.Y v H:i'}</span>
            </div>
            <div class="panel-body">
                {php}
                    echo $bb->Parse($this->vars['task']['description']);
                {/php}
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
                    {if ($user_assigned or $admin or (int)$task.user_id === $user_id)}
                        {if $task.done_date === null}
                            <a class="btn btn-default" href="{#_PATH_#}task/done?id={$task_id}&red=list" title="Hotový?">
                                <span class="glyphicon glyphicon-check"></span> 
                            </a>
                        {else}
                            <a class="btn btn-success" href="{#_PATH_#}task/undone?id={$task_id}&red=list" title="Dokončeno">
                                <span class="glyphicon glyphicon-check"></span> {$task.done_date|date:'j.n.Y v H:i'}
                            </a>
                        {/if}
                    {/if}

                    {if !$user_assigned and $task.done_date === null}
                        <a class="btn btn-default" href="{#_PATH_#}task/assignment/add?id={$task_id}&red=list" title="Přiřadit se k úkolu">
                            <span class="glyphicon glyphicon-plus"></span> 
                        </a>
                    {/if}
                    <a class="btn btn-default" href="{#_PATH_#}task/reply/add?id={$task_id}&red=list" title="Komentovat">
                        <span class="glyphicon glyphicon-comment"></span> {if isset($count_comments.$task_id)}{$count_comments.$task_id}{/if}
                    </a> 
                    {if $admin or $user_id === (int)$task.user_id}
                        {if $task.done_date === null}
                            <a class="btn btn-default" href="{#_PATH_#}task/edit?id={$task_id}&red=list" title="Upravit">
                                <span class="glyphicon glyphicon-edit"></span> 
                            </a>
                        {/if}
                        <a class="btn btn-default" href="{#_PATH_#}task/rm?id={$task_id}&red=list" title="Smazat">
                            <span class="glyphicon glyphicon-trash"></span> 
                        </a>
                    {/if}
                </div>
                {if isset($tasks_assignment,$tasks_assignment.$task_id)}
                    <span class="task-assignment" title="Přiřazení uživatelé">
                        {foreach $tasks_assignment.$task_id as $assignment}
                            {if $task.done_date === null and ($admin or $user_id === (int)$task.user_id or (int)$assignment.user_id === $user_id)}
                                <a class="label label-default" href="{#_PATH_#}task/assignment/rm?task_id={$task_id}&id={$assignment.user_id}&red=list">
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
        {if isset($comments.$task_id)}
            {foreach $comments.$task_id as $comment}
                <div class="reply task panel panel-default" id="reply_{$comment.reply_id}">
                    <div class="panel-heading">
                        <span><strong>{$comment.nick}</strong>, {$comment.posted|date:'j.n.Y v H:i'}</span>
                        <div class="btn-group btn-group-xs task-buttons">
                            {if $admin or $user_id === (int)$comment.user_id}
                                <a class="btn btn-default" href="{#_PATH_#}task/reply/edit?id={$comment.reply_id}&red=list" title="Upravit">
                                    <span class="glyphicon glyphicon-edit"></span> 
                                </a>
                                <a class="btn btn-default" href="{#_PATH_#}task/reply/rm?id={$comment.reply_id}&red=list" title="Smazat">
                                    <span class="glyphicon glyphicon-trash"></span> 
                                </a>
                            {/if}
                        </div>
                        {if $comment.editor !== null and $comment.reply_edit_date !== null}
                            <span title="Naposledy upraveno" class="edit-label label label-info"><span class="glyphicon glyphicon-edit"></span> {$comment.reply_edit_date|date:'j.n.Y v H:i'}, {$comment.editor}</span>
                        {/if}
                    </div>
                    <div class="panel-body">
                        {php}
                        echo $bb->Parse($this->vars['comment']['text']);
                        {/php}
                    </div>
                </div>
            {/foreach}
        {/if}    
    {/foreach}
{/if}