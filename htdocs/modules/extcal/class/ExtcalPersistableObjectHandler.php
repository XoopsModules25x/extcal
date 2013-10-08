<?php

/**
 * Persistable Object Handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of derived class objects.
 *
 * @author  Jan Keller Pedersen <mithrandir@xoops.org> - IDG Danmark A/S <www.idg.dk>
 * @copyright copyright (c) 2000-2004 XOOPS.org
 * @package Kernel
 */

class ExtcalPersistableObjectHandler extends XoopsObjectHandler
{

    /**#@+
     * Information about the class, the handler is managing
     *
     * @var string
     */
    var $table;
    var $keyName;
    var $className;
    var $identifierName;

    /**#@-*/

    /**
     * Constructor - called from child classes
     * @param object     $db         {@link XoopsDatabase} object
     * @param string     $tablename  Name of database table
     * @param string     $classname  Name of Class, this handler is managing
     * @param string     $keyname    Name of the property, holding the key
     *
     * @return void
     */
    function ExtcalPersistableObjectHandler(
        &$db, $tablename, $classname, $keyname, $idenfierName = false
    )
    {
        $this->XoopsObjectHandler($db);
        $this->table = $db->prefix($tablename);
        $this->keyName = $keyname;
        $this->className = $classname;
        if ($idenfierName != false) {
            $this->identifierName = $idenfierName;
        }
    }

    /**
     * create a new user
     *
     * @param bool $isNew Flag the new objects as "new"?
     *
     * @return object
     */
    function &create($isNew = true)
    {
        $obj = new $this->className();
        if ($isNew === true) {
            $obj->setNew();
        }
        return $obj;
    }

    /**
     * retrieve an object
     *
     * @param mixed $id ID of the object - or array of ids for joint keys. Joint keys MUST be given in the same order as in the constructor
     * @param bool $asObject whether to return an object or an array
     * @return mixed reference to the object, FALSE if failed
     */
    function &get($id, $asObject = true)
    {
        if (is_array($this->keyName)) {
            $criteria = new CriteriaCompo();
            for (
                $i = 0; $i < count($this->keyName); $i++
            ) {
                $criteria->add(new Criteria($this->keyName[$i], intval($id[$i])));
            }
        } else {
            $criteria = new Criteria($this->keyName, intval($id));
        }
        $criteria->setLimit(1);
        $objectArray = $this->getObjects($criteria, false, $asObject);
        if (count($objectArray) != 1) {
            return $this->create();
        }
        return $objectArray[0];
    }

