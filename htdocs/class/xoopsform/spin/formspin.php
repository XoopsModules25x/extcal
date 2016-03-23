<?php
/**
 * XoopsFormSpin element  -  Spin bytton
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         kernel
 * @subpackage      form
 * @since           2.0.0
 * @author          Jean-Jacques DELALANDRE <JJD@kiolo.com>
 * @version         XoopsFormSpin v 1.2
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

xoops_load('XoopsFormElement');

/**
 * A select field
 *
 * @author      Jean-Jacques DELALANDRE <jjd@kiolo.com>
 * @copyright   JJD http:xoops.kiolo.com
 * @access      public
 */

/*----------------------------------------------------------*/
/* set here the folder of the clas relative at the root     */
/*----------------------------------------------------------*/
define('_SPIN_FOLDER', '/class/xoopsform/spin/');

/*----------------------------------------------------------*/

/**
 * Class XoopsFormSpin
 */
class XoopsFormSpin extends XoopsFormElement
{

    /**
     * Value
     *
     * @var integer
     * @access private
     */
    public $_value = 0;

    /**
     * Value minimum
     *
     * @var integer
     * @access private
     */
    public $_min = 0;

    /**
     * Value maximum
     *
     * @var integer
     * @access private
     */
    public $_max = 100;

    /**
     * Small increment
     *
     * @var integer
     * @access private
     */
    public $_smallIncrement = 1;

    /**
     * Large increment
     *
     * @var integer
     * @access private
     */
    public $_largeIncrement = 10;

    /**
     *  unite for information on value
     *
     * @var string
     * @access private
     */
    public $_unite = '';

    /**
     * Folder of arrow image
     *
     * @var string
     * @access private
     */
    public $_imgFolder = 'default';

    /**
     * size of input text in nb car
     *
     * @var integer
     * @access private
     */
    public $_size = 2;

    /**
     *  minMaxVisible show buttons to go minimum and maximum
     *
     * @var integer
     * @access private
     */
    public $_minMaxVisible = true;

    /**
     *  tyleBordure ;  style CSS of frame control
     *
     * @var string
     * @access private
     */
    public $_styleBordure = 'color: #FFFFFF; background-color: #CCCCCC; line-height: 100%;border-width:1px; border-style: solid; border-color: #000000; margin-top: 0; margin-bottom: 0; padding: 0';

    /**
     *  tyleText : style CSS of input text
     *
     * @var string
     * @access private
     */
    public $_styleText = 'color: #000000; text-align: right; margin-left: 1; margin-right: 2; padding-right: 8';

    /**
     * Allow loading of javascript
     *
     * @var bool
     * @access private
     */
    public $_loadJS = true;

    /*---------------------------------------------------------------*/
    /**
     * Constructor
     *
     * @param string $caption      Caption
     * @param string $name         "name" attribute
     * @param int $value           Pre-selected value.
     * @param int $min             value
     * @param int $max             value
     * @param int $smallIncrement  Increment when click on button
     * @param int $largeIncrement  Increment when click on button
     * @param int $size            Number caractere of inputtext
     * @param string $unite        of the value
     * @param string $imgFolder    of image gif for button
     * @param string $styleText    style CSs of text
     * @param string $styleBordure style CSs of frame
     * @param bool $minMaxVisible  show min and mas buttons
     *
     */
    public function __construct($caption, $name, $value = 0, $min = 0, $max = 100, $smallIncrement = 1, $largeIncrement = 10, $size = 5, $unite = '', $imgFolder = 'default', $styleText = '', $styleBordure = '', $minMaxVisible = true)
    {
        $this->setName($name);
        $this->setCaption($caption);
        $this->setValue($value);
        $this->setMin($min);
        $this->setMax($max);
        $this->setSmallIncrement($smallIncrement);
        $this->setLargeIncrement($largeIncrement);
        $this->setSize($size);
        $this->setUnite($unite);
        $this->setImgFolder($imgFolder);
        $this->setStyleText($styleText);
        $this->setStyleBordure($styleBordure);
        $this->setMinMaxVisible($minMaxVisible);
    }

    /*-----------------------------------------------------------------*/
    /**
     * Get the values
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * Set the value
     *
     * @param  $value int
     */
    public function setValue($value)
    {
        $this->_value = $value;
    }

    /*-----------------------------------------------------------------*/
    /**
     * Get the min value
     */
    public function getMin()
    {
        return $this->_min;
    }

    /**
     * Set the min value
     *
     * @param  $min int
     */
    public function setMin($min)
    {
        $this->_min = (int)$min;
    }
    /*-----------------------------------------------------------------*/
    /**
     * Get the max value - must be more great then min
     */
    public function getMax()
    {
        return $this->_max;
    }

