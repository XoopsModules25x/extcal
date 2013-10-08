<?php
/**
 * classGenerator
 * walls_watermarks
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * 
 *
 * L'utilisation de ce formulaire d'adminitration suppose 
 * que la classe correspondante de la table a été générées avec classGenerator 
**/

include_once XOOPS_ROOT_PATH . '/class/uploader.php';


function extcal_getEvent($eventId){


    $eventHandler = xoops_getmodulehandler(_EXTCAL_CLS_EVENT, _EXTCAL_MODULE);
    $event = $eventHandler->getEvent($eventId);
    $t= $event->getVars();
    $data=array();
    while (list($key,$val) =each($t)){
      $data[$key] = $val['value'];
    }
    return $data;
}

function extcal_loadImg(&$REQUEST, &$event_picture1, &$event_picture2){
		///////////////////////////////////////////////////////////////////////////////
		$uploaddir_event = XOOPS_ROOT_PATH . '/uploads/extcal/';
		$uploadurl_event = XOOPS_URL . '/uploads/extcal/';
		//$picture = '';
		for($j=1; $j<3; $j++)
		{
			$delimg = @$REQUEST['delimg_'.$j.''];
			$delimg = isset($delimg) ? intval($delimg) : 0;
			if (!empty($REQUEST['xoops_upload_file'][$j]) && $delimg == 0)
			{
				$upload = new XoopsMediaUploader($uploaddir_event, array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png', 'image/jpg'), 3145728, null, null);
				if ($upload->fetchMedia($REQUEST['xoops_upload_file'][$j]))
				{
					$upload->setPrefix('event_') ;
					$upload->fetchMedia($REQUEST['xoops_upload_file'][$j]);
					if (!$upload->upload()) {
						$errors = $upload->getErrors();
						redirect_header("javascript:history.go(-1)",3, $errors);
					} else {
						if($j==1) {
							$event_picture1 = $upload->getSavedFileName();
						} elseif($j==2) {
							$event_picture2 = $upload->getSavedFileName();
						}
					}						
				} else if (!empty($REQUEST['file'.$j])) {
					if($j==1) {
						$event_picture1 = $REQUEST['file'.$j];
					} elseif($j==2) {
						$event_picture2 = $REQUEST['file'.$j];
					}
				}
			} else {
				$url_event = XOOPS_ROOT_PATH . "/uploads/extcal/". $REQUEST['file'.$j];
				if($j==1) {
					$event_picture1 = '';
				} elseif($j==2) {
					$event_picture2 = '';
				}
				if (is_file($url_event)){		
					chmod($url_event, 0777);
					unlink($url_event);
				}
			}
		}
		//exit;
		///////////////////////////////////////////////////////////////////////////////

}

/*******************************************************************
 *
 *******************************************************************/ 
function getListCategories($cat, $addNone = true, $name = 'cat'){
global $xoopsUser;
  // Category selectbox  
  $catHandler = xoops_getmodulehandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);

  $catsList = $catHandler->getAllCat($xoopsUser);
  $catSelect = new XoopsFormSelect('', $name, $cat);
  if ($addNone) $catSelect->addOption(0, ' ');
  
  foreach ($catsList as $catList ) {   
      $catSelect->addOption($catList->getVar('cat_id'), $catList->getVar('cat_name'));
  }
  return $catSelect;
} /*******************************************************************
 *
 *******************************************************************/ 
function getCheckeCategories($name = 'cat', $cat){
global $xoopsUser;
  // Category selectbox
  //<option style="background-color:#00FFFF;">VARCHAR</option>
  
  $catHandler = xoops_getmodulehandler(_EXTCAL_CLS_CAT, _EXTCAL_MODULE);
  $catsList = $catHandler->getAllCat($xoopsUser);

  $t = array();
  foreach ($catsList as $catList ) {
    $cat_id = $catList->getVar('cat_id');
    $name = $catList->getVar('cat_name');
    $cat_color = $catList->getVar('cat_color');
    $checked = in_array($cat_id, $cat) ? 'checked': '';
    $cat = ""
         . "<div style='float:left; margin-left:5px;'>" 
         . "<input type='checkbox' name='{$name}[{$cat_id}]' value='1' {$checked}>"
         . "<div style='absolute:left;height:12px; width:6px; background-color:#{$cat_color}; border:1px solid black; float:left; margin-right:5px;' ></div>"
         . " {$name}"
         . "</div>"
        ;
    
    $t[] = $cat;
  }
  return $t;
}

