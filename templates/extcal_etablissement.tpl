<script type="text/javascript">
    function bascule(elem) {
        etat = document.getElementById(elem).style.visibility;
        if (etat == "hidden") {
            document.getElementById(elem).style.visibility = "visible";
        }
        else {
            document.getElementById(elem).style.visibility = "hidden";
        }
    }

    hs.graphicsDir = './assets/js/graphics/';
    hs.outlineType = 'rounded-white';
</script>

<table class="outer">
    <tr>
        <th colspan="3" style="font-size:1.2em;">
            <div style="float:left;"></div>
            <div style="text-align:right;"><{$edit_delete}></div>
        </th>
    </tr>
    <tr>
        <td colspan="3" class="odd" style="padding-right:30px; padding-top:10px;">
            <div style="padding-right:0;">
                <{if $etablissement.logo}>
                    <a id="<{$id}>" class="highslide" onclick="return hs.expand(this)"
                       href="<{$smarty.const.XOOPS_URL}>/uploads/extcal/etablissement/<{$etablissement.logo}>">
                        <img align=right style="border:1px solid #FFFFFF;margin-right:6px;"
                             src="<{$smarty.const.XOOPS_URL}>/uploads/extcal/etablissement/<{$etablissement.logo}>"
                             height="150px">
                    </a>
                <{elseif $smarty.const._EXTCAL_SHOW_NO_PICTURE}>
                    <img align=right style="border:1px solid #FFFFFF;margin-right:6px;"
                         src="<{$smarty.const.XOOPS_URL}>/modules/extcal/assets/images/no_picture.png" width="180"
                         height="180">
                <{/if}>
            </div>
            <div style="font-size:16px; font-weight:bold; width:280px; overflow:hidden; margin-left:30px;">
                <span style="text-decoration: underline;"><{$etablissement.nom}></span><br>

                <div style="font-size:14px; ">
                    <{if $etablissement.categorie}><{$etablissement.categorie}><br><{/if}>
                    <{if $etablissement.adresse}><{$etablissement.adresse}><br><{/if}>
                    <{if $etablissement.adresse2}><{$etablissement.adresse2}><br><{/if}>
                    <{if $etablissement.cp}><{$etablissement.cp}><{/if}>
                    - <{if $etablissement.ville}><{$etablissement.ville}><br><{/if}>
                    <{if $etablissement.map!=''}>
                        <a href="<{$etablissement.map}>"
                           target="_blank"><{$smarty.const._MD_EXTCAL_ETABLISSEMENT_MAP2}></a>
                        <br>
                    <{/if}>

                    <{if $etablissement.tel_fixe}><{$etablissement.tel_fixe}><br><{/if}>
                    <{if $etablissement.tel_portable}><{$etablissement.tel_portable}><br><{/if}>
                    <{if $etablissement.mail}><A href="mailto:<{$mail}>"><{$etablissement.mail}></A><br><{/if}>
                    <{if $etablissement.site}><a href="<{$etablissement.site}>"
                                                 target="_blank"><{$smarty.const._MD_EXTCAL_VISIT_SITE}></a>
                        <br>
                    <{/if}>
                </div>
            </div>
        </td>
    </tr>
</table>
<hr>
<div style=" overflow:hidden;  font-weight:bold; margin-left:30px; text-align:left;">
    <strong style="text-decoration: underline;"><{$smarty.const._MD_EXTCAL_ETABLISSEMENT_INFO_COMPL}></strong></u>
    <br><br>
    <{if $etablissement.description}><{$etablissement.description}><br><br><{/if}>
    <{if $etablissement.horaires}><{$etablissement.horaires}><br><{/if}>
    <{if $etablissement.tarifs}><{$etablissement.tarifs}>&nbsp; <{$smarty.const._MD_EXTCAL_DEVISE2}><br><{/if}>
    <{if $etablissement.divers}><{$etablissement.divers}><br><{/if}>
</div>

<{*<tr>*}>
    <{*<td class="odd" colspan="3" style="padding-left:50px; padding-right:50px; padding-top:10px">*}>
        <{*<table align="right" border="0" width="100%">*}>
            <{*<tr>*}>
                <{*<td width="50%" style="font-weight:bold">*}>
                <{*</td>*}>
                <{*<td>&nbsp;<strong><u><{$smarty.const._MD_EXTCAL_ETABLISSEMENT_EVENTS_VENIR}></strong></u><br><br>*}>
                    <{*<div style="width: 400px; height: 300px; overflow-y: scroll; background-color:#FFFFFF; scrollbar-arrow-color:blue; scrollbar-face-color: #e7e7e7; scrollbar-3dlight-color: #a0a0a0; scrollbar-darkshadow-color:#888888">*}>

                        <{*<{foreach item=event from=$events}>*}>
                            <{*<div style="border:1px solid #333333;">*}>
                                <{*<table>*}>
                                    <{*<tr>*}>
                                        <{*<td width="100px" align="center">*}>
                                            <{*<{if $event.event_picture1}>*}>
                                                <{*<a id="<{$event.event_id}>" class="highslide" onclick="return hs.expand(this)"*}>
                                                   <{*href="<{$xoops_url}>/uploads/extcal/<{$event.event_picture1}>"><img align=left*}>
                                                                                                                       <{*style="border:1px solid #333333;margin-right:6px"*}>
                                                                                                                       <{*src="<{$xoops_url}>/uploads/extcal/<{$event.event_picture1}>"*}>
                                                                                                                       <{*width="100px" height="100px"></a>*}>
                                            <{*<{elseif $smarty.const._EXTCAL_SHOW_NO_PICTURE}>*}>
                                                <{*<img align=left style="border:1px solid #333333;margin-right:6px"*}>
                                                     <{*src="<{$xoops_url}>/modules/extcal/assets/images/no_picture.png" width="100px" height="100px">*}>
                                            <{*<{/if}>*}>
                                        <{*</td>*}>
                                        <{*<td><u><strong><a href="./event.php?event=<{$event.event_id}>"><{$event.event_title}></a></strong></u>&nbsp;&nbsp;( <{$event.event_start}>*}>
                                            <{*)<br><br><{$event.event_desc}></td>*}>
                                    <{*</tr>*}>
                                <{*</table>*}>
                            <{*</div>*}>
                        <{*<{/foreach}>*}>
                    <{*</div>*}>
                <{*</td>*}>
            <{*</tr>*}>
        <{*</table>*}>
    <{*</td>*}>
<{*</tr>*}>


<{*</table>*}>
<div id="map" align="center" style="visibility: hidden;"><br>
    <{$map}>
</div>
<p style="text-align:right;">
    <{foreach item=eventFile from=$event_attachement}>
        <a href="download_attachement.php?file=<{$eventFile.file_id}>"><{$eventFile.file_nicename}>
            (<i><{$eventFile.file_mimetype}></i>) <{$eventFile.formated_file_size}></a>
        <br>
    <{/foreach}>
</p><br>
<div class="highslide-caption"></div>


