<{include file="db:extcal_navbar.tpl"}>

<form action="<{$navigSelectBox.action}>" method="post">
    <{securityToken}><{*//mb*}>
    <table class="outer" style="border-top: none;">
        <tr>
            <th align='center' colspan='2'>
                <{$smarty.const._MD_EXTCAL_SERACH_CRITERIA}>
            </th>
        </tr>
        <tr class="even">
            <td>
            </td>
            <td>
            </td>
        </tr>

        <tr class="even">
            <td>
                <{$smarty.const._MD_EXTCAL_EXPRESSION}>
            </td>
            <td>
                <{$search.searchExp}><{$search.andor}>
            </td>
        </tr>

        <tr class="even">
            <td>
                <{$smarty.const._MD_EXTCAL_CATEGORY}>
            </td>
            <td>
                <{$search.cat}>
            </td>
        </tr>

        <tr class="even">
            <td>
                <{$smarty.const._MD_EXTCAL_PERIODE}>
            </td>
            <td>
                <{$search.day}><{$search.month}><{$search.year}>
            </td>
        </tr>

        <tr class="even">
            <td>
                <{$smarty.const._MD_EXTCAL_ORDER_BY}>
            </td>
            <td>
                <{$search.orderby1}><{$search.orderby2}><{$search.orderby3}>
            </td>
        </tr>

        <input type="hidden" name="num_tries" value="<{$num_tries}>">
        <tr class="even">
            <td colspan='2' align='center'>
                <input type="submit" style='width:150px;' value="<{$smarty.const._MD_EXTCAL_SEARCH}>" name="B1">
                <input type="button" style='width:150px;' value="<{$smarty.const._MD_CLEAR_CRITERIA}>" name="B2"
                       onclick="extcal_clear_criteres();">
                <input type="button" style='width:50px;visibility:hidden;' value="" name="B0">

            </td>
        </tr>
    </table>
    <{if $num_tries > 0}>
        <table class="outer" style="border-top: none;">
            <tr>
                <th align='center'>
                    <{$evenements_trouves}>
                </th>
            </tr>
        </table>
    <{/if}>
</form>

<{if $num_tries > 0}>

    <{include file="db:extcal_event_list1.tpl"}>


    <{include file="db:extcal_categorie.tpl"}>
<{/if}>

<div style="text-align:right;"><a
            href="<{$xoops_url}>/modules/extcal/rss.php?cat=<{$selectedCat}>"><img
                src="assets/images/icons/rss.gif" alt="RSS Feed"></a></div>
<{include file='db:system_notification_select.tpl'}>


<script type="text/javascript">
    function extcal_clear_criteres() {
        ob = document.getElementsByName("searchExp");
        ob[0].value = "";

        ob = document.getElementsByName("andor");
        ob[0].value = "AND";

        ob = document.getElementsByName("year");
        ob[0].value = 0;

        ob = document.getElementsByName("month");
        ob[0].value = 0;

        ob = document.getElementsByName("day");
        ob[0].value = 0;

        ob = document.getElementsByName("cat");
        ob[0].value = 0;

        ob = document.getElementsByName("orderby1");
        ob[0].value = "cat_name ASC";

        ob = document.getElementsByName("orderby2");
        ob[0].value = "event_title ASC";

        ob = document.getElementsByName("orderby3");
        ob[0].value = "";


        //alert(ob[0].name);
    }


</script>
