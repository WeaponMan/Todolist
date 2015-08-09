{php} 
    $this->vars['user_assigned'] = false; 
    $bb = new NBBC\BBCode(); 
    $bb->SetTagMarker("["); 
    $bb->SetAllowAmpersand(true); 
    $bb->SetEnableSmileys(false); 
    $bb->SetDetectURLs(true); 
    $bb->SetPlainMode(false); 
    $bb->SetWikiURL("http://cs.wikipedia.org/wiki/"); 
{/php}
{if isset($tasks_assignment)}
    {foreach $tasks_assignment as $assigment_user_id => $val}
        {php} $this->vars['user_assigned'] = $this->vars['assigment_user_id'] === $this->vars['current_user_id'];{/php}
    {/foreach}   
{/if}
<div style="width: 100%; height: 5px;"></div>
<div class="task panel panel-default" id="task_{$task.task_id}">
    <div class="panel-heading">
        <span class="btn btn-default active btn-xs" title="Priorita">{$task.priority|sprintf:'%+d'}</span>
        <span class="task-title">{$task.title}{if $task.due_date !== null}, <small>deadline: {$task.due_date|date:'j.n.Y v H:i'}{/if}</small></span>
        <span class="task-heading-text"><strong>{$task.nick}</strong>, {$task.create_date|date:'j.n.Y v H:i'}</span>
    </div>
    <div class="panel-body">
        {php}echo $bb->Parse($this->vars['task']['description']);{/php}
    </div>
    <div class="panel-footer">
        {if isset($task_tags)}
            <span class="task-tags" title="Tagy">
                {foreach $task_tags as $tag_id => $tag}
                    <a class="label label-default" href="{#_PATH_#}list/tag?id={$tag_id}">
                        <span class="glyphicon glyphicon-tag"></span> 
                        {$tag}
                    </a>  
                {/foreach} 
            </span>
        {/if}
        <div class="btn-group btn-group-xs task-buttons">
            {if $task.done_date === null and ($user_assigned or $admin or (int)$task.user_id === $current_user_id)}
                <a class="btn btn-default" href="{#_PATH_#}task/done?id={$task.task_id}&red=task" title="Hotový?">
                    <span class="glyphicon glyphicon-check"></span> 
                </a>
            {else}
                <a class="btn btn-success" href="{#_PATH_#}task/undone?id={$task.task_id}&red=rask" title="Dokončeno">
                    <span class="glyphicon glyphicon-check"></span> {$task.done_date|date:'j.n.Y v H:i'}
                </a>
            {/if}

            {if !$user_assigned and $task.done_date === null}
                <a class="btn btn-default" href="{#_PATH_#}task/assignment/add?id={$task.task_id}&red=task" title="Přiřadit se k úkolu">
                    <span class="glyphicon glyphicon-plus"></span> 
                </a>
            {/if}
            <a class="btn btn-default" href="{#_PATH_#}task/reply/add?id={$task.task_id}&red=task" title="Komentovat">
                <span class="glyphicon glyphicon-comment"></span> 
            </a> 
            {if $admin or $current_user_id === (int)$task.user_id}
                {if $task.done_date === null}
                    <a class="btn btn-default" href="{#_PATH_#}task/edit?id={$task.task_id}&red=task" title="Upravit">
                        <span class="glyphicon glyphicon-edit"></span> 
                    </a>
                {/if}
                <a class="btn btn-default" href="{#_PATH_#}task/rm?id={$task.task_id}&red=list" title="Smazat">
                    <span class="glyphicon glyphicon-trash"></span> 
                </a>
            {/if}
        </div>
        {if isset($tasks_assignment)}
            <span class="task-assignment" title="Přiřazení uživatelé">
                {foreach $tasks_assignment as $assignment_user_id => $nick}
                    {if $task.done_date === null and ($admin or $current_user_id === (int)$task.user_id or (int)$assignment_user_id === $current_user_id)}
                        <a class="label label-default" href="{#_PATH_#}task/assignment/rm?task_id={$task.task_id}&id={$assignment_user_id}&red=task">
                            <span class="glyphicon glyphicon-user"></span> 
                            <span class="glyphicon glyphicon-remove"></span> 
                            {$nick}
                        </a>
                    {else}
                        <div class="label label-default">
                            <span class="glyphicon glyphicon-user"></span>
                            {$nick}
                        </div>
                    {/if}
                {/foreach}  
            </span>
        {/if}
    </div>
</div>
{if isset($comments)}
    {foreach $comments as $comment}
        <div class="reply task panel panel-default" id="reply_{$comment.reply_id}">
            <div class="panel-heading">
                <span>
                    <strong>{$comment.nick}</strong>, 
                    {$comment.posted|date:'j.n.Y v H:i'}</span>
                <div class="btn-group btn-group-xs task-buttons">
                    {if $admin or $current_user_id === (int)$comment.user_id}
                        <a class="btn btn-default" href="{#_PATH_#}task/reply/edit?id={$comment.reply_id}&red=task" title="Upravit">
                            <span class="glyphicon glyphicon-edit"></span> 
                        </a>
                        <a class="btn btn-default" href="{#_PATH_#}task/reply/rm?id={$comment.reply_id}&red=task" title="Smazat">
                            <span class="glyphicon glyphicon-trash"></span> 
                        </a>
                    {/if}
                </div>
                {if $comment.editor !== null and $comment.reply_edit_date !== null}
                    <span title="Naposledy upraveno" class="edit-label label label-info"><span class="glyphicon glyphicon-edit"></span> {$comment.reply_edit_date|date:'j.n.Y v H:i'}, {$comment.editor}</span>
                {/if}
            </div>
            <div class="panel-body">
                {php}echo $bb->Parse($this->vars['comment']['text']);{/php}
            </div>
        </div>
    {/foreach}
{/if}