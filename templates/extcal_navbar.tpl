<style type="text/css">
    #extcal-nav #navlist {
        padding: 0 0;
        margin: 10px 0 0 0;
        border-bottom: 0 solid;
        font: bold 12px Verdana, sans-serif;
    }

    #extcal-nav #navlist li {
        list-style: none;
        margin: 0;
        display: inline;
    }

    #extcal-nav #navlist li a {
        padding: 3px 0.5em;
        margin-left: 3px;
        border: 1px solid;
        -moz-border-radius-topleft: 7px;
        -moz-border-radius-topright: 7px;
        -moz-border-top-right-radius: 5px;
        border-bottom: none;
        text-decoration: none;
        line-height: 20px;
        display: inline;
    }

    #extcal-nav #navlist li a#current {
        border-bottom: 0 solid white;
    }
</style>

<{if $list_position==0}>
    <form action="<{$navigSelectBox.action}>" method="<{$navigSelectBox.method}>" class="head">
        <{foreach item=element from=$navigSelectBox.elements}>
        <{$element.body}>
        <{/foreach}>
    </form>
<{/if}>

<div id="extcal-nav">
    <ul id="navlist">
        <{foreach item=navBar from=$tNavBar}>
            <li>
                <a href="<{$navBar.href}>"
                        <{if $navBar.current}>
                            id="current"
                        <{else}>
                            class="head"
                        <{/if}> >
                    <{$navBar.name}>
                </a>
            </li>
        <{/foreach}>
    </ul>
</div>


<{if $list_position==1}>
    <form action="<{$navigSelectBox.action}>" method="<{$navigSelectBox.method}>" class="head">
        <{foreach item=element from=$navigSelectBox.elements}>
        <{$element.body}>
        <{/foreach}>
    </form>
<{/if}>
