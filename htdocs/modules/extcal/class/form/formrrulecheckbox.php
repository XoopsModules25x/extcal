<?php

/**
 * Class ExtcalFormRRuleCheckBox
 */
class ExtcalFormRRuleCheckBox extends XoopsFormCheckBox
{

    /**
     * @param      $caption
     * @param      $name
     * @param null $value
     */
    function ExtcalFormRRuleCheckBox($caption, $name, $value = null)
    {
        $this->XoopsFormCheckBox($caption, $name, $value);
    }

    /**
     * prepare HTML for output
     *
     * @return string
     */
    function render()
    {
        $ret = "<table><tr>";
        $i   = 0;
        if (count($this->getOptions()) > 1
            && substr($this->getName(), -2, 2) != "[]"
        ) {
            $newname = $this->getName() . "[]";
            $this->setName($newname);
        }
        foreach (
            $this->getOptions() as $value => $name
        ) {
            if ((++$i) % 6 == 0) {
                $ret .= "</tr><tr>";
            }
            $ret .= "<td><input type='checkbox' name='" . $this->getName() . "' value='" . $value . "'";
            if (count($this->getValue()) > 0
                && in_array($value, $this->getValue())
            ) {
                $ret .= " checked='checked'";
            }
            $ret .= $this->getExtra() . " />" . $name . "</td>\n";
        }
        $ret .= "</tr></table>";

        return $ret;
    }

}
