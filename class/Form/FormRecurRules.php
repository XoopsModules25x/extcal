<?php namespace XoopsModules\Extcal\Form;

use  XoopsModules\Extcal;

/**
 * Class FormRecurRules.
 */
class FormRecurRules extends \XoopsFormElement
{
    // Initial value form reccur form
    public $_rrule_freq             = 'none';
    public $_rrule_daily_interval   = '';
    public $_rrule_weekly_interval  = '';
    public $_rrule_weekly_bydays    = '';
    public $_rrule_monthly_interval = '';
    public $_rrule_monthly_byday    = '';
    public $_rrule_bymonthday       = '';
    public $_rrule_yearly_interval  = '';
    public $_rrule_yearly_bymonths  = '';
    public $_rrule_yearly_byday     = '';

    /**
     * @param $values
     */
    public function __construct($values)
    {
        if (isset($values['rrule_freq'])) {
            $this->_rrule_freq = $values['rrule_freq'];
        }
        if (isset($values['rrule_daily_interval'])) {
            $this->_rrule_daily_interval = $values['rrule_daily_interval'];
        }
        if (isset($values['rrule_weekly_interval'])) {
            $this->_rrule_weekly_interval = $values['rrule_weekly_interval'];
        }
        if (isset($values['rrule_weekly_bydays'])) {
            $this->_rrule_weekly_bydays = $values['rrule_weekly_bydays'];
        }
        if (isset($values['rrule_monthly_interval'])) {
            $this->_rrule_monthly_interval = $values['rrule_monthly_interval'];
        }
        if (isset($values['rrule_monthly_byday'])) {
            $this->_rrule_monthly_byday = $values['rrule_monthly_byday'];
        }
        if (isset($values['rrule_bymonthday'])) {
            $this->_rrule_bymonthday = $values['rrule_bymonthday'];
        }
        if (isset($values['rrule_yearly_interval'])) {
            $this->_rrule_yearly_interval = $values['rrule_yearly_interval'];
        }
        if (isset($values['rrule_yearly_bymonths'])) {
            $this->_rrule_yearly_bymonths = $values['rrule_yearly_bymonths'];
        }
        if (isset($values['rrule_yearly_byday'])) {
            $this->_rrule_yearly_byday = $values['rrule_yearly_byday'];
        }
    }

