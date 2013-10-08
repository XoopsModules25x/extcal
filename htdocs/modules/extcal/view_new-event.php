<?php

include_once  ('../../mainfile.php');
include_once ('include/constantes.php');
$params = array('view' => _EXTCAL_NAV_NEW_EVENT, 'file' => _EXTCAL_FILE_NEW_EVENT);
$GLOBALS['xoopsOption']['template_main'] = "extcal_view_{$params['view']}.html";
include_once ('header.php');

/* ========================================================================== */
//ext_echoArray($_GET);

$eventId = ((isset($_GET['event'])) ?  $_GET['event']  : 0);
$action  = ((isset($_GET['action'])) ? $_GET['action'] : 'edit');

//------------------------------------------------------------------------------


//exit;
if (count($permHandler->getAuthorizedCat($xoopsUser, 'extcal_cat_submit')) > 0
) {

    include XOOPS_ROOT_PATH . '/header.php';

    // Title of the page
    $xoopsTpl->assign('xoops_pagetitle', _MI_EXTCAL_SUBMIT_EVENT);

    // Display the submit form
    if($eventId==0){
      $form = $eventHandler->getEventForm();
    }else{
      $form = $eventHandler->getEventForm('user', $action, array('event_id' => $eventId));
    }
    $xoopsTpl->assign('formEdit', $form->render());
   
  //-----------------------------------------------
  $xoopsTpl->assign('params', $params);
  
  $tNavBar = getNavBarTabs($params['view']);
  $xoopsTpl->assign('tNavBar', $tNavBar);
  $xoopsTpl->assign('list_position', -1);
  //-----------------------------------------------      
    
    //$form->display();

    include XOOPS_ROOT_PATH . '/footer.php';

} else {
    redirect_header("index.php", 3);
}


















