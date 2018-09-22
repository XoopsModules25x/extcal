<table>
    <tr>
        <th>
            <{foreach item=cat from=$cats}>
                <div style="float:left; margin-left:5px;">
                    <div style="float:left; background-color:#<{$cat.cat_color}>; border:1px solid #ffffff; margin-right:5px;">
                        &nbsp;</div>
                    <{$cat.cat_name}>
                </div>
            <{/foreach}>
        </th>
    </tr>
</table>
