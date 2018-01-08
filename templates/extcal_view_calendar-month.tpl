<{include file="db:extcal_navbar.tpl"}>

<table class="outer" style="border-top: none;">
    <tr style="text-align:center;">
        <th rowspan="2">&nbsp;</th>
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
    <{foreach item=row from=$tableRows}>
    <tr>
        <th style="text-align:center; vertical-align:middle;"><a
                    href="<{$params.file}>?year=<{$row.weekInfo.year}>&amp;month=<{$row.weekInfo.month}>&amp;day=<{$row.weekInfo.day}>"><{$row.weekInfo.week}></a>
        </th>
        <{foreach item=cell from=$row.week}>
        <td class="<{if $cell.isEmpty}>even<{else}>odd<{/if}>"
            style="width:14%; height:80px; vertical-align:top;<{if $cell.isSelected}> background-color:#B6CDE4;<{/if}>">
            <{if $cell.isEmpty}>&nbsp;
            <{else}>
                <a href="<{$xoops_url}>/modules/extcal/view_day.php?year=<{$year}>&amp;month=<{$month}>&amp;day=<{$cell.number}>"><{$cell.number}></a>
                <br>
            <{/if}>

            <{foreach item=event from=$cell.events}>
                <{if $event}>

                    <{include file="db:extcal_info_bulle.tpl"}>
                    <div style="background-color:#<{$event.cat.cat_color}>; height:2px; font-size:2px;">
                        &nbsp;</div>
                <{/if}>
            <{/foreach}>
        </td>
        <{/foreach}>
    </tr>
    <{/foreach}>
</table>

<{include file="db:extcal_categorie.tpl"}>

<div style="text-align:right;"><a href="<{$xoops_url}>/modules/extcal/rss.php?cat=<{$selectedCat}>"><img src="assets/images/icons/rss.gif" alt="RSS Feed"></a></div>
<{include file='db:system_notification_select.tpl'}>
