<table class="outer">
    <{foreach item=event from=$block}>
    <tr class="<{cycle values=" even,odd"}>">
        <td>
            <a href="<{$xoops_url}>/modules/extcal/event.php?event=<{$event.event_id}>"
               title="<{$event.event_title}>"><{$event.event_title}></a>
        </td>
        <td><{$event.formated_event_start}>
            <{if $event.formated_event_start != $event.formated_event_end}> - <{$event.formated_event_end}>
            <{/if}>
        </td>
    </tr>
    <{/foreach}>
</table>
