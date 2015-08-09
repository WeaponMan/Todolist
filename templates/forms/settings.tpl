<div class="floating-form">
    <form action="{#_PATH_ROUTE_#}" role="form" class="form-horizontal" method="post">
        <h3>Změna hesla</h3>
        <div class="form-group">    
            <label class="col-sm-4 control-label" for="password">Současné heslo</label>
            <div class="col-sm-7">
                <input type="password" class="form-control" name="password" id="password" />
            </div>
        </div>
        <div class="form-group">    
            <label class="col-sm-4 control-label" for="new_password">Nové heslo</label>
            <div class="col-sm-7">
                <input type="password" class="form-control" name="new_password" id="new_password" />
            </div>
        </div>
        <div class="form-group">    
            <label class="col-sm-4 control-label" for="re_new_password">Nové heslo znovu</label>
            <div class="col-sm-7">
                <input type="password" class="form-control" name="re_new_password" id="re_new_password" />
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-7">
                <strong>Všechny položky formuláře jsou povinné.</strong>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-7">
                <input class="btn btn-primary" type="submit" value="Změnit" />
            </div>
        </div>
    </form>
</div>
<div class="floating-form">
    <form action="{#_PATH_ROUTE_#}" role="form" class="form-horizontal" method="post">
        <h3>Změna emailu</h3>
        <div class="form-group">    
            <label class="col-sm-4 control-label" for="email">Současný e-mail</label>
            <div class="col-sm-7">
                <input type="text" class="form-control disabled" disabled="disabled" name="email" id="email"{if isset($email)} value="{$email}"{/if} />
            </div>
        </div>
        <div class="form-group">    
            <label class="col-sm-4 control-label" for="new_email">Nový e-mail</label>
            <div class="col-sm-7">
                <input type="text" class="form-control" name="new_email" id="new_email" />
            </div>
        </div>
        <div class="form-group">    
            <label class="col-sm-4 control-label" for="re_new_email">Nový e-mail znovu</label>
            <div class="col-sm-7">
                <input type="text" class="form-control" name="re_new_email" id="re_new_email" />
            </div>
        </div>
        <div class="form-group">    
            <label class="col-sm-4 control-label" for="email_password">Heslo</label>
            <div class="col-sm-7">
                <input type="password" class="form-control" name="email_password" id="email_password" />
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-7">
                <strong>Všechny položky formuláře jsou povinné.</strong>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-7">
                <input class="btn btn-primary" type="submit" value="Změnit" />
            </div>
        </div>
    </form>
</div>