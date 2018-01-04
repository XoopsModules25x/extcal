<{include file="db:extcal_navbar.tpl"}>

<script src='<{$smarty.const.XOOPS_URL}>/modules/extcal/assets/js/extcal_highslide.js' type="text/javascript"></script>


<table class="outer" style="border-top: none;">
    <tr class="head" style="text-align:center;" height="22px">
        <td style="width:33%;padding-top: 4px;">
            <a href="<{$xoops_url}>/modules/extcal/<{$params.file}>?<{$navig.prev.uri}>">
                &lt;&lt;&nbsp;&nbsp;<{$navig.prev.name}></a>
        </td>
        <td style="width:33%;padding-top: 4px;">
            <span style="font-weight:bold;"><{$navig.this.name}></span>
        </td>
        <td style="width:33%;padding-top: 4px;"><a href="<{$xoops_url}>/modules/extcal/<{$params.file}>?<{$navig.next.uri}>"><{$navig.next.name}>&nbsp;&nbsp;>></a>
        </td>
    </tr>
    <{foreach item=event from=$events}>
        <tr class="<{cycle values=" even,odd"}>">
            <td colspan="3" class="odd" style="vertical-align:middle;border-bottom:1px solid #CCCCCC;">

                <{$event.formated_event_start}>&nbsp;&nbsp;
                <a href="<{$xoops_url}>/modules/extcal/event.php?event=<{$event.event_id}>"
                   class="extcalTips infobulle"
                   title="">
                    <{$event.event_title}>
                </a>

                <div style="float:right; margin-left:5px;">
                    <{include file="db:extcal_buttons_event.tpl"}>
                </div>

                <div style="margin-left:0;margin-top:4px;">
                    <{$event.event_desc}>
                </div>

                <{if $event.event_picture1}>
                    <div class="highslide-gallery">
                        <a href="<{$xoops_url}>/uploads/extcal/<{$event.event_picture1}>" class="highslide"
                           onclick="return hs.expand(this)">
                            <img align="left" style="margin-right:10px;"
                                 src="<{$xoops_url}>/uploads/extcal/<{$event.event_picture1}>" height="150px">
                        </a>

                        <div class="highslide-heading"></div>
                    </div>
                <{elseif $smarty.const._EXTCAL_SHOW_NO_PICTURE}>
                    <img align=left style="margin-right:6px;"
                         src="<{$xoops_url}>/modules/extcal/assets/images/no_picture.png" height="180">
                <{/if}>

                <{if $event.event_picture2}>
                    <div class="highslide-gallery">
                        <a href="<{$xoops_url}>/uploads/extcal/<{$event.event_picture2}>" class="highslide"
                           onclick="return hs.expand(this)">
                            <img align="left" style="margin-right:10px;"
                                 src="<{$xoops_url}>/uploads/extcal/<{$event.event_picture2}>" height="150px">
                        </a>

                        <div class="highslide-heading"></div>
                    </div>
                <{elseif $smarty.const._EXTCAL_SHOW_NO_PICTURE}>
                    <img align=left style="margin-right:6px;"
                         src="<{$xoops_url}>/modules/extcal/assets/images/no_picture.png" height="180">
                <{/if}>

                <div style="float:right; background-color:#<{$event.cat.cat_color}>; border:1px solid white; margin-right:5px;">
                    &nbsp;
                </div>
                </div>
                </div>

                <!-- ------------------------------------ -->
            </td>
        </tr>
    <{/foreach}>
</table>

<{include file="db:extcal_categorie.tpl"}>

<div style="text-align:right;"><a href="<{$xoops_url}>/modules/extcal/rss.php?cat=<{$selectedCat}>"><img src="assets/images/icons/rss.gif" alt="RSS Feed"></a></div>
<{include file='db:system_notification_select.tpl'}>
