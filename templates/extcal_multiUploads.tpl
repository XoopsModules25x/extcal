<div id="selecteur" align='center'>
    <h1><{$event.event_title}> (#<{$event.event_id}>)</h1>

    <p><{$event.event_desc}></p>
    <br>

    <{if $multiUploadsOK}>
        <{$multiUploads}>
    <{else}>
        <p><b><span style="color: #FF0000; font-size: x-large; ">
                    <{$smarty.const._AM_EXTCAL_MULTIUPLOADS_NOT_OK}>
                </span></b></p>
        <br>
        <br>
    <{/if}>

</div>

<div align='center'>


    <a href='<{$urlRetour}>'>
        <input type="button" value="<{$smarty.const._AM_EXTCAL_GOTO_EVENTS}>" name="B3">
    </a>
    <a href='<{$urlRetour}>?op=modify&event_id=<{$event.event_id}>'>
        <input type="button" value="<{$smarty.const._AD_EXTCAL_EDIT_EVENT}>" name="B3">
    </a>

</div>



