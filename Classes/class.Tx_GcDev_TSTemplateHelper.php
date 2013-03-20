<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2010 Arvid Jakobsson <arvid@gluteus.se>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/* 
 * require_once(PATH_tslib . 'class.tslib_fe.php');
 * require_once(PATH_t3lib . 'class.t3lib_page.php');
 * require_once(PATH_t3lib . 'class.t3lib_userauth.php' );
 * require_once(PATH_tslib . 'class.tslib_feuserauth.php');
 * require_once(PATH_t3lib . 'class.t3lib_timetrack.php');
 */

/**
 * tx_gcsystem_SSFE_TSTemplateHelper
 *
 * description
 *
 * @package 
 * @author  Arvid Jakobsson <arvid@gluteus.se>
 */
class Tx_GcDev_TSTemplateHelper {
	/**
	 * Originally we stored the template in the global value "gc_template". I
	 * think this was because of performance reasons. We do something similar
	 * here by storing the template in a static variable.
	 */
	static private $templatePerPid = array();

	public function getTemplateForPid($pid) {
		// Is template for this pid cached?
		if (isset(self::$templatePerPid[$pid])) {
			$template = self::$templatePerPid[$pid];
		} else {
			/*
			 * initializing tsfe:
			 *  http://lists.typo3.org/pipermail/typo3-english/2007-February/036351.html
			 */
		
			$GLOBALS['TT'] = new t3lib_timeTrack;
			$tsfeClassName = t3lib_div::makeInstanceClassName('tslib_fe');

			$GLOBALS['TSFE'] = new $tsfeClassName($GLOBALS['TYPO3_CONF_VARS'], $pid, '');
			$GLOBALS['TSFE']->showHiddenPage = TRUE;
			$GLOBALS['TSFE']->connectToDB();
			$GLOBALS['TSFE']->initFEuser();
			$GLOBALS['TSFE']->determineId();
			/* $GLOBALS['TSFE']->getCompressedTCarray(); */
			$GLOBALS['TSFE']->initTemplate();
			$GLOBALS['TSFE']->getConfigArray();

			$template = $GLOBALS['TSFE']->tmpl->setup;
			self::$templatePerPid[$pid] = $template;
		}

		return $template;
	}

	public function formatArrayAsTyposcript($template, $ind = 0) {
		foreach ($template as $key => $value) {
			if (is_array($value)) {
				echo str_repeat("\t", $ind) . preg_replace('/.$/', '', $key) . ' {' . PHP_EOL;
				$this->formatArrayAsTyposcript($value, $ind+1);
				echo str_repeat("\t", $ind) . '}' . PHP_EOL;
			} else {
				echo str_repeat("\t", $ind) . $key . ' = ' . $value . PHP_EOL;
			}
		}
	}

}
?>