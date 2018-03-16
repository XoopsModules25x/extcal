<?php namespace XoopsModules\Extcal\Form;

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

/**
 * Class FormDateTime.
 */
class FormDateTime
{
    /**
     * @param ThemeForm $form
     * @param int       $startTS
     * @param int       $endTS
     *
     */
    public function __construct($form, $startTS = 0, $endTS = 0)
    {
        $startTS       = (int)$startTS;
        $startTS       = ($startTS > 0) ? $startTS : time();
        $startDatetime = getdate($startTS);

        $endTS       = (int)$endTS;
        $endTS       = ($endTS > 0) ? $endTS : time();
        $endDatetime = getdate($endTS);

        $timearray = [];
        for ($i = 0; $i < 24; ++$i) {
            for ($j = 0; $j < _EXTCAL_TS_MINUTE; $j += 15) {
                $key             = ($i * _EXTCAL_TS_HOUR) + ($j * _EXTCAL_TS_MINUTE);
                $timearray[$key] = (0 != $j) ? $i . ':' . $j : $i . ':0' . $j;
            }
        }
        ksort($timearray);

        // test new calendar
        //         require_once (XOOPS_ROOT_PATH . "/class/xoopsform/dateselect/formdateselect.php");
        //         $test = new \XoopsFormDateSelect('', 'event_start[date]', 15, $startTS);
        //         $test->setExtra('onBlur=\'validDate("event_start[date]", "event_start[time]", "event_end[date]", "event_end[time]");\'');
        //         $form->addElement($test);

        // Start date element's form
        $startElmtTray = new \XoopsFormElementTray(_MD_EXTCAL_START_DATE, '&nbsp;');

        $startDate = new \XoopsFormTextDateSelect('', 'event_start[date]', 15, $startTS);
        $startDate->setExtra('onBlur=\'validDate("event_start[date]", "event_start[time]", "event_end[date]", "event_end[time]");\'');
        $startElmtTray->addElement($startDate);

        $startTime = new \XoopsFormSelect('', 'event_start[time]', $startDatetime['hours'] * _EXTCAL_TS_HOUR + 600 * ceil($startDatetime['minutes'] / 10));
        $startTime->setExtra('onChange=\'validDate("event_start[date]", "event_start[time]", "event_end[date]", "event_end[time]");\'');
        $startTime->addOptionArray($timearray);
        $startElmtTray->addElement($startTime);

        $form->addElement($startElmtTray, true);

        // End date element's form
        $endElmtTray     = new \XoopsFormElementTray(_MD_EXTCAL_END_DATE, '<br>');
        $endDateElmtTray = new \XoopsFormElementTray('', '&nbsp;');

        $endElmtTray->addElement(new \XoopsFormRadioYN(_MD_EXTCAL_EVENT_END, 'have_end', 1));

        $endDate = new \XoopsFormTextDateSelect('', 'event_end[date]', 15, $endTS);
        $endDate->setExtra('onBlur=\'validDate("event_start[date]", "event_start[time]", "event_end[date]", "event_end[time]");\'');
        $endDateElmtTray->addElement($endDate);

        $endTime = new \XoopsFormSelect('', 'event_end[time]', $endDatetime['hours'] * _EXTCAL_TS_HOUR + 600 * ceil($endDatetime['minutes'] / 10));
        $endTime->setExtra('onChange=\'validDate("event_start[date]", "event_start[time]", "event_end[date]", "event_end[time]");\'');
        $endTime->addOptionArray($timearray);
        $endDateElmtTray->addElement($endTime);

        $endElmtTray->addElement($endDateElmtTray);
        $form->addElement($endElmtTray);
    }
}
