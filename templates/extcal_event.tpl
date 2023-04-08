<script src='<{$smarty.const.XOOPS_URL}>/modules/extcal/assets/js/extcal_highslide.js' type="text/javascript"></script>

<{include file="db:extcal_navbar.tpl"}>

<table class="outer">
    <tr>
        <th colspan="3" style="font-size:1.2em;">
            <div style="float:left;">
                <table>
                    <tr>
                        <td class="head" style="background-color:#<{$event.cat.cat_color|default:false}>; width:5px;"></td>
                        <td style="width:200px;"><{$event.cat.cat_name|default:false}></td>
                    </tr>
                </table>
            </div>
            
<{*            <div style="text-align:right;">*}>
<{*            <a href="<{$xoops_url}>/modules/extcal/print.php?event=<{$event.event_id}>">*}>
<{*            <img src="<{$smarty.const._EXTCAL_PATH_ICONS16}>/printer.png">*}>
<{*            </a>*}>
<{*            <{if $isAdmin || $canEdit}>*}>
<{*            <a href="edit_event.php?event=<{$event.event_id}>">*}>
<{*            <img src="<{$smarty.const._EXTCAL_PATH_ICONS16}>/edit.png">*}>
<{*            </a><{/if}>*}>
<{*            <{if $isAdmin}>*}>
<{*            <a href="admin/event.php?op=delete&event_id=<{$event.event_id}>">*}>
<{*            <img src="<{$smarty.const._EXTCAL_PATH_ICONS16}>/delete.png">*}>
<{*            </a>*}>
<{*            <{/if}>*}>
<{*            </div>*}>
            
        </th>
    </tr>
    <tr>
        <td colspan="3" class="odd">

            <{if $showPicture|default:false == 1}>
			<{if $event.event_picture1|default:false}>
                <div class="highslide-gallery">
                    <a href="<{$xoops_url}>/uploads/extcal/<{$event.event_picture1}>" class="highslide"
                       onclick="return hs.expand(this)">
                        <img style="text-align:left;margin-right:10px;"
                             alt='<{$event.event_picture1}>' title='<{$event.event_picture1}>'
                             src="<{$xoops_url}>/uploads/extcal/<{$event.event_picture1}>" height="150px">
                    </a>

                    <div class="highslide-heading"></div>
                </div>
            <{elseif $smarty.const._EXTCAL_SHOW_NO_PICTURE}>
                <img style="text-align:left;margin-right:6px;"
                     src="<{$xoops_url}>/modules/extcal/assets/images/no_picture.png" height="180"
                     alt='<{$smarty.const._EXTCAL_SHOW_NO_PICTURE}>' title='<{$smarty.const._EXTCAL_SHOW_NO_PICTURE}>'>
            <{/if}>

            <{if $event.event_picture2|default:false}>
                <div class="highslide-gallery">
                    <a href="<{$xoops_url}>/uploads/extcal/<{$event.event_picture2}>" class="highslide"
                       onclick="return hs.expand(this)">
                        <img style="text-align:left;margin-right:10px;"
                             alt='<{$event.event_picture2}>' title='<{$event.event_picture2}>'
                             src="<{$xoops_url}>/uploads/extcal/<{$event.event_picture2}>" height="150px">
                    </a>

                    <div class="highslide-heading"></div>
                </div>
            <{elseif $smarty.const._EXTCAL_SHOW_NO_PICTURE}>
                <img style="text-align:left;margin-right:6px;"
                     src="<{$xoops_url}>/modules/extcal/assets/images/no_picture.png" height="180"
                     alt='<{$smarty.const._EXTCAL_SHOW_NO_PICTURE}>' title='<{$smarty.const._EXTCAL_SHOW_NO_PICTURE}>'>
            <{/if}>
            <{/if}>


            <div style="font-size:20px;font-weight:bold;width:280px;overflow:hidden;"><u><{$event.event_title|default:false}></u>
            </div>
            <div style="margin-right:5px;"><br><span
                        style="text-decoration: underline;"><strong><{$smarty.const._MD_EXTCAL_LOCATION_DATE}> </strong></span><br><{$event.formated_event_start|default:false}>
                <br><br>

                <{if $event.event_desc|default:'' != ''}>
                    <span style="text-decoration: underline;"><strong><{$smarty.const._MD_EXTCAL_LOCATION_DESCRIPTION}>
                            :</strong></span>
                    <br>
                    <{$event_desc}>
                    <br>
                    <br>
                <{/if}>

                <{if $event.event_address|default:'' != ''}>
					<{if $showAddress == 1}>
                    <span style="text-decoration: underline;"><strong><{$smarty.const._MD_EXTCAL_LOCATION_ADRESSE}>
                            :</strong></span>
                    <br>
                    <{$event_address}>
                    <br>
                    <br>
					<{/if}>
                <{/if}>
                <!--<span style="font-size:0.8em;"><{$smarty.const._MD_EXTCAL_POSTED_BY}> <a href="<{$xoops_url}>/userinfo.php?uid=<{$event.user.uid}>"><{$event.user.uname}></a> <{$smarty.const._MD_EXTCAL_ON}> <{$event.formated_event_submitdate}></span>
            <p>
                <{$event.event_desc|default:''}>
            </p>-->

        </td>
    </tr>
