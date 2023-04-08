<html>

<head>
    <title>Extcal</title>
</head>

<body>
<div style="text-align:center;background: #CCCCCC;">
    <b><{$xoopsConfig.sitename}></b><br>
    <i><{$xoopsConfig.slogan}></i><br>
    <b><{$event.event_title}></b>
</div>

<{$dateAction}> : <{$subject}> <{$acteur.name}> (<{$acteur.uname}>)
<hr>
<{$acteur.name}> <{$action}> <br>

<{if $message != ''}>
    <b><{$smarty.const._MD_EXTCAL_CITATION}></b>
    :
    <br>
    <{$message}>
    <br>
<{/if}>

<hr>

<table style="border:1px;width=100%;border-spacing:0;padding:4px;table-border-color-light:#C0C0C0;border-color:#FFFFFF;background-color:#CCCCCC">

    <tr>
        <td style='text-align:center' colspan='4'>
            <b><{$smarty.const._MD_EXTCAL_MEMBERS_LIST}><{$br}></b>
        </td>
    </tr>
    <{foreach item=member from=$members}>
        <tr>
            <td>
                <b><{$member.name}></b>
            </td>
            <td>
                <{$member.uname}>
            </td>
            <td>
                <{$member.email}>
            </td>
            <td>
                <{$member.status}>
            </td>
        </tr>
    <{/foreach}>
</table>
<hr>

<div style="text-align: center;">
    <{$smarty.const._MD_EXTCAL_EVENT}> <br>
    <b><{$event.event_title}></b><br>
</div>
<{$smarty.const._MD_EXTCAL_START}> : <{$event.formated_event_start}><br>
<{$smarty.const._MD_EXTCAL_END}> : <{$event.formated_event_end}><br>


<hr>
<{$smarty.const._MD_EXTCAL_POLITESSE}> <{$submiter.name}>
<hr>


</body>

</html>
