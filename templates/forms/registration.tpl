<h2>Registrace</h2>
<form class="form-horizontal" action="{#_PATH_ROUTE_ARGS_#}" method="post" id="registration">
    <div class="form-group">    
        <label for="nick" class="col-sm-2 control-label">Uživatelské jméno:</label>
        <div class="col-sm-3">
            <input type="text" name="nick" class="form-control" id="nick" {if isset($nick)}value="{$nick}" {/if}/>
        </div>
    </div>
    <div class="form-group">    
        <label for="reg-password" class="col-sm-2 control-label">Heslo:</label>
        <div class="col-sm-3">
            <input type="password" name="password" class="form-control" id="reg-password" />
        </div>
    </div>
    <div class="form-group">    
        <label for="reg-re-password" class="col-sm-2 control-label">Heslo znovu:</label>
        <div class="col-sm-3">
            <input type="password" name="re-password" class="form-control" id="reg-re-password" />
        </div>
    </div>      
    <div class="form-group">    
        <label for="email" class="col-sm-2 control-label">E-mail:</label>
        <div class="col-sm-3">
            <input type="text" name="email" class="form-control" id="email" {if isset($email)}value="{$email}" {/if}/>
        </div>
    </div>
    <div class="form-group">    
        <label for="re-email" class="col-sm-2 control-label">E-mail znovu:</label>
        <div class="col-sm-3">
            <input type="text" name="re-email" class="form-control" id="re-email" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-3">
            <strong>Všechny položky formuláře jsou povinné.</strong>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-4">
            <input class="btn btn-primary" type="submit" value="Registrovat" />
        </div>
    </div>
</form>