/*******************************************************************
 *
 *******************************************************************/ 
function getListOrderBy($name='orderby', $caption='',$defaut, $addNone = false){
global $xoopsUser;

  $select = new XoopsFormSelect($caption, $name, $defaut);
  if ($addNone) $select->addOption('',  '');
  
  $select->addOption('year ASC',  _MD_EXTCAL_YEAR . ' ' . _MD_EXTCAL_ORDER_BY_ASC);
  $select->addOption('year DESC', _MD_EXTCAL_YEAR . ' ' . _MD_EXTCAL_ORDER_BY_DESC);
  
  $select->addOption('month ASC',  _MD_EXTCAL_MONTH . ' ' . _MD_EXTCAL_ORDER_BY_ASC);
  $select->addOption('month DESC', _MD_EXTCAL_MONTH . ' ' . _MD_EXTCAL_ORDER_BY_DESC);
  
  $select->addOption('event_title ASC',  _MD_EXTCAL_ALPHA . ' ' . _MD_EXTCAL_ORDER_BY_ASC);
  $select->addOption('event_title DESC', _MD_EXTCAL_ALPHA . ' ' . _MD_EXTCAL_ORDER_BY_DESC);

  $select->addOption('cat_name ASC',  _MD_EXTCAL_CATEGORY . ' ' . _MD_EXTCAL_ORDER_BY_ASC);
  $select->addOption('cat_name DESC', _MD_EXTCAL_CATEGORY . ' ' . _MD_EXTCAL_ORDER_BY_DESC);

  
  return $select;
}

/*******************************************************************
 *
 *******************************************************************/ 
function getListAndOr($name='andor', $caption='',$defaut){
global $xoopsUser;

  $select = new XoopsFormSelect($caption, $name, $defaut);
  
  $select->addOption('AND', _MD_EXTCAL_AND);
  $select->addOption('OR',  _MD_EXTCAL_OR);
  
  return $select;
}

/*******************************************************************
 *
 *******************************************************************/ 
function getList($name, $caption, $defaut, $options, $sep=';'){
global $xoopsUser;

  $select = new XoopsFormSelect($caption, $name, $defaut);
  if (!is_array($options)){
     $options = explode($sep, $options);
  }
  
  for ($h=0,$count=count($options);$h<$count;$h++){
    $select->addOption($h, $options[$h]);
  }

  
  return $select;
   
}

/*******************************************************************
 *
 *******************************************************************/ 
function getDateBetweenDates($ts, $startMonth, $endMonth, $mode='w'){

  $d = new DateTime($periodStart);
  $d->setTimestamp($ts);
  
//echo "<br>affichage des periodes : <br>";
 $begin = new DateTime();
  $begin->setTimestamp($startMonth);
  //echo $begin->format("d/m/Y à H\hi:s").'<br>'; // 03/10/2007 à 19h39:53
  
 $end = new DateTime();  
  $end->setTimestamp($endMonth);
  //echo $end->format("d/m/Y à H\hi:s").'<br>'; // 03/10/2007 à 19h39:53
  //echo "<hr>";   
 $interval = DateInterval::createFromDateString('next sunday'); 
$period = new DatePeriod($begin, $interval, $end);
//echoDateArray($period);

  //echo "<hr>{$interval}";
  return $d;


//echo mktime($heure, $minute, $seconde, $mois, $jour, $an);

// 
//   $jour = date('d', $ts);
//   $mois = date('m', $ts);
//   $an = date('Y', $ts); 
//   $heure = date('H', $ts); 
//   $minute = date('i', $ts); 
//   $seconde = date('s', $ts);
//   $d->setDate($heure,$minute,$seconde,$mois,$jour,$an);
 
// <?php
// $interval = DateInterval::createFromDateString('next sunday');
// $period = new DatePeriod($begin, $interval, $end);
// foreach ( $period as $dt ) {
//   echo $dt->format( "l Y-m-d H:i:s\n" );
}
/*
Sunday 2009-11-01 00:00:00
Sunday 2009-11-08 00:00:00
Sunday 2009-11-15 00:00:00
Sunday 2009-11-22 00:00:00
Sunday 2009-11-29 00:00:00
Sunday 2009-12-06 00:00:00
...
*/
function echoDateArray($period){
  foreach ( $period as $dt ) {
    echo $dt->format( "l Y-m-d H:i:s\n" ).'<br>';
  }

}

