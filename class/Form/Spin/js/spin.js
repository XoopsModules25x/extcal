//  ------------------------------------------------------------------------ //
//                      JJD-SPIN - Composant spinButton                      //
//                    Copyright (c) 2006 JJ Delalandre                       //
//                       <http://xoops.kiolo.com>                            //
//  ------------------------------------------------------------------------ //
/******************************************************************************


 Copyright (C) 2007 Jean-Jacques DELALANDRE
 Ce programme est libre, vous pouvez le redistribuer et/ou le modifier selon les termes de la Licence Publique Générale GNU publiée par la Free Software Foundation (version 2 ou bien toute autre version ultérieure choisie par vous).

 Ce programme est distribué car potentiellement utile, mais SANS AUCUNE GARANTIE, ni explicite ni implicite, y compris les garanties de commercialisation ou d'adaptation dans un but spécifique. Reportez-vous à la Licence Publique Générale GNU pour plus de détails.

 Vous devez avoir reçu une copie de la Licence Publique Générale GNU en même temps que ce programme ; si ce n'est pas le cas, écrivez à la Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, +tats-Unis.

 Création avril 2006
 Dernière modification : septembre 2007
 ******************************************************************************/

var spinIdTimer = 0;
var spinPrefixe = "";
var spinPrefixe2 = "";
var spinSmallIncrement = 0;
var spinLargeIncrement = 0;
var spinIncrement = 0;
var spinComteur = 0;
var spinImg = "";
var spinSens = 0;
var spinImgName = "";


/****************************************************************************
 *
 ***************************************************************************/
function spinStart(prefixe, prefixe2, smallIncrement, largeIncrement, delai, newImg) {

    spinPrefixe = prefixe;
    spinPrefixe2 = prefixe2;
    spinSmallIncrement = smallIncrement;
    spinLargeIncrement = largeIncrement;
    spinIncrement = spinSmallIncrement;
    spinCompteur = 0;

    spinDoIncrement();
    //alert ("onSpinStart" + "-" + prefixe + "-" + smallIncrement + "-" + largeIncrement);
    if (spinIncrement > 0) {
        spinSens = 1;
        i = 0
    } else {
        spinSens = -1;
        i = 1
    };

    spinImgName = prefixe2 + "_img" + i;

    obImg = document.getElementsByName(spinImgName);
    spinImg = obImg[0].src;
    obImg[0].src = newImg;

    spinIdTimer = setInterval("spinTimer()", delai);
}

/****************************************************************************
 *
 ***************************************************************************/
function spinStop() {

    clearInterval(spinIdTimer);

    if (spinImg != "") {
        obImg = document.getElementsByName(spinImgName);
        //alert (spinImgName + " - " + spinImg);
        obImg[0].src = spinImg;
    }
}

/****************************************************************************
 *
 ***************************************************************************/
function spinTimer() {
    spinDoIncrement();
}

/****************************************************************************
 *
 ***************************************************************************/
function spinDoIncrement() {

    obMin = document.getElementsByName(spinPrefixe2 + "_min");
    obMax = document.getElementsByName(spinPrefixe2 + "_max");
    obInc = document.getElementsByName(spinPrefixe2 + "_increment");
    obVal = document.getElementsByName(spinPrefixe);

    newValue = (1 * (obVal[0].value)) + spinIncrement;

    if (newValue < 1 * obMin[0].value) {
        newValue = 1 * obMin[0].value;
        spinStop();
    } else if (newValue > 1 * obMax[0].value) {
        newValue = 1 * obMax[0].value;
        spinStop();
    }

    obVal[0].value = newValue;
    spinCompteur++;
    if (spinCompteur > 10) {
        spinIncrement = spinLargeIncrement;
    }
}

/****************************************************************************
 *
 ***************************************************************************/
function spinSetValue(prefixe, prefixe2, suffixe, newValue, delai, newImg) {

    spinPrefixe = prefixe;
    spinPrefixe2 = prefixe2;

    spinImgName = prefixe2 + "_img" + suffixe;

    obImg = document.getElementsByName(spinImgName);
    spinImg = obImg[0].src;
    obImg[0].src = newImg;

    obVal = document.getElementsByName(prefixe);
    obVal[0].value = newValue;

    spinIdTimer = setInterval("spinStop ()", delai);

}









