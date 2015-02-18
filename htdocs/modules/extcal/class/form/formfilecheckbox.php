<?php

/**
 * Class ExtcalFormFileCheckBox
 */
class ExtcalFormFileCheckBox extends XoopsFormCheckBox
{

    /**
     * @param      $caption
     * @param      $name
     * @param null $value
     */
    function ExtcalFormFileCheckBox($caption, $name, $value = null)
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
        $ret = "";
        if (count($this->getOptions()) > 1
            && substr($this->getName(), -2, 2) != "[]"
        ) {
            $newname = $this->getName() . "[]";
            $this->setName($newname);
        }
        foreach (
            $this->getOptions() as $value => $name
        ) {
            $ret
                .= "<input type='checkbox' name='" . $this->getName() . "' value='" . $value . "'";
            $ret .= " checked='checked'";
            $ret .= $this->getExtra() . " />" . $name . "<br />\n";
        }

        return $ret;
    }

}