</table>
<div style="border-bottom:1px solid #CCCCCC;"></div>
<{if $showLocation|default:false == 1}>
<{if $location.id.value|default:false != 0}>
    <table style="border:0;width:100%" class="outer">
        <tr>
            <td style="width:_EXTCAL_TS_MINUTE%" class="odd">
                <span style="text-decoration: underline;"><strong><{$smarty.const._MD_EXTCAL_LOCATION}></strong></span><br>
                <a href="./location.php?location_id=<{$event.event_location|default:''}>">
                    <span style="font-size:14px;"><{$location.nom.value|default:false}></span>
                </a>
                <br>
                <{if $location.adresse.value|default:false}><{$location.adresse.value}><br><{/if}>
                <{if $location.ville.value|default:false}><{$location.ville.value}><br><{/if}>
                <{if $location.telephone.value|default:false}><{$location.telephone.value}><br><{/if}>
                <{if $location.site.value|default:false}>
                    <a href="<{$location.site.value}>" target="_blank">
                        <{$smarty.const._MD_EXTCAL_VISIT_SITE}>
                    </a>
                    <br>
                <{/if}>
                <{if $location.map.value|default:false}>
                    <a href='<{$location.map.value}>' target='blanck'><{$smarty.const._MD_EXTCAL_LOCALISATION}></a>
                    <br>
                <{/if}>
            </td>
            <td style="width:20%" class="odd">
                <{if $location.logo.value|default:false}>
                    <div class="highslide-gallery">
                        <a href="<{$xoops_url}>/uploads/extcal/location/<{$location.logo.value}>"
                           class="highslide" onclick="return hs.expand(this)">
                            <img style="text-align:left;margin-right:10px;"
                                 src="<{$xoops_url}>/uploads/extcal/location/<{$location.logo.value}>"
                                 height="150px" alt='<{$location.logo.value}>' title='<{$location.logo.value}>'>
                        </a>

                        <div class="highslide-heading"></div>
                    </div>
                <{elseif $smarty.const._EXTCAL_SHOW_NO_PICTURE}>
                    <img style="text-align:left;margin-right:6px;"
                         src="<{$xoops_url}>/modules/extcal/assets/images/no_picture.png" height="180"
                         alt='<{$smarty.const._EXTCAL_SHOW_NO_PICTURE}>' title='<{$smarty.const._EXTCAL_SHOW_NO_PICTURE}>'>
                <{/if}>
            </td>
        </tr>
    </table>
<{/if}>
<{/if}>

<table style="border:0;width=100%" class="outer">

    <tr>
        <td style="width:50%" class="odd" colspan='2'>

            <{if $showOrganizer|default:false == 1}>
			<{if $event.event_organisateur|default:false}>
                <span style="text-decoration: underline;"><strong><{$smarty.const._MD_EXTCAL_ORGANISATEUR}></strong></span>
                <br>
                <{$event.event_organisateur}>
                <br>
            <{/if}>
            <{/if}>
			<{if $showContact|default:false == 1}>
            <{if $event.event_contact|default:false}><{$event.event_contact}><br><{/if}>
            <{/if}>
			<{if $showEmail|default:false == 1}>
            <{if $event.event_email|default:false}><a href="mailto:<{$event.event_email}>"><{$event.event_email}></a><br><{/if}>
			<{/if}>
			<{if $showUrl|default:false == 1}>
            <{if $event.event_url|default:false}><a href="<{$event.event_url}>" target="_blank"><{$event.event_url}></a><br><{/if}>
			<{/if}>
        </td>

    </tr>

    <tr>

        <{if $showPrice|default:false == 1}>
		<{if $event.event_price|default:false}>
            <td style="width:50%" class="odd"><br>
                <span style="text-decoration: underline;"><strong><{$smarty.const._MD_EXTCAL_LOCATION_TARIFS}>
                        :</strong></span><br>
                <{$event.event_price}>
                <{$smarty.const._MD_EXTCAL_DEVISE2}>
            </td>
        <{/if}>
        <{/if}>

        <td class="odd"><br>
            <span style="text-decoration: underline;"><strong><{$smarty.const._MD_EXTCAL_START}></strong></span> <{$event.formated_event_start|default:false}>
            <br>
            <span style="text-decoration: underline;"><strong><{$smarty.const._MD_EXTCAL_END}></strong></span> <{$event.formated_event_end|default:false}>
            <br>
        </td>
    </tr>
