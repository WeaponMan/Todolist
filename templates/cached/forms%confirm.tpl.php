<form id="potvrzovaci" method="post" action="<?php echo $this->autoEscape(_PATH_ROUTE_ARGS_); ?>">
    <table>
        <tr>
            <td colspan="2"><h1><?php echo $this->autoEscape($this->vars['question']); ?></h1></td>
            <td rowspan="2"><img src="<?php echo $this->autoEscape(_PATH_); ?>img/question-symbol.png" width="128" height="128" alt="<?php echo $this->autoEscape($this->default_fn('otazník')); ?>" /></td>
        </tr>
        <tr class="align-center">
            <td><input class="btn btn-default" type="submit" name="yes" value="<?php echo $this->autoEscape($this->default_fn('Ano')); ?>" /></td>
            <td><input class="btn btn-default"type="submit" name="no" value="<?php echo $this->autoEscape($this->default_fn('Ne')); ?>" /></td>
        </tr>
    </table>
</form>