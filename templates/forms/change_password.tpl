<form action="{#_PATH_ROUTE_ARGS_#}" role="form" class="form-horizontal" method="post">
    <h3>Změna hesla</h3>
    <div class="form-group">    
        <label class="col-sm-2 control-label" for="new_password">Nové heslo</label>
        <div class="col-sm-4">
            <input type="password" class="form-control" name="password" id="new_password" />
        </div>
    </div>
    <div class="form-group">    
        <label class="col-sm-2 control-label" for="re_new_password">Nové heslo znovu</label>
        <div class="col-sm-4">
            <input type="password" class="form-control" name="re_password" id="re_new_password" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-4">
            <strong>Všechny položky formuláře jsou povinné.</strong>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-4">
            <input class="btn btn-primary" type="submit" value="Změnit" />
        </div>
    </div>
</form>