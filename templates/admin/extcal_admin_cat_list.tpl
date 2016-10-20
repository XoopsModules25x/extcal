<table class="width90 outer floatcenter0" summary="<{$smarty.const._MI_EXTCAL_MANAGER_CATS}>">
    <thead>

    <tr>
        <th>#</th>
        <th><{$smarty.const._AM_EXTCAL_NAME}></th>
        <th><{$smarty.const._AM_EXTCAL_WEIGHT}></th>
        <th class="txtcenter"><{$smarty.const._AM_EXTCAL_ACTION}></th>

    </tr>
    </thead>

    <tbody>

    <{foreach item=cat from=$cats}>

        <{*<{if $i++ is odd by 1}>*}>
        <{*<{assign var='colour' value=even}>*}>
        <{*<{else}>*}>
        <{*<{assign var='colour' value=odd}>*}>
        <{*<{/if}>*}>
        <{*<tr class="<{$colour}>">*}>
        <tr class="<{cycle values = "even,odd"}>">

            <td align='center'>
                <{$cat.cat_id}>
            </td>
            <td>
                <div style="height:12px; width:12px; background-color:#<{$cat.cat_color}>; border:1px solid black; float:left; margin-right:5px;">
                </div>
                <a class="tooltip" href="<{$smarty.const._EXTCAL_PATH_BO}>cat.php?op=edit&cat_id=<{$cat.cat_id}>"
                   title="<{$smarty.const._EDIT}>">
                    <{$cat.cat_name}>
                </a>
            </td>
            <td align='center'>
                <{$cat.cat_weight}>
            </td>

            <td class="txtcenter">
                <a class="tooltip" href="<{$smarty.const._EXTCAL_PATH_BO}>cat.php?op=edit&cat_id=<{$cat.cat_id}>"
                   title="<{$smarty.const._EDIT}>">
                    <img src="<{$smarty.const._EXTCAL_PATH_ICONS16}>edit.png" alt="">
                </a>

                <a class="tooltip" class="tooltip"
                   href="<{$smarty.const._EXTCAL_PATH_BO}>cat.php?op=delete&cat_id=<{$cat.cat_id}>"
                   title="<{$smarty.const._DELETE}>">
                    <img src="<{$smarty.const._EXTCAL_PATH_ICONS16}>delete.png" alt="">
                </a>
            </td>


        </tr>
    <{/foreach}>


    </tbody>
</table>

<{*<form name="frmCatNew" id="frmCatNew" action="cat.php?op=new&id=0" method="post">*}>
    <{*<fieldset>*}>
        <{*<div class="txtcenter"><input type="submit" value="<{$smarty.const._ADD}>" name="B1" title="<{$smarty.const._ADD}>"></div>*}>
    <{*</fieldset>*}>
<{*</form>*}>

