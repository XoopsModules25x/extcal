function bascule(elem) {
    etat = document.getElementById(elem).style.visibility;
    if (etat == "hidden") {
        document.getElementById(elem).style.visibility = "visible";
    }
    else {
        document.getElementById(elem).style.visibility = "hidden";
    }
}

//hs.graphicsDir = '<{$smarty.const.XOOP_URL}>/modules/extcal/assets/js/graphics/';
hs.graphicsDir = './assets/js/graphics/';
hs.align = 'center';
hs.transitions = ['expand', 'crossfade'];
hs.outlineType = 'rounded-white';
hs.wrapperClassName = 'controls-in-heading';
hs.fadeInOut = true;


