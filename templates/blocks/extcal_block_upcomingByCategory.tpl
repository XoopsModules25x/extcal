<table class="outer">
    <{foreach item=cat from=$block}>

    <tr class='head'>

        <td style="border-left: 4px solid #<{$cat.cat_color}>;border-top: 4px solid #<{$cat.cat_color}>;margin-left: 8px;"
            colspan='1'>
            <strong> <{$cat.cat_name}> (#<{$cat.cat_id}>)</strong>
        </td>
    </tr>

    <{foreach item=event from=$cat.events}>

    <tr class="<{cycle values=" even,odd"}>">


        <td style="border-left: 4px solid #<{$cat.cat_color}>;margin-left: 8px;">
            <{$event.formated_event_start}>
            <{if $event.formated_event_start != $event.formated_event_end}> - <{$event.formated_event_end}>
            <{/if}>
            <br>
            <a href="<{$xoops_url}>/modules/extcal/event.php?event=<{$event.event_id}>"
               title="<{$event.event_title}>">
                <{$event.event_title}>
            </a>
        </td>

    </tr>
    <{/foreach}>

    <{/foreach}>
</table>
