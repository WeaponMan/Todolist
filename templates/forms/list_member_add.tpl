<h2>Přidání uživatele do listu</h2>
<form class="form-horizontal" role="form" action="{#_PATH_ROUTE_ARGS_#}" method="post" id="list-member-add-form">
    <div class="form-group">    
        <label for="list-member-add-name" class="col-sm-3 control-label"><span class="required">* </span>Jméno uživatele nebo email:</label>
        <div class="col-sm-3">
            <input type="text" name="name" class="form-control" id="list-member-add-name" {if isset($name)}value="{$name}" {/if}/>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-3">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="admin" id="list-member-add-admin" /> Administrátor listu
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-4">
            <strong><span class="required">* </span>Položky formuláře označené hvězdičkou jsou povinné.</strong>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-3">
            <input class="btn btn-primary" type="submit" value="Přidat" />
        </div>
    </div>
</form>