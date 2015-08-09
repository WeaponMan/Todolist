<h3>Listy v aplikaci</h3>
{if $lists}
<table class="table table-striped">
    <thead>
        <tr>
            <th>Jméno</th>
            <th>Vlastník</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        {foreach $lists as $list}
            <tr>
                <td><a href="{#_PATH_#}list?id={$list.list_id}">{$list.name}</a></td>
                <td>{$list.nick}</td>
                <td>
                    <div class="btn-group btn-group-xs">
                        <a class="btn btn-default" href="{#_PATH_#}list/members/?id={$list.list_id}" title="Členové listu">
                            <span class="glyphicon glyphicon-user"></span> 
                        </a>
                        <a class="btn btn-default" href="{#_PATH_#}admin/list/rm?id={$list.list_id}" title="Smazat list">
                            <span class="glyphicon glyphicon-trash"></span> 
                        </a>
                    </div>
                </td>
            </tr>
        {/foreach}
    </tbody>
</table>
{else}
    <h4>V aplikaci není ani jeden list.</h4>
{/if}