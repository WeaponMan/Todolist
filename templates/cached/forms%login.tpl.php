<form action="<?php echo $this->autoEscape(_PATH_); ?>" role="form" class="form-horizontal" method="post" id="login-form">
    <h2>Přihlášení</h2>
    <div class="form-group">    
        <label for="nick" class="col-sm-5 control-label">E-mail/Uživatelské jméno</label>
        <div class="col-sm-7">
            <input type="text" name="nick" class="form-control" id="nick" <?php if(isset($this->vars['nick'])) { ?>value="<?php echo $this->autoEscape($this->vars['nick']); ?>" <?php } ?>/>
        </div>
    </div>
    <div class="form-group">    
        <label class="col-sm-5 control-label" for="password">Heslo</label>
        <div class="col-sm-7">
            <input type="password" class="form-control" name="password" id="password" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-5 ie-padding">
            <a class="col-sm-12" href="<?php echo $this->autoEscape(_PATH_); ?>registration">Registrace</a>
            <a class="col-sm-12" href="<?php echo $this->autoEscape(_PATH_); ?>password/forgotten">Zapomenuté heslo?</a>
        </div>
        <div class="col-sm-6">
            <input class="btn btn-primary" type="submit" value="Přihlásit" />
        </div>
    </div>
</form>