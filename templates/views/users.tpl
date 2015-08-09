<h2>Uživatelé aplikace</h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Nick</th>
            <th>E-mail</th>
            <th>Datum posledního přihlášení</th>
            <th>adminem aplikace od</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        {foreach $users as $_user}
            <tr>
                <td>{$_user.nick}</td>
                <td>{$_user.email}</td>
                <td>{$_user.last_login|date:'j.n.Y v H:i'}</td>
                <td>{if $_user.app_admin_from !== null}{$_user.app_admin_from|date:'j.n.Y v H:i'}{else} - {/if}</td>
                <td>
                    <div class="btn-group btn-group-xs">
                        {if $_user.app_admin_from !== null}
                            <a class="btn btn-default" href="{#_PATH_#}admin/user/depose?id={$_user.user_id}" title="Sesadit na uživatele">
                                <span class="glyphicon glyphicon-minus"></span> 
                            </a>
                        {else}
                            <a class="btn btn-default" href="{#_PATH_#}admin/user/promote?id={$_user.user_id}" title="Povýšit na admina aplikace">
                                <span class="glyphicon glyphicon-plus"></span> 
                            </a>
                        {/if}
                        <a class="btn btn-default" href="{#_PATH_#}admin/user/rm?id={$_user.user_id}" title="Smazat uživatele">
                            <span class="glyphicon glyphicon-trash"></span> 
                        </a>
                    </div>
                </td>
            </tr>
        {/foreach}
    </tbody>
</table>
