<table class="outer">
    <{foreach item=event from=$block}>
    <tr class="<{cycle values=" even,odd"}>">
        <td>
            <a href="<{$xoops_url}>/modules/extcal/event.php?event=<{$event.event_id}>"
               title="<{$event.event_title}>"><{$event.event_title}></a>
        </td>
        <td><{$event.start}></td>
    </tr>
    <{/foreach}>
</table>
