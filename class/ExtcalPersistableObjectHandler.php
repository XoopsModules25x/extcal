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

use XoopsModules\Extcal;

/**
 * Persistable Object Handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of derived class objects.
 *
 * @author    Jan Keller Pedersen <mithrandir@xoops.org> - IDG Danmark A/S <www.idg.dk>
 * @copyright copyright (c) 2000-2004 XOOPS.org
 */
class ExtcalPersistableObjectHandler extends \XoopsPersistableObjectHandler //XoopsObjectHandler
{
    /**#@+
     * Information about the class, the handler is managing
     *
     * @var string
     */
    //    public $table;
    //    public $keyName;
    //    public $className;
    //    public $identifierName;

    /**#@-*/

    /**
     * Constructor - called from child classes.
     *
     * @param \XoopsDatabase $db        {@link XoopsDatabase}
     *                                  object
     * @param string         $tablename Name of database table
     * @param string         $classname Name of Class, this handler is managing
     * @param string         $keyname   Name of the property, holding the key
     * @param bool           $idenfierName
     */
    public function __construct(\XoopsDatabase $db, $tablename, $classname, $keyname, $idenfierName = false)
    {
        parent::__construct($db);
        $this->table     = $db->prefix($tablename);
        $this->keyName   = $keyname;
        $this->className = $classname;
        if (false !== $idenfierName) {
            $this->identifierName = $idenfierName;
        }
    }

    /**
     * Constructor.
     */
    //    public function ExtcalPersistableObjectHandler($db, $tablename, $classname, $keyname, $idenfierName = false)
    //    {
    //        $this->__construct($db, $tablename, $classname, $keyname, $idenfierName);
    //    }

    /**
     * create a new user.
     *
     * @param bool $isNew Flag the new objects as "new"?
     *
     * @return \XoopsObject
     */
    public function create($isNew = true)
    {
        $obj = new $this->className();
        if (true === $isNew) {
            $obj->setNew();
        }

        return $obj;
    }

    /**
     * retrieve an object.
     *
     * @param mixed $id ID of the object - or array of ids for joint keys. Joint keys MUST be given in the same order as in the constructor
     * @param null  $fields
     * @param bool  $as_object
     *
     * @return mixed reference to the object, FALSE if failed
     *
     * @internal param bool $asObject whether to return an object or an array
     */
    public function get($id = null, $fields = null, $as_object = true) //get($id, $as_object = true)
    {
        if (is_array($this->keyName)) {
            $criteria = new \CriteriaCompo();
            for ($i = 0, $iMax = count($this->keyName); $i < $iMax; ++$i) {
                $criteria->add(new \Criteria($this->keyName[$i], (int)$id[$i]));
            }
        } else {
            $criteria = new \Criteria($this->keyName, (int)$id);
        }
        $criteria->setLimit(1);
        $objectArray =& $this->getObjects($criteria, false, true);
        if (1 != count($objectArray)) {
            return $this->create();
        }

        return $objectArray[0];
    }

    /**
     * retrieve objects from the database.
     *
     * @param \CriteriaElement $criteria {@link CriteriaElement} conditions to be met
     * @param bool             $idAsKey  use the ID as key for the array?
     * @param bool             $asObject return an array of objects?
     *
     * @return array
     */
    public function &getObjects(\CriteriaElement $criteria = null, $idAsKey = false, $asObject = true)
    {
        $ret   = [];
        $limit = $start = 0;
        $sql   = 'SELECT * FROM ' . $this->table;
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ('' != $criteria->getSort()) {
                $sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }

        $ret = $this->convertResultSet($result, $idAsKey, $asObject);

        return $ret;
    }

    /**
     * Convert a database resultset to a returnable array.
     *
     * @param \XoopsObject $result  database resultset
     * @param bool        $idAsKey - should NOT be used with joint keys
     * @param bool        $asObject
     *
     * @return array
     */
    public function convertResultSet($result, $idAsKey = false, $asObject = true)
    {
        $ret = [];
        while (false !== ($myrow = $this->db->fetchArray($result))) {
            $obj = $this->create(false);
            $obj->assignVars($myrow);
            if (!$idAsKey) {
                if ($asObject) {
                    $ret[] = $obj;
                } else {
                    $row  = [];
                    $vars =& $obj->getVars();
                    foreach (array_keys($vars) as $i) {
                        $row[$i] = $obj->getVar($i);
                    }
                    $ret[] = $row;
                }
            } else {
                if ($asObject) {
                    $ret[$myrow[$this->keyName]] = $obj;
                } else {
                    $row  = [];
                    $vars =& $obj->getVars();
                    foreach (array_keys($vars) as $i) {
                        $row[$i] = $obj->getVar($i);
                    }
                    $ret[$myrow[$this->keyName]] = $row;
                }
            }
            unset($obj);
        }

        return $ret;
    }

