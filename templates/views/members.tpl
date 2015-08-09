<h2>Členové listu</h2>
{if $list_members}    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nick</th>
                <th>přidán uživatelem</th>
                <th>členem od</th>
                <th>adminem od</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            {foreach $list_members as $member}
                <tr>
                    <td>{$member.user_name}</td>
                    <td>{$member.added_user_name}</td>
                    <td>{$member.member_from|date:'j.n.Y v H:i'}</td>
                    <td>{if $member.list_admin_from !== null}{$member.list_admin_from|date:'j.n.Y v H:i'}{else} - {/if}</td>
                    <td>
                        <div class="btn-group btn-group-xs">
                            {if $member.list_admin_from !== null}
                                <a class="btn btn-default" href="{#_PATH_#}list/member/depose?list_id={$current_menu_list}&id={$member.user_id}" title="Sesadit na člena">
                                    <span class="glyphicon glyphicon-minus"></span> 
                                </a>
                            {else}
                                <a class="btn btn-default" href="{#_PATH_#}list/member/promote?list_id={$current_menu_list}&id={$member.user_id}" title="Povýšit na admina">
                                    <span class="glyphicon glyphicon-plus"></span> 
                                </a>
                            {/if}
                            <a class="btn btn-default" href="{#_PATH_#}list/member/rm?list_id={$current_menu_list}&id={$member.user_id}" title="Vyloučit z listu">
                                    <span class="glyphicon glyphicon-trash"></span> 
                            </a>
                        </div>
                    </td>
                </tr>
            {/foreach}
        </tbody>
    </table>
{else}
    <h4>Nemáte v listu žádné členy.</h4>
{/if}