/*****************************************************************/
function ext_echoArray($t, $msg=''){
  if ($msg != ''){
    echo "<hr>{$msg}<hr>";
  }
  
  $txt = print_r($t,true);
  echo "<pre>Number of items: " . count($t)
     . "<br>{$txt}</pre>";
}
/*****************************************************************/
function ext_echo($line, $msg=''){
  if ($msg != ''){
    echo "<hr>{$msg}<hr>";
  }
  echo $line . "<br>";
}
/*****************************************************************/
function ext_echoTSN($tsName, $msg=''){
  global $$tsName;
  $ts = $$tsName;
  ext_echoTSU($ts, $tsName, $msg='');
}
/*****************************************************************/
function ext_echoTSU($ts, $tsName, $msg=''){
  if ($msg != ''){
    echo "<hr>{$msg}<hr>";
  }

  echo 'date --->' . $tsName . " = " . $ts . " - " . date('d-m-Y H:m:s',$ts) . "<br>";
}
/*****************************************************************/
/*****************************************************************/
function ext_convert_date($date, $sep='-')  
{
$lstSep = '/ .';
    
    for ($h=0, $count=strlen($lstSep); $h<$count; $h++)
    {
      $sep2replace = substr($lstSep,$h,1);
      if (strpos($date, $sep2replace)) 
      {
        $date=str_replace($sep2replace, $sep, $date);
      }
      return strtotime($date);
    
    }
}



function ext_DateAdd($givendate,$day=0,$mth=0,$yr=0) {
     //$cd = strtotime($givendate);
      $cd = $givendate;
      $newdate = date('Y-m-d h:i:s', mktime(date('h',$cd),
                 date('i',$cd), date('s',$cd), date('m',$cd)+$mth,
                 date('d',$cd)+$day, date('Y',$cd)+$yr));
      return strtotime($newdate);
              }

function ext_DateAdd2($date, $number, $interval=d) {

    $date_time_array = getdate($date);
    $hours = $date_time_array['hours'];
    $minutes = $date_time_array['minutes'];
    $seconds = $date_time_array['seconds'];
    $month = $date_time_array['mon'];
    $day = $date_time_array['mday'];
    $year = $date_time_array['year'];

    switch ($interval) {
    
        case 'yyyy':
            $year+=$number;
            break;
        case 'q':
            $year+=($number*3);
            break;
        case 'm':
            $month+=$number;
            break;
        case 'y':
        case 'd':
        case 'w':
            $day+=$number;
            break;
        case 'ww':
            $day+=($number*7);
            break;
        case 'h':
            $hours+=$number;
            break;
        case 'n':
            $minutes+=$number;
            break;
        case 's':
            $seconds+=$number;
            break;            
    }
       $timestamp= mktime($hours,$minutes,$seconds,$month,$day,$year);
    return $timestamp;
}

// function date_diff($date1, $date2) {
//     $current = $date1;
//     $datetime2 = date_create($date2);
//     $count = 0;
//     while(date_create($current) < $datetime2){
//         $current = gmdate("Y-m-d", strtotime("+1 day", strtotime($current)));
//         $count++;
//     }
//     return $count;
// } 








/**************************************************************************/
function eclaircirCouleur($color, $plancher, $plafond){
include_once(XOOPS_ROOT_PATH . '/modules/extcal/class/colorTools.php');
  
  //$ct = new ColorTools();
   //return $ct->eclaircir($color,$plancher,$plafond);
   return ColorTools::eclaircir($color,$plancher,$plafond);
   
}
/**************************************************************************/


?>
