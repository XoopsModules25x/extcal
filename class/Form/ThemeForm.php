<?php namespace XoopsModules\Extcal\Form;

//include __DIR__ . '/formdatetime.php';
//include __DIR__ . '/formrecurrules.php';
//include __DIR__ . '/formfilecheckbox.php';
//include __DIR__ . '/FormRRuleCheckBox.php';

/**
 * Class ThemeForm.
 */
class ThemeForm extends \XoopsThemeForm
{
    /**
     * create HTML to output the form as a theme-enabled table with validation.
     *
     * @return string
     */
    public function render()
    {
        $ret = "\n<script type=\"text/javascript\">\n";
        $ret .= "function validDate(startDateInput, startTimeSelect, endDateInput, endTimeSelect) {\n";
        $ret .= "startDateInput = document.getElementById(startDateInput);\n";
        $ret .= "startTimeSelect = document.getElementById(startTimeSelect);\n";
        $ret .= "endDateInput = document.getElementById(endDateInput);\n";
        $ret .= "endTimeSelect = document.getElementById(endTimeSelect);\n";

        $ret .= "var pattern = new RegExp(\"-\", \"g\");\n";

        $ret .= "var startDateString = startDateInput.value;\n";
        $ret .= "var startDateArray = startDateString.split(pattern);\n";
        $ret .= "var startDate = new Date(startDateArray[0], startDateArray[2], startDateArray[1]);\n";

        $ret .= "var endDateString = endDateInput.value;\n";
        $ret .= "var endDateArray = endDateString.split(pattern);\n";
        $ret .= "var endDate = new Date(endDateArray[0], endDateArray[2], endDateArray[1]);\n";

        $ret .= "if ((startDate.getTime() + startTimeSelect.options[startTimeSelect.selectedIndex].value) > (endDate.getTime() + endTimeSelect.options[endTimeSelect.selectedIndex].value)) {\n";
        $ret .= "endDateInput.value = startDateInput.value;\n";
        $ret .= "endTimeSelect.selectedIndex = startTimeSelect.selectedIndex;\n";
        $ret .= "}\n";

        $ret .= "}\n";
        $ret .= "</script>\n";

        $ret .= parent::render();

        return $ret;
    }
}
