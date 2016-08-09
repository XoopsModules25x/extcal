<{$dateAction}> : <{$subject}> <{$acteur.name}> (<{$acteur.uname}>)<{$br}>
---------------------------------
<{$acteur.name}> <{$action}>

<{if $message != ''}>
    <{$smarty.const._MD_EXTCAL_CITATION}> :
    <{$message}>
<{/if}>

_________________________________
<{$smarty.const._MD_EXTCAL_MEMBERS_LIST}>

<{foreach item=member from=$members}>
    ===> <{$member.name}> (<{$member.uname}> - <{$member.email}>) => <{$member.libAction}>
<{/foreach}>
_________________________________

<{$smarty.const._MD_EXTCAL_EVENT}>
<{$event.event_title}>
<{$smarty.const._MD_EXTCAL_START}> : <{$event.formated_event_start}>
<{$smarty.const._MD_EXTCAL_END}> : <{$event.formated_event_end}>


------------------------------------------
<{$smarty.const._MD_EXTCAL_POLITESSE}> <{$submiter.name}>
