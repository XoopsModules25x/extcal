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

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

// // require_once __DIR__ . '/ExtcalPersistableObjectHandler.php';
require_once XOOPS_ROOT_PATH . '/class/uploader.php';

/**
 * Class FileHandler.
 */
class FileHandler extends ExtcalPersistableObjectHandler
{
    /**
     * @param $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'extcal_file', File::class, 'file_id');
    }

    /**
     * @param $eventId
     *
     * @return bool
     */
    public function createFile($eventId)
    {
        $userId = $GLOBALS['xoopsUser'] ? $GLOBALS['xoopsUser']->getVar('uid') : 0;

        $allowedMimeType = [];
        $mimeType        = include XOOPS_ROOT_PATH . '/include/mimetypes.inc.php';
        foreach ($GLOBALS['xoopsModuleConfig']['allowed_file_extention'] as $fileExt) {
            $allowedMimeType[] = $mimeType[$fileExt];
        }

        $uploader = new \XoopsMediaUploader(XOOPS_ROOT_PATH . '/uploads/extcal', $allowedMimeType, 3145728);
        $uploader->setPrefix($userId . '-' . $eventId . '_');
        if ($uploader->fetchMedia('event_file')) {
            if (!$uploader->upload()) {
                return false;
            }
        } else {
            return false;
        }

        $data = [
            'file_name'     => $uploader->getSavedFileName(),
            'file_nicename' => $uploader->getMediaName(),
            'file_mimetype' => $uploader->getMediaType(),
            'file_size'     => $_FILES['event_file']['size'],
            'file_date'     => time(),
            'file_approved' => 1,
            'event_id'      => $eventId,
            'uid'           => $userId,
        ];

        $file = $this->create();
        $file->setVars($data);

        return $this->insert($file);
    }

    /**
     * @param $file
     */
    public function deleteFile(&$file)
    {
        $this->_deleteFile($file);
        $this->deleteById($file->getVar('file_id'));
    }

    /**
     * @param $eventId
     *
     * @return array
     */
    public function getEventFiles($eventId)
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('file_approved', 1));
        $criteria->add(new \Criteria('event_id', $eventId));

        return $this->getObjects($criteria);
    }

    /**
     * @param $eventId
     */
    public function updateEventFile($eventId)
    {
        $criteria = new \CriteriaCompo();
        $criteria->add(new \Criteria('file_approved', 1));
        $criteria->add(new \Criteria('event_id', $eventId));

        if (isset($_POST['filetokeep'])) {
            if (is_array($_POST['filetokeep'])) {
                $count = count($_POST['filetokeep']);
                $in    = '(' . $_POST['filetokeep'][0];
                array_shift($_POST['filetokeep']);
                foreach ($_POST['filetokeep'] as $elmt) {
                    $in .= ',' . $elmt;
                }
                $in .= ')';
            } else {
                $in = '(' . $_POST['filetokeep'] . ')';
            }
            $criteria->add(new \Criteria('file_id', $in, 'NOT IN'));
        }

        $files =& $this->getObjects($criteria);
        foreach ($files as $file) {
            $this->deleteFile($file);
        }
    }

    /**
     * @param $fileId
     *
     * @return mixed
     */
    public function getFile($fileId)
    {
        return $this->get($fileId);
    }

    /**
     * @param $files
     */
    public function formatFilesSize(&$files)
    {
        foreach ($files as $i => $iValue) {
            $this->formatFileSize($files[$i]);
        }
    }

    /**
     * @param $file
     */
    public function formatFileSize(&$file)
    {
        if ($file['file_size'] > 1000) {
            $file['formated_file_size'] = round($file['file_size'] / 1000) . 'kb';
        } else {
            $file['formated_file_size'] = '1kb';
        }
    }

    /**
     * @param $file
     */
    public function _deleteFile(&$file)
    {
        if (file_exists(XOOPS_ROOT_PATH . '/uploads/extcal/' . $file->getVar('file_name'))) {
            unlink(XOOPS_ROOT_PATH . '/uploads/extcal/' . $file->getVar('file_name'));
        }
    }
}
