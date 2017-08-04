<script src="<{$xoops_url}>/modules/extcal/include/AC_RunActiveContent.js"
        type="text/javascript"></script>

<script type="text/javascript">
    if (AC_FL_RunContent == 0) {
        alert("Cette page nécessite le fichier AC_RunActiveContent.js.");
    } else {
        AC_FL_RunContent(
                'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0',
                'width', '<{$block.imageParam.frameWidth}>',
                'height', '<{$block.imageParam.frameHeight}>',
                'src', 'SlideShow',
                'FlashVars', 'confFile=<{$xoops_url}>/cache/extcalSlideShowParam.xml',
                'quality', 'high',
                'pluginspage', 'http://www.macromedia.com/go/getflashplayer',
                'align', 'middle',
                'play', 'true',
                'loop', 'true',
                'scale', 'showall',
                'wmode', 'transparent',
                'devicefont', 'false',
                'id', 'SlideShow',
                'bgcolor', '#ffffff',
                'name', 'SlideShow',
                'menu', 'true',
                'allowFullScreen', 'false',
                'allowScriptAccess', 'sameDomain',
                'movie', '<{$xoops_url}>/modules/extcal/include/SlideShow',
                'salign', ''
        ); //end AC code
    }
</script>
<noscript>
    <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
            codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0"
            width="150px" height="225px" id="Slideshow" align="middle">
        <param name="allowScriptAccess" value="sameDomain">
        <param name="allowFullScreen" value="false">
        <param name="movie"
               value="<{$xoops_url}>/modules/extcal/include/SlideShow.swf">
        <param name="quality" value="high">
        <param name="bgcolor" value="#ffffff">
        <param name="FlashVars"
               value="confFile=<{$xoops_url}>/cache/extcalSlideShowParam.xml">
        <embed src="<{$xoops_url}>/modules/extcal/include/SlideShow.swf"
               quality="high" bgcolor="#ffffff"
               width="<{$block.imageParam.frameWidth}>"
               height="<{$block.imageParam.frameHeight}>"
               name="SlideShow" align="middle"
               allowScriptAccess="sameDomain"
               allowFullScreen="false"
               type="application/x-shockwave-flash"
               pluginspage="http://www.macromedia.com/go/getflashplayer">
    </object>
</noscript>