</table>
<div style="border-bottom:1px solid #CCCCCC;"></div>


<table class="outer">

    <{if $whosGoing|default:false}>
        <tr>
            <td colspan="3" class="even">
                <b><{$smarty.const._MD_EXTCAL_WHOS_GOING}> (<{$eventmember.member.nbUser|default:false}>)
                    :</b> <{foreach name=eventMemberList from=$eventmember.member.userList|default:array() item=member}><{if $smarty.foreach.eventMemberList.first != 1}>, <{/if}>
                    <a href="<{$xoops_url}>/userinfo.php?uid=<{$member.uid}>"><{$member.uname}></a><{/foreach}>
                <{if $eventmember.member.show_button|default:false}>
                    <form style="display:inline;" method="post" action="event_member.php">
                        <{securityToken}><{*//mb*}>
                        <input type="hidden" name="mode" value="<{$eventmember.member.joinevent_mode|default:false}>">
                        <input type="hidden" name="event" value="<{$event.event_id|default:false}>">
                        <input type="submit" value="<{$eventmember.member.button_text|default:false}>"<{$eventmember.member.button_disabled|default:false}>>
                    </form>
                <{/if}>
            </td>
        </tr>
    <{/if}>
    <{if $whosNotGoing|default:false}>
        <tr>
            <td colspan="3" class="even">
                <b><{$smarty.const._MD_EXTCAL_WHOSNOT_GOING}> (<{$eventmember.notmember.nbUser|default:false}>)
                    :</b> <{foreach name=eventMemberList from=$eventmember.notmember.userList|default:array() item=member}><{if $smarty.foreach.eventMemberList.first != 1}>, <{/if}>
                    <a href="<{$xoops_url}>/userinfo.php?uid=<{$member.uid}>"><{$member.uname}></a><{/foreach}>
                <{if $eventmember.notmember.show_button}>
                    <form style="display:inline;" method="post" action="event_notmember.php">
                        <{securityToken}><{*//mb*}>
                        <input type="hidden" name="mode" value="<{$eventmember.notmember.joinevent_mode|default:false}>">
                        <input type="hidden" name="event" value="<{$event.event_id|default:false}>">
                        <input type="submit" value="<{$eventmember.notmember.button_text|default:false}>"<{$eventmember.notmember.button_disabled|default:false}>>
                    </form>
                <{/if}>
            </td>
        </tr>
    <{/if}>


    <{if false}>
        <tr>
            <td colspan="3" class="even">
                <{$smarty.const._MD_EXTCAL_STATUS}> : <{$status|default:false}>
                <input type="submit" value="<{$smarty.const._MD_EXTCAL_VALIDATE}>">
            </td>
        </tr>
    <{/if}>


    <div id="map" style="text-align:center;visibility: hidden;"><br>
        <{$map|default:false}>
    </div>
</table>
<{if $showFile|default:false == 1}>
<p style="text-align:right;">
    <{foreach item=eventFile from=$event_attachement}>
        <a href="download_attachement.php?file=<{$eventFile.file_id|default:false}>"><{$eventFile.file_nicename|default:false}>
            (<i><{$eventFile.file_mimetype|default:false}></i>) <{$eventFile.formated_file_size|default:false}></a>
        <br>
    <{/foreach}>
</p>
<{/if}>

<{include file="db:extcal_buttons_event.tpl"}>

<div style="text-align: center; margin-top: 20px;">
    <{$commentsnav|default:false}>
    <{$lang_notice|default:false}>
</div>

<div style="margin-top: 10px;">
    <!-- start comments loop -->
    <{if $comment_mode|default:false == "flat"}>
        <{include file="db:system_comments_flat.tpl"}>
    <{elseif $comment_mode|default:false == "thread"}>
        <{include file="db:system_comments_thread.tpl"}>
    <{elseif $comment_mode|default:false == "nest"}>
        <{include file="db:system_comments_nest.tpl"}>
    <{/if}>
    <!-- end comments loop -->
</div>
<{include file='db:system_notification_select.tpl'}>

