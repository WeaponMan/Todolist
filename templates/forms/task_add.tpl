<h2>{$pageHeading}</h2>
<form class="form-horizontal" action="{#_PATH_ROUTE_ARGS_#}" method="post" id="task-add-form">
    <div class="form-group">    
        <label for="task-add-title" class="col-sm-2 control-label"><span class="required">* </span>Název úkolu:</label>
        <div class="col-sm-3">
            <input type="text" name="title" class="form-control" id="task-add-title" {if isset($title)}value="{$title}" {/if}/>
        </div>
    </div>
    <div class="form-group">    
        <label for="task-add-title" class="col-sm-2 control-label"><span class="required">* </span>Popis úkolu:</label>
        <div class="col-sm-3">
            <textarea name="description" class="form-control" id="task-add-description">{if isset($description)}{$description}{/if}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="priority"><span class="required">* </span>Priorita:</label>
        <div class="col-sm-3">
            <input type="number" class="form-control"name="priority" {if isset($priority)}value="{$priority}" {else}value="0" {/if}/>
        </div>
    </div>
    <div class="form-group">    
        <label for="task-add-due-date" class="col-sm-2 control-label">Datum dokončení:</label>
        <div class="col-sm-3">
            <input title="Například: {$current_date|date:'j.n.Y H:i'}" type="text" name="due_date" class="form-control" id="task-due-date" {if isset($due_date)}value="{$due_date}" {/if}/>
        </div>
    </div>
    <div class="form-group">    
        <label for="task-add-tags" class="col-sm-2 control-label">Tagy:</label>
        <div class="col-sm-3">
            <input type="text" name="tags" class="form-control" id="task-add-tags" {if isset($tags)}value="{$tags}" {/if}/>
        </div>
    </div>
    <div class="form-group">    
        <label for="task-add-assignments" class="col-sm-2 control-label">Přiřadit uživatele:</label>
        <div class="col-sm-3">
            <select name="user_assignments" id="task-add-assignments" class="form-control" multiple>
                {foreach $list_assigments as $assignment}
                    {if isset($user_assignments)}
                        {if is_array($user_assignments)}
                            <option {if in_array($assignment.user_id, $user_assignments)}selected {/if}value="{$assignment.user_id}">{$assignment.nick}</option>
                        {else}
                            <option {if $user_assignments === $assignment.user_id}selected {/if}value="{$assignment.user_id}">{$assignment.nick}</option>
                        {/if}
                    {else}
                        <option value="{$assignment.user_id}">{$assignment.nick}</option>
                    {/if}
                {/foreach}
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
            <input class="btn btn-primary" type="submit" value="{$submit}" />
        </div>
    </div>
</form>