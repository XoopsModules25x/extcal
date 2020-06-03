<{include file="db:extcal_navbar.tpl"}>


<table class="outer" style="border-top: none;">
    <tr style="text-align:center;">
        <td class="even" style="width:33%;">
            <a href="<{$xoops_url}>/modules/extcal/<{$params.file}>?<{$navig.prev.uri}>">
                &lt;&lt;&nbsp;&nbsp;<{$navig.prev.name}>
            </a>
        </td>
        <td class="even" style="width:33%;"><span style="font-weight:bold;"><{$navig.this.name}></span>
        </td>
        <td class="even" style="width:33%;"><a href="<{$xoops_url}>/modules/extcal/<{$params.file}>?<{$navig.next.uri}>"><{$navig.next.name}>&nbsp;&nbsp;>></a>
        </td>
    </tr>

</table>

<hr>
<table class="outer" style="border: 1px;">

    <tr style="text-align:left;">
        <td class='<{$trancheHeure.class}>' style="border: 1px solid #808080;" width='50px'>

        </td>
    </tr>


    <{foreach item=trancheHeure key=itemnum from=$agenda}>
        <{if $itemnum==0}>
            <tr style="text-align:left;">
                <th class='<{$trancheHeure.class}>' style="border: 1px solid #808080;" width='50px'>

                </th>
                <{foreach item=jour from=$trancheHeure.jours}>
                    <th class='<{$trancheHeure.class}>' style="border: 1px solid #808080;"
                        width='<{$params.colJourWidth}>%'>
                        <{$jour.jour}><br>
                        <{$jour.caption}>
                    </th>
                <{/foreach}>
            </tr>
        <{/if}>
        <tr style="text-align:left;">
            <td class='<{$trancheHeure.class}>' style="border: 1px solid #808080;" width='50px'>
                <{$trancheHeure.caption}>
            </td>
            <{foreach item=jour from=$trancheHeure.jours}>
                <td class='<{$trancheHeure.class}>' style="border: 1px solid #808080;" width='<{$params.colJourWidth}>%'
                        <{$jour.bg}>>
                    <{foreach item=event from=$jour.events}>
                        <{include file="db:extcal_info_bulle.tpl"}>

                    <{/foreach}>
                </td>
            <{/foreach}>
        </tr>
    <{/foreach}>
</table>

<{include file="db:extcal_categorie.tpl"}>

<div style="text-align:right;"><a href="<{$xoops_url}>/modules/extcal/rss.php?cat=<{$selectedCat}>"><img
                src="assets/images/icons/rss.gif" alt="RSS Feed"></a></div>
<{include file='db:system_notification_select.tpl'}>
