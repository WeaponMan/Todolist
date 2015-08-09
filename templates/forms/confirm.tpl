<form id="potvrzovaci" method="post" action="{#_PATH_ROUTE_ARGS_#}">
    <table>
        <tr>
            <td colspan="2"><h1>{$question}</h1></td>
            <td rowspan="2"><img src="{#_PATH_#}img/question-symbol.png" width="128" height="128" alt="{'otazník'}" /></td>
        </tr>
        <tr class="align-center">
            <td><input class="btn btn-default" type="submit" name="yes" value="{'Ano'}" /></td>
            <td><input class="btn btn-default"type="submit" name="no" value="{'Ne'}" /></td>
        </tr>
    </table>
</form>