    /**
     * prepare HTML for output.
     *
     * @return string
     */
    public function render()
    {
        $ret = '';

        $formObject = new \XoopsFormRadio('', 'rrule_freq', $this->_rrule_freq);
        $formObject->addOption('none', _MD_EXTCAL_NO_RECCUR_EVENT);
        $ret .= $formObject->render();
        $ret .= '<br><br><fieldset><legend>' . _MD_EXTCAL_RECCUR_POLICY . '</legend><fieldset><legend>';

        $formObject = new \XoopsFormRadio('', 'rrule_freq', $this->_rrule_freq);
        $formObject->addOption('daily', _MD_EXTCAL_DAILY);
        $ret .= $formObject->render();
        $ret .= '</legend>' . _MD_EXTCAL_DURING . ' ';

        $formObject = new \XoopsFormText('', 'rrule_daily_interval', 3, 2, $this->_rrule_daily_interval);
        $ret        .= $formObject->render();
        $ret        .= ' ' . _MD_EXTCAL_DAYS . '</fieldset><br><fieldset><legend>';

        $formObject = new \XoopsFormRadio('', 'rrule_freq', $this->_rrule_freq);
        $formObject->addOption('weekly', _MD_EXTCAL_WEEKLY);
        $ret .= $formObject->render();
        $ret .= '</legend>' . _MD_EXTCAL_DURING . ' ';

        $formObject = new \XoopsFormText('', 'rrule_weekly_interval', 3, 2, $this->_rrule_weekly_interval);
        $ret        .= $formObject->render();
        $ret        .= ' ' . _MD_EXTCAL_WEEKS . '<br>';

        $formObject = new \XoopsFormCheckBox('', 'rrule_weekly_bydays', $this->_rrule_weekly_bydays);
        $formObject->addOption('MO', _MD_EXTCAL_MO2 . '&nbsp;');
        $formObject->addOption('TU', _MD_EXTCAL_TU2 . '&nbsp;');
        $formObject->addOption('WE', _MD_EXTCAL_WE2 . '&nbsp;');
        $formObject->addOption('TH', _MD_EXTCAL_TH2 . '&nbsp;');
        $formObject->addOption('FR', _MD_EXTCAL_FR2 . '&nbsp;');
        $formObject->addOption('SA', _MD_EXTCAL_SA2 . '&nbsp;');
        $formObject->addOption('SU', _MD_EXTCAL_SU2 . '&nbsp;');
        $ret .= $formObject->render();
        $ret .= '</fieldset><br><fieldset><legend>';

        $formObject = new \XoopsFormRadio('', 'rrule_freq', $this->_rrule_freq);
        $formObject->addOption('monthly', _MD_EXTCAL_MONTHLY);
        $ret .= $formObject->render();
        $ret .= '</legend>' . _MD_EXTCAL_DURING . ' ';

        $formObject = new \XoopsFormText('', 'rrule_monthly_interval', 3, 2, $this->_rrule_monthly_interval);
        $ret        .= $formObject->render();
        $ret        .= ' ' . _MD_EXTCAL_MONTH . ', ' . _MD_EXTCAL_ON . ' ';

        $formObject = new \XoopsFormSelect('', 'rrule_monthly_byday', $this->_rrule_monthly_byday);
        $formObject->addOption('', '&nbsp;');
        $formObject->addOption('1MO', _MD_EXTCAL_1_MO);
        $formObject->addOption('1TU', _MD_EXTCAL_1_TU);
        $formObject->addOption('1WE', _MD_EXTCAL_1_WE);
        $formObject->addOption('1TH', _MD_EXTCAL_1_TH);
        $formObject->addOption('1FR', _MD_EXTCAL_1_FR);
        $formObject->addOption('1SA', _MD_EXTCAL_1_SA);
        $formObject->addOption('1SU', _MD_EXTCAL_1_SU);
        $formObject->addOption('2MO', _MD_EXTCAL_2_MO);
        $formObject->addOption('2TU', _MD_EXTCAL_2_TU);
        $formObject->addOption('2WE', _MD_EXTCAL_2_WE);
        $formObject->addOption('2TH', _MD_EXTCAL_2_TH);
        $formObject->addOption('2FR', _MD_EXTCAL_2_FR);
        $formObject->addOption('2SA', _MD_EXTCAL_2_SA);
        $formObject->addOption('2SU', _MD_EXTCAL_2_SU);
        $formObject->addOption('3MO', _MD_EXTCAL_3_MO);
        $formObject->addOption('3TU', _MD_EXTCAL_3_TU);
        $formObject->addOption('3WE', _MD_EXTCAL_3_WE);
        $formObject->addOption('3TH', _MD_EXTCAL_3_TH);
        $formObject->addOption('3FR', _MD_EXTCAL_3_FR);
        $formObject->addOption('3SA', _MD_EXTCAL_3_SA);
        $formObject->addOption('3SU', _MD_EXTCAL_3_SU);
        $formObject->addOption('4MO', _MD_EXTCAL_4_MO);
        $formObject->addOption('4TU', _MD_EXTCAL_4_TU);
        $formObject->addOption('4WE', _MD_EXTCAL_4_WE);
        $formObject->addOption('4TH', _MD_EXTCAL_4_TH);
        $formObject->addOption('4FR', _MD_EXTCAL_4_FR);
        $formObject->addOption('4SA', _MD_EXTCAL_4_SA);
        $formObject->addOption('4SU', _MD_EXTCAL_4_SU);
        $formObject->addOption('-1MO', _MD_EXTCAL_LAST_MO);
        $formObject->addOption('-1TU', _MD_EXTCAL_LAST_TU);
        $formObject->addOption('-1WE', _MD_EXTCAL_LAST_WE);
        $formObject->addOption('-1TH', _MD_EXTCAL_LAST_TH);
        $formObject->addOption('-1FR', _MD_EXTCAL_LAST_FR);
        $formObject->addOption('-1SA', _MD_EXTCAL_LAST_SA);
        $formObject->addOption('-1SU', _MD_EXTCAL_LAST_SU);
        $ret .= $formObject->render();
        $ret .= ' ' . _MD_EXTCAL_OR_THE . ' ';

        $formObject = new \XoopsFormText('', 'rrule_bymonthday', 3, 2, $this->_rrule_bymonthday);
        $ret        .= $formObject->render();
        $ret        .= ' ' . _MD_EXTCAL_DAY_NUM_MONTH . '</fieldset><br><fieldset><legend>';

        $formObject = new \XoopsFormRadio('', 'rrule_freq', $this->_rrule_freq);
        $formObject->addOption('yearly', _MD_EXTCAL_YEARLY);
        $ret .= $formObject->render();
        $ret .= '</legend>' . _MD_EXTCAL_DURING . ' ';

        $formObject = new \XoopsFormText('', 'rrule_yearly_interval', 3, 2, $this->_rrule_yearly_interval);
        $ret        .= $formObject->render();
        $ret        .= ' ' . _MD_EXTCAL_YEARS . '<br>';

        $formObject = new Extcal\Form\FormRRuleCheckBox('', 'rrule_yearly_bymonths', $this->_rrule_yearly_bymonths);
        $formObject->addOption('1', _MD_EXTCAL_JAN);
        $formObject->addOption('2', _MD_EXTCAL_FEB);
        $formObject->addOption('3', _MD_EXTCAL_MAR);
        $formObject->addOption('4', _MD_EXTCAL_APR);
        $formObject->addOption('5', _MD_EXTCAL_MAY);
        $formObject->addOption('6', _MD_EXTCAL_JUN);
        $formObject->addOption('7', _MD_EXTCAL_JUL);
        $formObject->addOption('8', _MD_EXTCAL_AUG);
        $formObject->addOption('9', _MD_EXTCAL_SEP);
        $formObject->addOption('10', _MD_EXTCAL_OCT);
        $formObject->addOption('11', _MD_EXTCAL_NOV);
        $formObject->addOption('12', _MD_EXTCAL_DEC);
        $ret .= $formObject->render();
        $ret .= '<br>';

        $formObject = new \XoopsFormSelect('', 'rrule_yearly_byday', $this->_rrule_yearly_byday);
        $formObject->addOption('', _MD_EXTCAL_SAME_ST_DATE);
        $formObject->addOption('1MO', _MD_EXTCAL_1_MO);
        $formObject->addOption('1TU', _MD_EXTCAL_1_TU);
        $formObject->addOption('1WE', _MD_EXTCAL_1_WE);
        $formObject->addOption('1TH', _MD_EXTCAL_1_TH);
        $formObject->addOption('1FR', _MD_EXTCAL_1_FR);
        $formObject->addOption('1SA', _MD_EXTCAL_1_SA);
        $formObject->addOption('1SU', _MD_EXTCAL_1_SU);
        $formObject->addOption('2MO', _MD_EXTCAL_2_MO);
        $formObject->addOption('2TU', _MD_EXTCAL_2_TU);
        $formObject->addOption('2WE', _MD_EXTCAL_2_WE);
        $formObject->addOption('2TH', _MD_EXTCAL_2_TH);
        $formObject->addOption('2FR', _MD_EXTCAL_2_FR);
        $formObject->addOption('2SA', _MD_EXTCAL_2_SA);
        $formObject->addOption('2SU', _MD_EXTCAL_2_SU);
        $formObject->addOption('3MO', _MD_EXTCAL_3_MO);
        $formObject->addOption('3TU', _MD_EXTCAL_3_TU);
        $formObject->addOption('3WE', _MD_EXTCAL_3_WE);
        $formObject->addOption('3TH', _MD_EXTCAL_3_TH);
        $formObject->addOption('3FR', _MD_EXTCAL_3_FR);
        $formObject->addOption('3SA', _MD_EXTCAL_3_SA);
        $formObject->addOption('3SU', _MD_EXTCAL_3_SU);
        $formObject->addOption('4MO', _MD_EXTCAL_4_MO);
        $formObject->addOption('4TU', _MD_EXTCAL_4_TU);
        $formObject->addOption('4WE', _MD_EXTCAL_4_WE);
        $formObject->addOption('4TH', _MD_EXTCAL_4_TH);
        $formObject->addOption('4FR', _MD_EXTCAL_4_FR);
        $formObject->addOption('4SA', _MD_EXTCAL_4_SA);
        $formObject->addOption('4SU', _MD_EXTCAL_4_SU);
        $formObject->addOption('-1MO', _MD_EXTCAL_LAST_MO);
        $formObject->addOption('-1TU', _MD_EXTCAL_LAST_TU);
        $formObject->addOption('-1WE', _MD_EXTCAL_LAST_WE);
        $formObject->addOption('-1TH', _MD_EXTCAL_LAST_TH);
        $formObject->addOption('-1FR', _MD_EXTCAL_LAST_FR);
        $formObject->addOption('-1SA', _MD_EXTCAL_LAST_SA);
        $formObject->addOption('-1SU', _MD_EXTCAL_LAST_SU);
        $ret .= $formObject->render();
        $ret .= '</fieldset></fieldset>';

        return $ret;
    }
}
