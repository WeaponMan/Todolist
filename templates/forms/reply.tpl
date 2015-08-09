<h2>{$pageHeading}</h2>
<form class="form-horizontal" role="form" action="{#_PATH_ROUTE_ARGS_#}" method="post">
    <div class="form-group">    
        <label for="reply-text" class="col-sm-3 control-label">Text komentáře:</label>
        <div class="col-sm-3">
            <textarea name="text" class="form-control" id="reply-text">{if isset($text)}{$text}{/if}</textarea>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-3">
            <input class="btn btn-primary" type="submit" value="{$submit}" />
        </div>
    </div>
</form>