    /**
     * Set the max value - must be more great then min
     *
     * @param  $max int
     */
    public function setMax($max)
    {
        $this->_max = (int)$max;
    }

    /*-----------------------------------------------------------------*/
    /**
     * Get the small increment when click a short time on up down nutton
     */
    public function getSmallIncrement()
    {
        return $this->_smallIncrement;
    }

    /**
     * Set the small increment when click a short time on up down nutton
     * must be  " > 0 "
     *
     * @param $smallIncrement
     * @internal param int $value
     */
    public function setSmallIncrement($smallIncrement)
    {
        $this->_smallIncrement = (int)$smallIncrement;
        if ($this->_smallIncrement == 0) {
            $this->_smallIncrement = 1;
        }
    }

    /*-----------------------------------------------------------------*/
    /**
     * Get the large increment when click a long time on up down nutton
     */
    public function getLargeIncrement()
    {
        return $this->_largeIncrement;
    }

    /**
     * Set the large increment when click a long time on up down nutton
     *
     * @param  $largeIncrement int
     */
    public function setLargeIncrement($largeIncrement)
    {
        $this->_largeIncrement = (int)$largeIncrement;
        if ($this->_largeIncrement == 0) {
            $this->_largeIncrement = 10;
        }
    }

    /*-----------------------------------------------------------------*/
    /**
     * Get the size in nb car of the input text for the value
     */
    public function getSize()
    {
        return $this->_size;
    }

    /**
     * Set the size in nb car of the input text for the value
     * must be 2 car min
     *
     * @param  $size mixed
     */
    public function setSize($size)
    {
        $this->_size = $size;
        if ($this->_size == 0) {
            $this->_size = 2;
        }
    }

    /*-----------------------------------------------------------------*/
    /**
     * @return string
     */
    public function getImgFolder()
        /**
         * Get the shortname of the folder images
         */
    {
        return $this->_imgFolder;
    }

    /**
     * Set the shortname of the folder images
     *
     * @param  $folder string
     */
    public function setImgFolder($folder)
    {
        if ($folder <> '') {
            $this->_imgFolder = $folder;
        }
    }
    /*-----------------------------------------------------------------*/
    /**
     * Get the label of unites between value and buttons
     */
    public function getUnite()
    {
        return $this->_unite;
    }

    /**
     * Set the label of unites between value and buttons
     *
     * @param  $unite string
     */
    public function setUnite($unite)
    {
        $this->_unite = $unite;
    }
    /*-----------------------------------------------------------------*/
    /**
     * Get the style CSS of the text
     */
    public function getStyleText()
    {
        return $this->_styleText;
    }

    /**
     * Set the style CSS of the text
     *
     * @param  $style string
     */
    public function setStyleText($style)
    {
        if ($style <> '') {
            $this->_styleText = $style;
        }
    }
    /*-----------------------------------------------------------------*/
    /**
     * Get the style CSS of the frame
     */
    public function getStyleBordure()
    {
        return $this->_styleBordure;
    }

    /**
     * Set the style CSS of the frame
     *
     * @param  $style string
     */
    public function setStyleBordure($style)
    {
        if ($style <> '') {
            $this->_styleBordure = $style;
        }
    }
    /*-----------------------------------------------------------------*/
    /**
     * Get MinMaxVisible : show the button to go min and max value
     */
    public function getMinMaxVisible()
    {
        return $this->_minMaxVisible;
    }

    /**
     * Set  MinMaxVisible : show the button to go min and max value
     *
     * @param  $visible bool
     */
    public function setMinMaxVisible($visible)
    {
        $this->_minMaxVisible = $visible;
    }
    /**********************************************************************/

