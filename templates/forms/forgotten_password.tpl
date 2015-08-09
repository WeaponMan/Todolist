<form action="{#_PATH_ROUTE_ARGS_#}" role="form" class="form-horizontal" method="post">
    <h3>Zapomenuté heslo</h3>
    <div class="form-group">    
        <label class="col-sm-2 control-label" for="nick">Uživatelské jméno</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" name="nick" id="nick" />
        </div>
    </div>
    <div class="form-group">    
        <label class="col-sm-2 control-label" for="email">Emailová adresa</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" name="email" id="email" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-4">
            <strong>Všechny položky formuláře jsou povinné.</strong>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-4">
            <input class="btn btn-primary" type="submit" value="Odeslat" />
        </div>
    </div>
</form>