    /**
     * retrieve objects from the database
     *
     * @param object $criteria {@link CriteriaElement} conditions to be met
     * @param bool $idAsKey use the ID as key for the array?
     * @param bool $asObject return an array of objects?
     *
     * @return array
     */
    function &getObjects(
        $criteria = null, $idAsKey = false, $asObject = true
    )
    {
        $ret = array();
        $limit = $start = 0;
        $sql = 'SELECT * FROM ' . $this->table;
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ($criteria->getSort() != '') {
                $sql .= ' ORDER BY ' . $criteria->getSort() . ' '
                    . $criteria->getOrder();
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
     * Convert a database resultset to a returnable array
     *
     * @param object $result database resultset
     * @param bool $idAsKey - should NOT be used with joint keys
     * @param bool $asObject
     *
     * @return array
     */
    function convertResultSet($result, $idAsKey = false, $asObject = true)
    {
        $ret = array();
        while ($myrow = $this->db->fetchArray($result)) {
            $obj =& $this->create(false);
            $obj->assignVars($myrow);
            if (!$idAsKey) {
                if ($asObject) {
                    $ret[] =& $obj;
                } else {
                    $row = array();
                    $vars = $obj->getVars();
                    foreach (
                        array_keys($vars) as $i
                    ) {
                        $row[$i] = $obj->getVar($i);
                    }
                    $ret[] = $row;
                }
            } else {
                if ($asObject) {
                    $ret[$myrow[$this->keyName]] =& $obj;
                } else {
                    $row = array();
                    $vars = $obj->getVars();
                    foreach (
                        array_keys($vars) as $i
                    ) {
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
     * Retrieve a list of objects as arrays - DON'T USE WITH JOINT KEYS
     *
     * @param object $criteria {@link CriteriaElement} conditions to be met
     * @param int   $limit      Max number of objects to fetch
     * @param int   $start      Which record to start at
     *
     * @return array
     */
    function getList($criteria = null, $limit = 0, $start = 0)
    {
        $ret = array();
        if ($criteria == null) {
            $criteria = new CriteriaCompo();
        }

        if ($criteria->getSort() == '') {
            $criteria->setSort($this->identifierName);
        }

        $sql = 'SELECT ' . $this->keyName;
        if (!empty($this->identifierName)) {
            $sql .= ', ' . $this->identifierName;
        }
        $sql .= ' FROM ' . $this->table;
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ($criteria->getSort() != '') {
                $sql .= ' ORDER BY ' . $criteria->getSort() . ' '
                    . $criteria->getOrder();
            }
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }

        $myts =& MyTextSanitizer::getInstance();
        while ($myrow = $this->db->fetchArray($result)) {
            //identifiers should be textboxes, so sanitize them like that
            $ret[$myrow[$this->keyName]] = empty($this->identifierName) ? 1
                : $myts->htmlSpecialChars($myrow[$this->identifierName]);
        }
        return $ret;
    }

    /**

     * count objects matching a condition
     *
     * @param object $criteria {@link CriteriaElement} to match
     * @return int count of objects
     */
    function getCount($criteria = null)
    {
        $field = "";
        $groupby = false;
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            if ($criteria->groupby != "") {
                $groupby = true;
                $field = $criteria->groupby
                    . ", "; //Not entirely secure unless you KNOW that no criteria's groupby clause is going to be mis-used
            }
        }
        $sql = 'SELECT ' . $field . 'COUNT(*) FROM ' . $this->table;
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ($criteria->groupby != "") {
                $sql .= $criteria->getGroupby();
            }
        }
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }
        if ($groupby == false) {
            list($count) = $this->db->fetchRow($result);
            return $count;
        } else {
            $ret = array();
            while (list($id, $count) = $this->db->fetchRow($result)) {
                $ret[$id] = $count;
            }
            return $ret;
        }
    }

    /**
     * delete an object from the database
     *
     * @param mixed $id id of the object to delete
     * @param bool $force
     * @return bool FALSE if failed.
     */
    function delete($id, $force = false)
    {
        if (is_array($this->keyName)) {
            $clause = array();
            for (
                $i = 0; $i < count($this->keyName); $i++
            ) {
                $clause[] = $this->keyName[$i] . " = " . $id[$i];
            }
            $whereclause = implode(" AND ", $clause);
        } else {
            $whereclause = $this->keyName . " = " . $id;
        }
        $sql = "DELETE FROM " . $this->table . " WHERE " . $whereclause;
        if (false != $force) {
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
     * insert a new object in the database
     *
     * @param object $obj reference to the object
     * @param bool $force whether to force the query execution despite security settings
     * @param bool $checkObject check if the object is dirty and clean the attributes
     * @return bool FALSE if failed, TRUE if already present and unchanged or successful
     */

    function insert(&$obj, $force = false, $checkObject = true)
    {
        if ($checkObject != false) {
            if (!is_object($obj)) {
                var_dump($obj);
                return false;
            }
            /**
             * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
             */
            if (!is_a($obj, $this->className)) {
                $obj->setErrors(
                    get_class($obj) . " Differs from " . $this->className
                );
                return false;
            }
        }
        if (!$obj->cleanVars()) {
            return false;
        }

        foreach (
            $obj->cleanVars as $k
            => $v
        ) {
            if ($obj->vars[$k]['data_type'] == XOBJ_DTYPE_INT) {
                $cleanvars[$k] = intval($v);
            } elseif (is_array($v)) {
                $cleanvars[$k] = $this->db->quoteString(implode(',', $v));
            } else {
                $cleanvars[$k] = $this->db->quoteString($v);
            }
        }
        if ($obj->isNew()) {
            if (!is_array($this->keyName)) {
                if ($cleanvars[$this->keyName] < 1) {
                    $cleanvars[$this->keyName] = $this->db->genId(
                        $this->table . '_' . $this->keyName . '_seq'
                    );
                }
            }
            $sql = "INSERT INTO " . $this->table . " ("
                . implode(',', array_keys($cleanvars)) . ") VALUES ("
                . implode(',', array_values($cleanvars)) . ")";
        } else {
            $sql = "UPDATE " . $this->table . " SET";
            foreach (
                $cleanvars as $key
                => $value
            ) {
                if ((!is_array($this->keyName) && $key == $this->keyName)
                    || (is_array($this->keyName)
                        && in_array($key, $this->keyName))
                ) {
                    continue;
                }
                if (isset($notfirst)) {
                    $sql .= ",";
                }
                $sql .= " " . $key . " = " . $value;
                $notfirst = true;
            }
            if (is_array($this->keyName)) {
                $whereclause = "";
                for (
                    $i = 0; $i < count($this->keyName); $i++
                ) {
                    if ($i > 0) {
                        $whereclause .= " AND ";
                    }
                    $whereclause .= $this->keyName[$i] . " = "
                        . $obj->getVar($this->keyName[$i]);
                }
            } else {
                $whereclause
                    = $this->keyName . " = " . $obj->getVar($this->keyName);
            }
            $sql .= " WHERE " . $whereclause;
        }
        if (false != $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            return false;
        }
        if ($obj->isNew() && !is_array($this->keyName)) {
            $obj->assignVar($this->keyName, $this->db->getInsertId());
        }
        return true;
    }

    /**
     * Change a value for objects with a certain criteria
     *
     * @param   string  $fieldname  Name of the field
     * @param   string  $fieldvalue Value to write
     * @param   object  $criteria   {@link CriteriaElement}
     *
     * @return  bool
     **/
    function updateAll(
        $fieldname, $fieldvalue, $criteria = null, $force = false
    )
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
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (false != $force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }
        if (!$result) {
            return false;
        }
        return true;
    }

    function updateFieldValue(
        $fieldname, $fieldvalue, $criteria = null, $force = true
    )
    {
        $sql = 'UPDATE ' . $this->table . ' SET ' . $fieldname . ' = '
            . $fieldvalue;
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (false != $force) {
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
     * delete all objects meeting the conditions
     *
     * @param object $criteria {@link CriteriaElement} with conditions to meet
     * @return bool
     */

    function deleteAll($criteria = null)
    {
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
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

    function _toObject($data)
    {
        if (is_array($data)) {
            $ret = array();
            foreach (
                $data as $v
            ) {
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

    function objectToArray($objects, $externalKeys = array(), $format = 's')
    { 
        static $cache;
        if (!is_array($externalKeys)) $externalKeys = array($externalKeys);  //JJD
        
        $ret = array();
        if (is_array($objects)) {
            $i = 0;
            foreach (
                $objects as $object
            ) {
                $vars = $object->getVars();
                foreach (
                    $vars as $k
                    => $v
                ) {
                    $ret[$i][$k] = $object->getVar($k, $format);
                }
                foreach (
                    $externalKeys as $key
                ) {
                    // Replace external key by corresponding object
                    $externalKey = $object->getExternalKey($key);
                    if ($ret[$i][$key] != 0) {
                        // Retriving data if isn't cached
                        if (!isset($cached[$externalKey['keyName']][$ret[$i][$key]])) {
                            if ($externalKey['core']) {
                                $handler = xoops_gethandler($externalKey['className']);
                            } else {
                                $handler = xoops_getmodulehandler($externalKey['className'], 'extcal');
                            }
                            $cached[$externalKey['keyName']][$ret[$i][$key]] = $this->objectToArrayWithoutExternalKey($handler->$externalKey['getMethodeName']($ret[$i][$key], true), $format);
                        }
                        $ret[$i][$externalKey['keyName']] = $cached[$externalKey['keyName']][$ret[$i][$key]];
                    }
                    unset($ret[$i][$key]);
                }
                $i++;
            }
        } else {
            $vars = $objects->getVars();
            foreach (
                $vars as $k
                => $v
            ) {
                $ret[$k] = $objects->getVar($k, $format);
            }
            foreach (
                $externalKeys as $key
            ) {
                // Replace external key by corresponding object
                $externalKey = $objects->getExternalKey($key);
                if ($ret[$key] != 0) {
                    // Retriving data if isn't cached
                    if (!isset($cached[$externalKey['keyName']][$ret[$key]])) {
                        if ($externalKey['core']) {
                            $handler = xoops_gethandler($externalKey['className']);
                        } else {
                            $handler = xoops_getmodulehandler($externalKey['className'], 'extcal');
                        }
                        $cached[$externalKey['keyName']][$ret[$key]] = $this->objectToArrayWithoutExternalKey($handler->$externalKey['getMethodeName']($ret[$key], true), $format);
                    }
                    $ret[$externalKey['keyName']] = $cached[$externalKey['keyName']][$ret[$key]];
                }
                unset($ret[$key]);
            }
        }
        return $ret;
    }

    function objectToArrayWithoutExternalKey($object, $format = 's')
    {
        $ret = array();
        if ($object != null) {
            $vars = $object->getVars();
            foreach (
                $vars as $k
                => $v
            ) {
                $ret[$k] = $object->getVar($k, $format);
            }
        }
        return $ret;
    }

    function updateCounter($fieldname, $criteria, $op = '+')
    {
        $sql
            =
            'UPDATE ' . $this->table . ' SET ' . $fieldname . ' = ' . $fieldname
                . $op . '1';
        $sql .= ' ' . $criteria->renderWhere();
        $result = $this->db->queryF($sql);
        if (!$result) {
            return false;
        }
        return true;
    }

    function getSum($criteria = null, $sum = '*')
    {
        $field = "";
        $groupby = false;
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            if ($criteria->groupby != "") {
                $groupby = true;
                $field = $criteria->groupby
                    . ", "; //Not entirely secure unless you KNOW that no criteria's groupby clause is going to be mis-used
            }
        }
        $sql = 'SELECT ' . $field . "SUM($sum) FROM " . $this->table;
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ($criteria->groupby != "") {
                $sql .= $criteria->getGroupby();
            }
        }
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }
        if ($groupby == false) {
            list($sum) = $this->db->fetchRow($result);
            return $sum;
        } else {
            $ret = array();
            while (list($id, $sum) = $this->db->fetchRow($result)) {
                $ret[$id] = $sum;
            }
            return $ret;
        }
    }

    function getMax($criteria = null, $max = '*')
    {
        $field = "";
        $groupby = false;
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            if ($criteria->groupby != "") {
                $groupby = true;
                $field = $criteria->groupby
                    . ", "; //Not entirely secure unless you KNOW that no criteria's groupby clause is going to be mis-used
            }
        }
        $sql = 'SELECT ' . $field . "MAX($max) FROM " . $this->table;
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
            if ($criteria->groupby != "") {
                $sql .= $criteria->getGroupby();
            }
        }
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }
        if ($groupby == false) {
            list($max) = $this->db->fetchRow($result);
            return $max;
        } else {
            $ret = array();
            while (list($id, $max) = $this->db->fetchRow($result)) {
                $ret[$id] = $max;
            }
            return $ret;
        }
    }

    function getAvg($criteria = null, $avg = '*')
    {
        $field = "";

        $sql = 'SELECT ' . $field . "AVG($avg) FROM " . $this->table;
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }
        list($sum) = $this->db->fetchRow($result);
        return $sum;
    }

    function getInsertId()
    {
        return $this->db->getInsertId();
    }

}
?>