    /**
     * Prepare HTML for output
     *
     * @return string HTML
     */
    public function render()
    {
        $sSpinFolder = $this->getFolder();
        $sFolderImg  = "{$sSpinFolder}/images/{$this->getImgFolder()}/";

        $prefixe  = $this->getName();
        $prefixe2 = 'spin' . $prefixe;

        $smallIncrement = $this->getSmallIncrement();
        $largeIncrement = $this->getLargeIncrement();

        /*----------------------------------------------*/
        $delai        = 200;
        $onMouseDown1 = "spinStart(\"{$prefixe}\", \"{$prefixe2}\",  {$smallIncrement},  {$largeIncrement}, {$delai}, \"{$sFolderImg}spinUp1.gif\");";
        $onMouseDown2 = "spinStart(\"{$prefixe}\", \"{$prefixe2}\", -{$smallIncrement}, -{$largeIncrement}, {$delai}, \"{$sFolderImg}spinDown1.gif\");";

        $onMouseUp = 'spinStop();';
        //----------------------------------------------------------------
        $styleBordure = $this->htmlAddAttribut('style', $this->getStyleBordure());
        $styleText    = $this->htmlAddAttribut('style', $this->getStyleText());
        $styleArrow   = "style=\"display: table-cell;vertical-align: middle; text-align: center; line-height: 100%; font-size: 7 pt; margin-top: 0; margin-bottom: 0; padding: 0\"";
        //----------------------------------------------------------------
        $t = array();

        if ($this->_loadJS) {
            $js  = $sSpinFolder . '/js/spin.js';
            $t[] = "<script src='{$js}' type='text/javascript'></script>";
        }

        $t[] = "<div STYLE='width:50px;'>";
        //$t[] = "<table border='0' width='8%' cellpadding='0' cellspacing='0'>";
        $t[] = "<table border='0' width='8%' cellpadding='0' cellspacing='0' {$styleBordure}>";
        $t[] = '  <tr>';
        //$t[] = "    <td width='60%'>{$Caption}</td>";
        $t[] = "    <td width='60%'>";
        $t[] = "      <INPUT TYPE='hidden' NAME='{$prefixe2}_min' VALUE='{$this->getMin()}'>";
        $t[] = "      <INPUT TYPE='hidden' NAME='{$prefixe2}_max' VALUE='{$this->getMax()}'>";
        $t[] = "      <INPUT TYPE='hidden' NAME='{$prefixe2}_smallIncrement' VALUE='{$this->_smallIncrement}'  style='text-align: right;'>";
        $t[] = "      <input type='text'  name='{$prefixe}' size='{$this->getSize()}' value='{$this->getValue()}' {$styleText}>";
        $t[] = '    </td>';

        $unite = $this->getUnite();
        if ($unite <> '') {
            $t[] = "    <td style='display: table-cell;vertical-align: middle; '>&nbsp;{$unite}&nbsp;</td>";
        }
        //-------------------------------------------------------
        if ($this->getMinMaxVisible()) {
            $onMouseDownMin = "spinSetValue(\"{$prefixe}\", \"{$prefixe2}\",  \"Min\", {$this->getMin()}, {$delai}, \"{$sFolderImg}spinMin1.gif\");";
            $t[]            = "    <td width='63%' align='center' {$styleArrow}>";
            $t[]            = "      <img border='0' name='{$prefixe2}_imgMin' src='{$sFolderImg}spinMin0.gif'   onmousedown='{$onMouseDownMin}'><br>";
            $t[]            = '    </td>';
        }
        //-------------------------------------------------------
        $t[] = "    <td width='63%' align='center' {$styleArrow}>";

        $t[] = "      <img border='0' name='{$prefixe2}_img0' src='{$sFolderImg}spinUp0.gif'   onmousedown='{$onMouseDown1}' onmouseup='{$onMouseUp}' onmouseout='{$onMouseUp}'><br>";
        $t[] = "      <img border='0' name='{$prefixe2}_img1' src='{$sFolderImg}spinDown0.gif' onmousedown='{$onMouseDown2}' onmouseup='{$onMouseUp}' onmouseout='{$onMouseUp}'>";

        $t[] = '    </td>';

        //-------------------------------------------------------
        if ($this->getMinMaxVisible()) {
            $onMouseDownMax = "spinSetValue(\"{$prefixe}\", \"{$prefixe2}\",  \"Max\", {$this->getMax()}, {$delai}, \"{$sFolderImg}spinMax1.gif\");";
            $t[]            = "    <td width='63%' align='center' {$styleArrow}>";
            $t[]            = "      <img border='0' name='{$prefixe2}_imgMax' src='{$sFolderImg}spinMax0.gif'   onmousedown='{$onMouseDownMax}'><br>";
            $t[]            = '    </td>';
        }
        //-------------------------------------------------------

        $t[] = '  </tr>';
        $t[] = '</table>' . "\n";
        $t[] = '</div>';
        //-------------------------------------------
        $html = implode("\n", $t);

        return $html;
    }

    /**************************************************************************
     * calcul du dossier du composant
     *************************************************************************/
    public function getFolder()
    {
        $sSpinFolder = XOOPS_URL . _SPIN_FOLDER;

        return $sSpinFolder;
    }

    /********************************************************************
     *
     ********************************************************************
     * @param $attribut
     * @param $value
     * @param string $default
     * @return string
     */
    public function htmlAddAttribut($attribut, $value, $default = '')
    {
        $r = '';
        if ($value == '') {
            $value = $default;
        }

        if ($value <> '') {
            if (substr($value, 0, strlen($attribut)) <> $attribut) {
                $r = "{$attribut}=\"{$value}\"";
            }
            return $r;
        }

        /*-----------------------------------------------*/
        /*---          fin de la classe               ---*/
        /*-----------------------------------------------*/
    }
}
