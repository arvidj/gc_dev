<?php

if (!defined ('TYPO3_MODE')) die('Access denied.');

/*
 * $extutil = new Tx_Extbase_Utility_Extension();
 * $extutil->createAutoloadRegistryForExtension($_EXTKEY, t3lib_extMgm::extPath($_EXTKEY));
 */

$TYPO3_CONF_VARS['SC_OPTIONS']['GLOBAL']['cliKeys'][$_EXTKEY] = array('EXT:'.$_EXTKEY.'/cli/cli.php','_CLI_lowlevel');

?>
