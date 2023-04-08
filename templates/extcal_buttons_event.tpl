<STYLE type="txt/css">
    <!--
    .extbtnimg {
        border: 2px solid #FF0000;
    }

    -->
</STYLE>


<div style="text-align:left;">
    <a href="<{$xoops_url}>/modules/extcal/print.php?event=<{$event.event_id}>">
        <img src="<{xoModuleIcons16 'printer.png'}>" alt='<{$smarty.const._MD_EXTCAL_ICONE_PRINT}>' title='<{$smarty.const._MD_EXTCAL_ICONE_PRINT}>'
             class='extbtnimg'
             style='margin-left:2px;margin-right:2px;'>
    </a>
    <{if $isAdmin || $canEdit}>
        <a href="<{$smarty.const._EXTCAL_FILE_NEW_EVENT}>?event=<{$event.event_id}>&action=edit">
            <img src="<{xoModuleIcons16 'edit.png'}>" alt='<{$smarty.const._MD_EXTCAL_ICONE_EDIT}>' title='<{$smarty.const._MD_EXTCAL_ICONE_EDIT}>'
                 class='extbtnimg'
                 style='margin-left:2px;margin-right:2px;'>

        </a>
        <a href="<{$smarty.const._EXTCAL_FILE_NEW_EVENT}>?event=<{$event.event_id}>&action=clone">
            <img src="<{$smarty.const._EXTCAL_PATH_ICONS16}>/editcopy.png"
                 alt='<{$smarty.const._MD_EXTCAL_ICONE_CLONE}>'
                 title='<{$smarty.const._MD_EXTCAL_ICONE_CLONE}>'
                 class='extbtnimg'
                 style='margin-left:2px;margin-right:2px;'>

        </a>
    <{/if}>
    <{if $isAdmin}>
        <a href="admin/event.php?op=delete&event_id=<{$event.event_id}>">
            <img src="<{xoModuleIcons16 'delete.png'}>" alt='<{$smarty.const._MD_EXTCAL_ICONE_DELETE}>' title='<{$smarty.const._MD_EXTCAL_ICONE_DELETE}>'
                 class='extbtnimg'
                 style='margin-left:2px;margin-right:2px;'>
        </a>
    <{/if}>
</div>


