<?php namespace XoopsModules\Extcal\Form;

/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    {@link https://xoops.org/ XOOPS Project}
 * @license      {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package      extcal
 * @since
 * @author       XOOPS Development Team,
 */

/**
 * Class FormRRuleCheckBox.
 */
class FormRRuleCheckBox extends \XoopsFormCheckBox
{
    /**
     * @param      $caption
     * @param      $name
     * @param null $value
     */
    public function __construct($caption, $name, $value = null)
    {
        parent::__construct($caption, $name, $value);
    }

    /**
     * prepare HTML for output.
     *
     * @return string
     */
    public function render()
    {
        $ret = '<table><tr>';
        $i   = 0;
        if (count($this->getOptions()) > 1 && '[]' !== substr($this->getName(), -2, 2)) {
            $newname = $this->getName() . '[]';
            $this->setName($newname);
        }
        foreach ($this->getOptions() as $value => $name) {
            if (0 == (++$i) % 6) {
                $ret .= '</tr><tr>';
            }
            $ret .= "<td><input type='checkbox' name='" . $this->getName() . "' value='" . $value . "'";
            if (count($this->getValue()) > 0 && in_array($value, $this->getValue())) {
                $ret .= ' checked';
            }
            $ret .= $this->getExtra() . '>' . $name . "</td>\n";
        }
        $ret .= '</tr></table>';

        return $ret;
    }
}
