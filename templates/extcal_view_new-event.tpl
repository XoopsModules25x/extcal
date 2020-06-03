<form action="<{$navigSelectBox.action}>" method="<{$navigSelectBox.method}>">
    <{foreach item=element from=$navigSelectBox.elements}>
    <{$element.body}>
    <{/foreach}>
</form>

<{include file="db:extcal_navbar.tpl"}>

<{$formEdit}>


<div style="text-align:right;"><a href="<{$xoops_url}>/modules/extcal/rss.php?cat=<{$selectedCat}>">
        <img src="assets/images/icons/rss.gif" alt="RSS Feed">
    </a></div>

<{include file='db:system_notification_select.tpl'}>
