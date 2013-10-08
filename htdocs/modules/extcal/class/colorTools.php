<?PHP

class ColorTools{         
  
  function __construct(){
  }


/**************************************************************
Ajoute ou rtir un icrement sur chaque compsante RGB d'une couleur
Les valeur sont limiter au bornes inf�rieure et sup�rieure 0 et 255
***************************************************************/
Function modifierCouleur($colorHexa, 
            $incrementRouge, $incrementVert, $incrementBleu, 
            $plancherRouge = 0, $plafondRouge = 255, 
            $plancherVert  = 0, $plafondVert  = 255, 
            $plancherBleu  = 0, $plafondBleu  = 255 
            ) {


    $t10 = ColorTools::hexa2rgbA($colorHexa);
    
    $t10[1] = ColorTools::bornerValeur($t10[1] + $incrementRouge, $plancherRouge, $plafondRouge);
    $t10[2] = ColorTools::bornerValeur($t10[2] + $incrementVert,  $plancherVert,  $plafondVert);
    $t10[3] = ColorTools::bornerValeur($t10[3] + $incrementBleu,  $plancherBleu,  $plafondBleu);
    
    $newColorHexa = ColorTools::getHexaColorFromA($t10);
    
    return $newColorHexa;

 }


/**************************************************************
Eclairci une couleur 
elle born� pa un plancher et un plafond pur �vite le tout blanc ou tout blanc
ou les blocage sur une couleur pur (ex #FF0000) 
***************************************************************/
Function eclaircir($colorHexa, $plancher = 0, $plafond = 255){

    $tMin = Array('', $plancher, $plancher, $plancher);
    $tMax = Array('', $plafond,  $plafond,  $plafond);
    
    $t10 = ColorTools::hexa2rgbA($colorHexa);
// echo "<hr>";
// ext_echoArray($t10);    
    $max = $plancher;
    For ($h = 1 ; $h <= 3 ; $h++){
        If ($max < $t10[$h]) {
          $max = $t10[$h];
        }
    }


    $increment = $plafond - $max;
     
//     $t10[1] = $t10[1] + $increment;
//     $t10[2] = $t10[2] + $increment;
//     $t10[3] = $t10[3] + $increment;
    
    $min = 0;
    For ($h = 1 ; $h <= 3 ; $h++){
       $t10[$h] = $t10[$h] + $increment;
       If ($t10[$h] < $tMin[$h] && $min < ($tMin[$h] - $t10[$h])) {
            $min = ($tMin[$h] - $t10[$h]);
        }
    }

// echo "{$colorHexa}-{$plancher}-{$plafond}<br>";    
// echo "{$min}-{$max}-{$increment}<br>";    

    $t10[1] = ColorTools::bornerValeur($t10[1] + $min, $plancher, $plafond);
    $t10[2] = ColorTools::bornerValeur($t10[2] + $min, $plancher, $plafond);
    $t10[3] = ColorTools::bornerValeur($t10[3] + $min, $plancher, $plafond);
    
// ext_echoArray($t10);    
    
    $newColorHexa = ColorTools::getHexaColorFromA($t10);
// echo "colorHexa = {$newColorHexa}-{$colorHexa}<br>";    
    return  $newColorHexa;
}

/**************************************************************
Fonce une couleur 
elle born� pa un plancher et un plafond pur �vite le tout blanc ou tout blanc
ou les blocage sur une couleur pur (ex #FFFF00) 
***************************************************************/
Function foncer($colorHexa, $plancher = 0, $plafond = 255){

    $tMin = Array('', $plancher, $plancher, $plancher);
    $tMax = Array('', $plafond,  $plafond,  $plafond);

    
    $t10 = ColorTools::hexa2rgbA($colorHexa);
    $max = 255;
    
    For ($h = 1 ; $h <= 3 ; $h++){
        If ($max > $t10[$h]) {
          $max = $t10[$h];
        }
    }
    
    $increment = -$max;
    
//     $t10[1] = $t10[1] + $increment;
//     $t10[2] = $t10[2] + $increment;
//     $t10[3] = $t10[3] + $increment;
    
    $min = 0;
    For ($h = 1 ; $h <= 3 ; $h++){
       $t10[$h] = $t10[$h] + $increment;
       If ($t10[$h] > $tMax[$h] && $min < ($t10[$h] - $tMax[$h])) {
            $min = ($t10[$h] - $tMax[$h]);
        }
    }

    $t10[1] = ColorTools::bornerValeur($t10[1] - $min, $plancher, $plafond);
    $t10[2] = ColorTools::bornerValeur($t10[2] - $min, $plancher, $plafond);
    $t10[2] = ColorTools::bornerValeur($t10[3] - $min, $plancher, $plafond);
    
    $colorHexa = ColorTools::getHexaColorFromA($t10);
    
    return $colorHexa;

}


/**************************************************************
Renvoi une couleur RGB en hexa a partir du tableau passe en parametr
Description du tableau
0 = doit contenir '#' ou ''
1 = int red
2 = int vert
3 = int bleu 
***************************************************************/
Function getHexaColorFromA($aColors) {
    
    $tHex = array("","","","");

    $tHex[0] = $aColors[0];
    $tHex[1] = substr('00' . dechex($aColors[1]), -2);
    $tHex[2] = substr('00' . dechex($aColors[2]), -2);
    $tHex[3] = substr('00' . dechex($aColors[3]), -2);
    
    $colorHexa = implode('', $tHex);
    return $colorHexa;
}

/**************************************************************
Transforme les composante d'une couleur en valeu hexa
prefixe doit contenir '#'  ou ''
***************************************************************/
Function rgb2hexa($r, $g, $b, $prefixe = ''){
    
    $colorHexa = ColorTools::getHexaColorFromA(Array($prefixe, $r, $g, $b));
    
    return $colorHexa;
    
}

/**************************************************************
renvoi un tableau d'entier des valeur rgbd d'une couleur a partir d'un hexa
Description du tableau renvoy�
0 = contient '#' ou ''  (selon premier cactere de la couleur hexa)
1 = int red
2 = int vert
3 = int bleu 
***************************************************************/
Function hexa2rgbA($colorHexa){

    $t = array("","","","");
  
    If (substr($colorHexa, 0, 1) == '#'){
        $t[0] = '#';
        $offsetCar = 1;
    }else{
        $t[0] = '';
        $offsetCar = 0;
    } 

    $t[1] = hexdec(substr($colorHexa, $offsetCar + 0, 2));
    $t[2] = hexdec(substr($colorHexa, $offsetCar + 2, 2));
    $t[3] = hexdec(substr($colorHexa, $offsetCar + 4, 2));
    
    return $t;
}
 
/**************************************************************
Renvoi les composante rgb d'une couleur par r�f�rence
***************************************************************/
Function hexa2rgb($colorHexa, &$r, &$v, &$b, &$diese){
    
  $t = ColorTools::hexa2rgbA($colorHexa);
  $r = $t[1];
  $v = $t[2];
  $v = $t[3];
  $diese = $t[0];
  
  return true;
}


/**************************************************************
Borne les valeurs max et min d'une valeur
***************************************************************/
Function bornerValeur($val, $min, $max){
    
    If ($val < $min){
        $val = $min;
    } else if($val >  $max){
        $val = $max;
    }
    
    return $val;
    
}

//--------------------------------------------------------
}  // --- fin de la classe colors
//--------------------------------------------------------


?>