    /**
     * Retrieve a list of objects as arrays - DON'T USE WITH JOINT KEYS.
     *
     * @param \CriteriaElement $criteria {@link CriteriaElement} conditions to be met
     * @param int              $limit    Max number of objects to fetch
     * @param int              $start    Which record to start at
     *
     * @return array
     */
    public function getList(\CriteriaElement $criteria = null, $limit = 0, $start = 0)
    {
        $ret = [];
        if (null === $criteria) {
            $criteria = new \CriteriaCompo();
        }

        if ('' == $criteria->getSort()) {
            $criteria->setSort($this->identifierName);
        }

        $sql = 'SELECT ' . $this->keyName;
        if (!empty($this->identifierName)) {
            $sql .= ', ' . $this->identifierName;
        }
        $sql .= ' FROM ' . $this->table;
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ('' != $criteria->getSort()) {
                $sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }

        $myts = \MyTextSanitizer::getInstance();
        while (false !== ($myrow = $this->db->fetchArray($result))) {
            //identifiers should be textboxes, so sanitize them like that
            $ret[$myrow[$this->keyName]] = empty($this->identifierName) ? 1 : $myts->htmlSpecialChars($myrow[$this->identifierName]);
        }

        return $ret;
    }

    /**
     * count objects matching a condition.
     *
     * @param \CriteriaElement $criteria {@link CriteriaElement} to match
     *
     * @return int|array count of objects
     */
    public function getCount(\CriteriaElement $criteria = null)
    {
        $field   = '';
        $groupby = false;
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            if ('' != $criteria->groupby) {
                $groupby = true;
                $field   = $criteria->groupby . ', '; //Not entirely secure unless you KNOW that no criteria's groupby clause is going to be mis-used
            }
        }
        $sql = 'SELECT ' . $field . 'COUNT(*) FROM ' . $this->table;
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ('' != $criteria->groupby) {
                $sql .= $criteria->getGroupby();
            }
        }
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }
        if (false === $groupby) {
            list($count) = $this->db->fetchRow($result);

            return $count;
        } else {
            $ret = [];
            while (false !== (list($id, $count) = $this->db->fetchRow($result))) {
                $ret[$id] = $count;
            }

            return $ret;
        }
    }

    /**
     * delete an object from the database by id.
     *
     * @param mixed $id id of the object to delete
     * @param bool  $force
     *
     * @return bool FALSE if failed.
     */
    public function deleteById($id, $force = false) //delete(\XoopsObject $object, $force = false)
    {
        if (is_array($this->keyName)) {
            $clause = [];
            for ($i = 0, $iMax = count($this->keyName); $i < $iMax; ++$i) {
                $clause[] = $this->keyName[$i] . ' = ' . $id[$i];
            }
            $whereclause = implode(' AND ', $clause);
        } else {
            $whereclause = $this->keyName . ' = ' . $id;
        }
        $sql = 'DELETE FROM ' . $this->table . ' WHERE ' . $whereclause;
        if (false !== $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            return false;
        }

        return true;
    }

    /**
     * insert a new object in the database.
     *
     * @param \XoopsObject $obj         reference to the object
     * @param bool         $force       whether to force the query execution despite security settings
     * @param bool         $checkObject check if the object is dirty and clean the attributes
     *
     * @return bool FALSE if failed, TRUE if already present and unchanged or successful
     */
    public function insert(\XoopsObject $obj, $force = false, $checkObject = true)
    {
        if (false !== $checkObject) {
            if (!is_object($obj)) {
                //                var_dump($obj);
                return false;
            }

            if (!($obj instanceof $this->className && class_exists($this->className))) {
                $obj->setErrors(get_class($obj) . ' Differs from ' . $this->className);

                return false;
            }
        }
        if (!$obj->cleanVars()) {
            return false;
        }

        foreach ($obj->cleanVars as $k => $v) {
            if (XOBJ_DTYPE_INT == $obj->vars[$k]['data_type']) {
                $cleanvars[$k] = (int)$v;
            } elseif (is_array($v)) {
                $cleanvars[$k] = $this->db->quoteString(implode(',', $v));
            } else {
                $cleanvars[$k] = $this->db->quoteString($v);
            }
        }
        if ($obj->isNew()) {
            if (!is_array($this->keyName)) {
                if ($cleanvars[$this->keyName] < 1) {
                    $cleanvars[$this->keyName] = $this->db->genId($this->table . '_' . $this->keyName . '_seq');
                }
            }
            $sql = 'INSERT INTO ' . $this->table . ' (' . implode(',', array_keys($cleanvars)) . ') VALUES (' . implode(',', array_values($cleanvars)) . ')';
        } else {
            $sql = 'UPDATE ' . $this->table . ' SET';
            foreach ($cleanvars as $key => $value) {
                if ((!is_array($this->keyName) && $key == $this->keyName)
                    || (is_array($this->keyName)
                        && in_array($key, $this->keyName))) {
                    continue;
                }
                if (isset($notfirst)) {
                    $sql .= ',';
                }
                $sql      .= ' ' . $key . ' = ' . $value;
                $notfirst = true;
            }
            if (is_array($this->keyName)) {
                $whereclause = '';
                for ($i = 0, $iMax = count($this->keyName); $i < $iMax; ++$i) {
                    if ($i > 0) {
                        $whereclause .= ' AND ';
                    }
                    $whereclause .= $this->keyName[$i] . ' = ' . $obj->getVar($this->keyName[$i]);
                }
            } else {
                $whereclause = $this->keyName . ' = ' . $obj->getVar($this->keyName);
            }
            $sql .= ' WHERE ' . $whereclause;
        }
        if (false !== $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            return false;
        }
        if (!is_array($this->keyName) && $obj->isNew()) {
            $obj->assignVar($this->keyName, $this->db->getInsertId());
        }

        return true;
    }

    /**
     * Change a value for objects with a certain criteria.
     *
     * @param string           $fieldname  Name of the field
     * @param string|array     $fieldvalue Value to write
     * @param \CriteriaElement $criteria   {@link CriteriaElement}
     * @param bool             $force
     *
     * @return bool
     */
    public function updateAll($fieldname, $fieldvalue, \CriteriaElement $criteria = null, $force = false)
    {
        $setClause = $fieldname . ' = ';
        if (is_numeric($fieldvalue)) {
            $setClause .= $fieldvalue;
        } elseif (is_array($fieldvalue)) {
            $setClause .= $this->db->quoteString(implode(',', $fieldvalue));
        } else {
            $setClause .= $this->db->quoteString($fieldvalue);
        }
        $sql = 'UPDATE ' . $this->table . ' SET ' . $setClause;
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (false !== $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            return false;
        }

        return true;
    }

    /**
     * @param      $fieldname
     * @param      $fieldvalue
     * @param null $criteria
     * @param bool $force
     *
     * @return bool
     */
    public function updateFieldValue($fieldname, $fieldvalue, $criteria = null, $force = true)
    {
        $sql = 'UPDATE ' . $this->table . ' SET ' . $fieldname . ' = ' . $fieldvalue;
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (false !== $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            return false;
        }

        return true;
    }

    /**
     * delete all objects meeting the conditions.
     *
     * @param \CriteriaElement $criteria       {@link CriteriaElement}
     *                                         with conditions to meet
     * @param bool             $force
     * @param bool             $asObject
     *
     * @return bool
     */
    public function deleteAll(\CriteriaElement $criteria = null, $force = true, $asObject = false)
    {
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql = 'DELETE FROM ' . $this->table;
            $sql .= ' ' . $criteria->renderWhere();
            if (!$this->db->query($sql)) {
                return false;
            }
            $rows = $this->db->getAffectedRows();

            return $rows > 0 ? $rows : true;
        }

        return false;
    }

    /**
     * @param $data
     *
     * @return array
     */
    public function _toObject($data)
    {
        if (is_array($data)) {
            $ret = [];
            foreach ($data as $v) {
                $object = new $this->className();
                $object->assignVars($v);
                $ret[] = $object;
            }

            return $ret;
        } else {
            $object = new $this->className();
            $object->assignVars($v);

            return $object;
        }
    }

    /**
     * @param        $objects
     * @param array  $externalKeys
     * @param string $format
     *
     * @return array
     */
    public function objectToArray($objects, $externalKeys = [], $format = 's')
    {
        static $cache;
        if (!is_array($externalKeys)) {
            $externalKeys = [$externalKeys];
        } //JJD

        $ret = [];
        if (is_array($objects)) {
            $i = 0;
            foreach ($objects as $object) {
                $vars = $object->getVars();
                foreach ($vars as $k => $v) {
                    $ret[$i][$k] = $object->getVar($k, $format);
                }
                foreach ($externalKeys as $key) {
                    // Replace external key by corresponding object
                    $externalKey = $object->getExternalKey($key);
                    if (0 != $ret[$i][$key]) {
                        // Retrieving data if isn't cached
                        if (!isset($cached[$externalKey['keyName']][$ret[$i][$key]])) {
                            if ($externalKey['core']) {
                                $handler = xoops_getHandler($externalKey['className']);
                            } else {
                                $handler = Extcal\Helper::getInstance()->getHandler($externalKey['className']);
                            }
                            $getMethod                                       = $externalKey['getMethodeName'];
                            $cached[$externalKey['keyName']][$ret[$i][$key]] = $this->objectToArrayWithoutExternalKey($handler->$getMethod($ret[$i][$key], true), $format);
                        }
                        $ret[$i][$externalKey['keyName']] = $cached[$externalKey['keyName']][$ret[$i][$key]];
                    }
                    unset($ret[$i][$key]);
                }
                ++$i;
            }
        } else {
            $vars = $objects->getVars();
            foreach ($vars as $k => $v) {
                $ret[$k] = $objects->getVar($k, $format);
            }
            foreach ($externalKeys as $key) {
                // Replace external key by corresponding object
                $externalKey = $objects->getExternalKey($key);
                if (0 != $ret[$key]) {
                    // Retriving data if isn't cached
                    if (!isset($cached[$externalKey['keyName']][$ret[$key]])) {
                        if ($externalKey['core']) {
                            $handler = xoops_getHandler($externalKey['className']);
                        } else {
                            $handler = Extcal\Helper::getInstance()->getHandler($externalKey['className']);
                        }
                        $getMethod                                   = $externalKey['getMethodeName'];
                        $cached[$externalKey['keyName']][$ret[$key]] = $this->objectToArrayWithoutExternalKey($handler->$getMethod($ret[$key], true), $format);
                    }
                    $ret[$externalKey['keyName']] = $cached[$externalKey['keyName']][$ret[$key]];
                }
                unset($ret[$key]);
            }
        }

        return $ret;
    }

    /**
     * @param        $object
     * @param string $format
     *
     * @return array
     */
    public function objectToArrayWithoutExternalKey($object, $format = 's')
    {
        $ret = [];
        if (null !== $object) {
            $vars = $object->getVars();
            foreach ($vars as $k => $v) {
                $ret[$k] = $object->getVar($k, $format);
            }
        }

        return $ret;
    }

    /**
     * @param        $fieldname
     * @param        $criteria
     * @param string $op
     *
     * @return bool
     */
    public function updateCounter($fieldname, $criteria, $op = '+')
    {
        $sql    = 'UPDATE ' . $this->table . ' SET ' . $fieldname . ' = ' . $fieldname . $op . '1';
        $sql    .= ' ' . $criteria->renderWhere();
        $result = $this->db->queryF($sql);
        if (!$result) {
            return false;
        }

        return true;
    }

    /**
     * @param \CriteriaElement $criteria
     * @param string           $sum
     *
     * @return array|string
     */
    public function getSum(\CriteriaElement $criteria = null, $sum = '*')
    {
        $field   = '';
        $groupby = false;
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            if ('' != $criteria->groupby) {
                $groupby = true;
                $field   = $criteria->groupby . ', '; //Not entirely secure unless you KNOW that no criteria's groupby clause is going to be mis-used
            }
        }
        $sql = 'SELECT ' . $field . "SUM($sum) FROM " . $this->table;
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ('' != $criteria->groupby) {
                $sql .= $criteria->getGroupby();
            }
        }
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }
        if (false === $groupby) {
            list($sum) = $this->db->fetchRow($result);

            return $sum;
        } else {
            $ret = [];
            while (false !== (list($id, $sum) = $this->db->fetchRow($result))) {
                $ret[$id] = $sum;
            }

            return $ret;
        }
    }

    /**
     * @param \CriteriaElement $criteria
     * @param string           $max
     *
     * @return array|string
     */
    public function getMax(\CriteriaElement $criteria = null, $max = '*')
    {
        $field   = '';
        $groupby = false;
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            if ('' != $criteria->groupby) {
                $groupby = true;
                $field   = $criteria->groupby . ', '; //Not entirely secure unless you KNOW that no criteria's groupby clause is going to be mis-used
            }
        }
        $sql = 'SELECT ' . $field . "MAX($max) FROM " . $this->table;
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ('' != $criteria->groupby) {
                $sql .= $criteria->getGroupby();
            }
        }
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }
        if (false === $groupby) {
            list($max) = $this->db->fetchRow($result);

            return $max;
        } else {
            $ret = [];
            while (false !== (list($id, $max) = $this->db->fetchRow($result))) {
                $ret[$id] = $max;
            }

            return $ret;
        }
    }

    /**
     * @param null   $criteria
     * @param string $avg
     *
     * @return int
     */
    public function getAvg($criteria = null, $avg = '*')
    {
        $field = '';

        $sql = 'SELECT ' . $field . "AVG($avg) FROM " . $this->table;
        if (isset($criteria) && is_subclass_of($criteria, 'CriteriaElement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }
        list($sum) = $this->db->fetchRow($result);

        return $sum;
    }

    /**
     * @return mixed
     */
    public function getInsertId()
    {
        return $this->db->getInsertId();
    }
}
