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
     * @return ExtcalFormFileCheckBox
     */
    public function __construct($caption, $name, $value = null)
    {
        parent::__construct($caption, $name, $value);
    }

    /**
     * prepare HTML for output
     *
     * @return string
     */
    public function render()
    {
        $ret = '';
        if (count($this->getOptions()) > 1 && substr($this->getName(), -2, 2) !== '[]') {
            $newname = $this->getName() . '[]';
            $this->setName($newname);
        }
        foreach ($this->getOptions() as $value => $name) {
            $ret .= "<input type='checkbox' name='" . $this->getName() . "' value='" . $value . "'";
            $ret .= " checked='checked'";
            $ret .= $this->getExtra() . ' />' . $name . "<br />\n";
        }

        return $ret;
    }
}
