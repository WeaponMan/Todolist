<h2><?php echo $this->autoEscape($this->vars['pageHeading']); ?></h2>
<form class="form-horizontal" role="form" action="<?php echo $this->autoEscape(_PATH_ROUTE_ARGS_); ?>" method="post">
    <div class="form-group">    
        <label for="reply-text" class="col-sm-3 control-label">Text komentáře:</label>
        <div class="col-sm-3">
            <textarea name="text" class="form-control" id="reply-text"><?php if(isset($this->vars['text'])) {  echo $this->autoEscape($this->vars['text']);  } ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-3">
            <input class="btn btn-primary" type="submit" value="<?php echo $this->autoEscape($this->vars['submit']); ?>" />
        </div>
    </div>
</form>