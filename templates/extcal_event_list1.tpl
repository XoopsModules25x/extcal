<table class="outer" style="border-top: none;">
    <{foreach item=event from=$events}>
        <tr class="<{cycle values=" even,odd"}>">
            <td class="odd" style="vertical-align:middle;" width='100px'>
                <{$event.formated_event_start}>&nbsp;&nbsp;
            </td>
            <td class="odd" style="vertical-align:middle;" width='100px'>
                <{$event.formated_event_end}>&nbsp;&nbsp;
            </td>
            <td class="odd" style="vertical-align:middle;" width='10px'>
                <{if $event.event_isrecur}>*<{$event.event_id}><{/if}>
            </td>
            <td class="odd" style="vertical-align:middle;">

                <{include file="db:extcal_info_bulle.tpl"}>


            </td>
            <td class="odd" style="vertical-align:middle;">
                <{include file="db:extcal_buttons_event.tpl"}>
            </td>
        </tr>
    <{/foreach}>
</table>
