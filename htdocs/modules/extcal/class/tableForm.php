<?php
/**
 **/

defined('XOOPS_ROOT_PATH') or die('Restricted access');

xoops_load('XoopsForm');

/**
 * Form that will output as a theme-enabled HTML table
 *
 * Also adds JavaScript to validate required fields
 */
class XoopsTableForm extends XoopsForm
{

    /**
     * ad the balise html "table" to render 
     *
     * @var boolean
     */
    var $_addBaliseTable = '';







    /**
     * Gets the "value" attribute of a form element
     *
     * @param string $name the "name" attribute of a form element
     * @param bool $encode To sanitizer the text?
     * @return string the "value" attribute assigned to a form element, null if not set
     */
    function setAddBaliseTable($addBaliseTable)
    {
        $this->_addBaliseTable = $addBaliseTable;
        return;
    }
    
    /**
     * gets the "value" attribute of all form elements
     *
     * @param bool $encode To sanitizer the text?
     * @return array array of name/value pairs assigned to form elements
     */
    function getAddBaliseTable()
    {
        return $this->_addBaliseTable;
    }






    /**
     * Insert an empty row in the table to serve as a seperator.
     *
     * @param string $extra HTML to be displayed in the empty row.
     * @param string $class CSS class name for <td> tag
     */
    function insertBreak($extra = '', $class = '')
    {
        $class = ($class != '') ? " class='" . preg_replace('/[^A-Za-z0-9\s\s_-]/i', '', $class) . "'" : '';
        // Fix for $extra tag not showing
        if ($extra) {
            $extra = '<tr><td colspan="2" ' . $class . '>' . $extra . '</td></tr>';
            $this->addElement($extra);
        } else {
            $extra = '<tr><td colspan="2" ' . $class . '>&nbsp;</td></tr>';
            $this->addElement($extra);
        }
    }

    /**
     * create HTML to output the form as a theme-enabled table with validation.
     *
     * YOU SHOULD AVOID TO USE THE FOLLOWING Nocolspan METHOD, IT WILL BE REMOVED
     *
     * To use the noColspan simply use the following example:
     *
     * $colspan = new XoopsFormDhtmlTextArea( '', 'key', $value, '100%', '100%' );
     * $colspan->setNocolspan();
     * $form->addElement( $colspan );
     *
     * @return string
     */

function render(){

        $addBaliseTable = $this->_addBaliseTable; 
        $title = $this->getTitle();
        $ret = '';

        if($addBaliseTable){
          $ret .= '<table width="100%" class="outer" cellspacing="1"> ';
        }

        if ($title <> '' ){
          $ret .= '<tr><th colspan="2">' . $title . '</th></tr>';
        }
        
        
        $hidden = '';
        $class = 'even';
        foreach ($this->getElements() as $ele) {
            if (!is_object($ele)) {
                $ret .= $ele;
            } else if (!$ele->isHidden()) {
                if (!$ele->getNocolspan()) {
                    $ret .= '<tr valign="top" align="left"><td class="head">';
                    if (($caption = $ele->getCaption()) != '') {
                        $ret .= '<div class="xoops-form-element-caption' . ($ele->isRequired() ? '-required' : '') . '">';
                        $ret .= '<span class="caption-text">' . $caption . '</span>';
                        $ret .= '<span class="caption-marker">*</span>';
                        $ret .= '</div>';
                    }
                    if (($desc = $ele->getDescription()) != '') {
                        $ret .= '<div class="xoops-form-element-help">' . $desc . '</div>';
                    }
                    $ret .= '</td><td class="' . $class . '">' . $ele->render() . '</td></tr>' . NWLINE;
                } else {
                    $ret .= '<tr valign="top" align="left"><td class="head" colspan="2">';
                    if (($caption = $ele->getCaption()) != '') {
                        $ret .= '<div class="xoops-form-element-caption' . ($ele->isRequired() ? '-required' : '') . '">';
                        $ret .= '<span class="caption-text">' . $caption . '</span>';
                        $ret .= '<span class="caption-marker">*</span>';
                        $ret .= '</div>';
                    }
                    $ret .= '</td></tr><tr valign="top" align="left"><td class="' . $class . '" colspan="2">' . $ele->render() . '</td></tr>';
                }
            } else {
                $hidden .= $ele->render();
            }
        }

        if($addBaliseTable){   
          $ret .= '</table>';
        }
        
        $ret .= NWLINE . ' ' . $hidden  . NWLINE;
        
        return $ret;
  
}

} // fin de la classe
?>