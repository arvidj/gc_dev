<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Arvid Jakobsson <arvid@gluteus.se>
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

/**
 * Tx_GcDev_PageTreeCLIFormatter
 *
 * Formats a page tree in a format suitable for the CLI, like this:
 *
 * a
 * |-- b
 * |   |-- c
 * |   |-- d
 * |   |   |-- e
 * |   |   `-- f
 * |   `-- g
 * |       `-- h
 * |-- i
 * `-- j
 * `-- k
 *
 * @package
 * @author  Arvid Jakobsson <arvid@gluteus.se>
 */
class Tx_GcDev_PageTreeCLIFormatter {
	protected $dataProvider = NULL;

	public function __construct($dataProvider) {
		$this->dataProvider = $dataProvider;
	}

	public function format($node) {
		$this->formatAux($node, '', '');
	}

	protected function formatAux($node, $ind, $leaf) {
		$label = '[' . $node->getId() . '] ' . (!$node->getText() ? '[null]' : $node->getText());
		echo $leaf . $label  . PHP_EOL;

		$nodeCollection = $this->dataProvider->getNodes($node);

		for ($i = 0, $c = count($nodeCollection); $i < $c; $i++) {
			$node = $nodeCollection[$i];
			$last = ($i == $c - 1);
			$leaf = $ind . ($last ? '`' : '|') . '-- ';

			$ind2 = $last ? $ind . '   ' : $ind . '|  ';
			$this->formatAux($node,  $ind2, $leaf);
		}
	}
}
?>