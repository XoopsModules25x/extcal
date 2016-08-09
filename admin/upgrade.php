<?php

$step = 'default';
if (isset($_POST['step'])) {
    $step = $_POST['step'];
}

include_once dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';
include __DIR__ . '/function.php';

// Change this variable if you use a cloned version of eXtGallery
$localModuleDir = 'extcal';

$moduleName     = 'extcal';
$versionFile    = 'http://www.zoullou.net/extcal.version';
$downloadServer = 'http://downloads.sourceforge.net/zoullou/';

$lastVersion       = @file_get_contents($versionFile);
$lastVersionString = substr($lastVersion, 0, 1) . '.' . substr($lastVersion, 1, 1) . '.' . substr($lastVersion, 2, 1);
$moduleFileName    = $moduleName . '-' . $lastVersionString . '.tar.gz';
$langFileName      = $moduleName . '-lang-' . $lastVersionString . '_' . $xoopsConfig['language'] . '.tar.gz';

switch ($step) {

    case 'download':

        xoops_cp_header();
        adminMenu();

        if ($GLOBALS['xoopsModule']->getVar('version') >= $lastVersion) {
            echo _AM_EXTCAL_UPDATE_OK;
            xoops_cp_footer();
            break;
        }

        if (!$handle = @fopen($downloadServer . $moduleFileName, 'r')) {
            printf(_AM_EXTCAL_MD_FILE_DONT_EXIST, $downloadServer, $moduleFileName);
            xoops_cp_footer();
            break;
        }
        $localHandle = @fopen(XOOPS_ROOT_PATH . '/uploads/' . $moduleFileName, 'w+');

        // Downlad module archive
        if ($handle) {
            while (!feof($handle)) {
                $buffer = fread($handle, 8192);
                fwrite($localHandle, $buffer);
            }
            fclose($localHandle);
            fclose($handle);
        }

        // English file are included on module package
        if ($xoopsConfig['language'] !== 'english') {
            if (!$handle = @fopen($downloadServer . $langFileName, 'r')) {
                printf(_AM_EXTCAL_LG_FILE_DONT_EXIST, $downloadServer, $langFileName);
            } else {
                $localHandle = @fopen(XOOPS_ROOT_PATH . '/uploads/' . $langFileName, 'w+');
                // Download language archive
                if ($handle) {
                    while (!feof($handle)) {
                        $buffer = fread($handle, 8192);
                        fwrite($localHandle, $buffer);
                    }
                    fclose($localHandle);
                    fclose($handle);
                }
            }
        }

        xoops_confirm(array('step' => 'install'), 'upgrade.php', _AM_EXTCAL_DOWN_DONE, _AM_EXTCAL_INSTALL);

        xoops_cp_footer();

        break;

    case 'install':

        xoops_cp_header();
        adminMenu();

        if (!file_exists(XOOPS_ROOT_PATH . '/uploads/' . $moduleFileName)) {
            echo _AM_EXTCAL_MD_FILE_DONT_EXIST_SHORT;
            xoops_cp_footer();

            break;
        }

        $gPcltarLibDir = XOOPS_ROOT_PATH . '/modules/' . $localModuleDir . '/class';
        include dirname(__DIR__) . '/class/pcltar.lib.php';

        //TrOn(5);

        // Extract module files
        PclTarExtract(XOOPS_ROOT_PATH . '/uploads/' . $moduleFileName, XOOPS_ROOT_PATH . '/modules/' . $localModuleDir . '/', 'modules/' . $moduleName . '/');
        // Delete downloaded module's files
        unlink(XOOPS_ROOT_PATH . '/uploads/' . $moduleFileName);

        if (file_exists(XOOPS_ROOT_PATH . '/uploads/' . $langFileName)) {
            // Extract language files
            PclTarExtract(XOOPS_ROOT_PATH . '/uploads/' . $langFileName, XOOPS_ROOT_PATH . '/modules/' . $localModuleDir . '/', 'modules/' . $moduleName . '/');
            // Delete downloaded module's files
            unlink(XOOPS_ROOT_PATH . '/uploads/' . $langFileName);
        }

        // Delete template_c file
        if ($handle = opendir(XOOPS_ROOT_PATH . '/templates_c')) {
            while (false !== ($file = readdir($handle))) {
                if ($file !== '.' && $file !== '..' && $file !== 'index.html') {
                    unlink(XOOPS_ROOT_PATH . '/templates_c/' . $file);
                }
            }

            closedir($handle);
        }
        //TrDisplay();

        xoops_confirm(array('dirname' => $localModuleDir, 'op' => 'update_ok', 'fct' => 'modulesadmin'), XOOPS_URL . '/modules/system/admin.php', _AM_EXTCAL_INSTALL_DONE, _AM_EXTCAL_UPDATE);

        xoops_cp_footer();

        break;

    default:
    case 'default':

        redirect_header('index.php', 3, '');

        break;
}
