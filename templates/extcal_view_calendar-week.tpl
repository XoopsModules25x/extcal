<{include file="db:extcal_navbar.tpl"}>

<table class="outer" style="border-top: none;">
    <tr style="text-align:center;">
        <td colspan="2" class="even"><a href="<{$xoops_url}>/modules/extcal/<{$params.file}>?<{$navig.prev.uri}>">
                &lt;&lt; <{$navig.prev.name}></a></td>
        <td colspan="3" class="even"><span style="font-weight:bold;"><{$navig.this.name}></span>
        </td>
        <td colspan="2" class="even"><a href="<{$xoops_url}>/modules/extcal/<{$params.file}>?<{$navig.next.uri}>"><{$navig.next.name}>
                >></a></td>
    </tr>
    <tr style="text-align:center;" class="head">
        <{foreach item=weekdayName from=$weekdayNames}>
            <td><{$weekdayName}></td>
        <{/foreach}>
    </tr>
    <tr>
        <{foreach item=day from=$week}>
            <td class="<{if $day.isEmpty}>even<{else}>odd<{/if}>"
                style="width:14%; height:80px; vertical-align:top;<{if $day.isSelected}> background-color:#B6CDE4;<{/if}>">
                <{if $day.isEmpty}>&nbsp;<{else}>
                    <a href="<{$xoops_url}>/modules/extcal/view_day.php?year=<{$day.year}>&amp;month=<{$day.month}>&amp;day=<{$day.dayNumber}>"><{$day.dayNumber}></a>
                <{/if}>
                <br>
                <{foreach item=event from=$day.events}>
                    <{if $event}>

                        <{include file="db:extcal_info_bulle.tpl"}>
                        <div style="background-color:#<{$event.cat.cat_color}>; height:2px; font-size:2px;">
                            &nbsp;</div>
                    <{/if}>
                <{/foreach}>
            </td>
        <{/foreach}>
    </tr>
</table>

<{include file="db:extcal_categorie.tpl"}>

<div style="text-align:right;"><a href="<{$xoops_url}>/modules/extcal/rss.php?cat=<{$selectedCat}>"><img src="assets/images/icons/rss.gif" alt="RSS Feed"></a></div>
<{include file='db:system_notification_select.tpl'}>
