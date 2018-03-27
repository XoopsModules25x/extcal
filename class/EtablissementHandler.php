<?php namespace XoopsModules\Extcal;

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

//Kraven 30
// defined('XOOPS_ROOT_PATH') || die('Restricted access');

require_once XOOPS_ROOT_PATH . '/kernel/object.php';

/**
 * Class EtablissementHandler.
 */
class EtablissementHandler extends ExtcalPersistableObjectHandler
{
    /**
     * @param $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'extcal_etablissement', Etablissement::class, 'id', 'nom');
    }

    /**
     * @param      $etablissementId
     * @param bool $skipPerm
     *
     * @return bool
     */
    public function getEtablissement($etablissementId, $skipPerm = false)
    {
        $user = $GLOBALS['xoopsUser'];

        $criteriaCompo = new \CriteriaCompo();
        $criteriaCompo->add(new \Criteria('id', $etablissementId));

        if (!$skipPerm) {
            $this->addCatPermCriteria($criteriaCompo, $user);
        }
        $ret =& $this->getObjects($criteriaCompo);
        if (isset($ret[0])) {
            return $ret[0];
        } else {
            return false;
        }
    }

    /**
     * @param \CriteriaElement $criteria
     * @param null             $fields
     * @param bool             $asObject
     * @param bool             $id_as_key
     *
     * @return array
     */
    public function &getAll(
        \CriteriaElement $criteria = null,
        $fields = null,
        $asObject = true,
        $id_as_key = true
    ) //getAll($criteria = null, $asObject = false)
    {
        $rst =& $this->getObjects($criteria, $asObject);
        if ($asObject) {
            return $rst;
        } else {
            return $this->objectToArray($rst);
        }
    }
}
