<h2>{$heading}</h2>
<form class="form-horizontal" role="form" action="{#_PATH_ROUTE_ARGS_#}" method="post" id="list-add-form">
    <div class="form-group">
        <label for="list-add-name" class="col-sm-2 control-label">Jméno listu:</label>
        <div class="col-sm-3">
            <input class="form-control" type="text" name="list-name" id="list-add-name" {if isset($list_name)}value="{$list_name}" {/if}/>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-3">
            <input class="btn btn-primary" type="submit" value="{$submit}" />
        </div>
    </div>
</form>