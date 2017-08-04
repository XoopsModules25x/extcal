<{if $showInfoBulle}>
    <div style="height:12px; width:12px; background-color:#<{$event.cat.cat_color}>; border:1px solid black; float:left; margin-right:5px;">
    </div>
    <a class="tooltip54" href="<{$xoops_url}>/modules/extcal/event.php?event=<{$event.event_id}>">
        <{if $showId}>(#<{$event.event_id}>)<{/if}> <{$event.event_title}><br>
        <span class="custom info" width350 style="background: #<{$event.cat.cat_light_color}>;">
          <{if $event.event_icone}><img src="assets/css/images/<{$event.event_icone}>"  alt="" iconinfo><{/if}>
            <em><{if $showId}>(#<{$event.event_id}>)<{/if}> <{$event.event_title}></em>
            <{if $event.event_picture1!=""}>
                <img src="<{$xoops_url}>/uploads/extcal/<{$event.event_picture1}>" alinea>
            <{/if}>
            <b><{$smarty.const._MD_EXTCAL_START}></b> <{$event.formated_event_start}><br>
          <b><{$smarty.const._MD_EXTCAL_END}></b> <{$event.formated_event_end}><br>

      </span>
    </a>
<{else}>
    <a href="<{$xoops_url}>/modules/extcal/event.php?event=<{$event.event_id}>">
        <{if $showId}>(#<{$event.event_id}>)<{/if}> <{$event.event_title}>
    </a>
    <div style="height:12px; width:12px; background-color:#<{$event.cat.cat_color}>; border:1px solid black; float:left; margin-right:5px;"

         title='<{if $showId}>(#<{$event.event_id}>)<{/if}>  <{$event.formated_event_start}> - <{$event.formated_event_end}> : <{$event.event_title}>'>
    </div>
<{/if}>
