<?php

function help($command = FALSE) {
	echo <<<EOT
Usage:

	./typo3/cli_dispatch.phpsh gc_dev dump-template [<pid>]
		Dumps the template for the given pid. pid defaults to 1.

	./typo3/cli_dispatch.phpsh gc_dev dump-pagetree [<pid>]
		Dumps the pagetree for the given pid. pid defaults to 0.
EOT;
}


// Handle arguments
$argv = $_SERVER['argv'];
if (!isset($argv[1])) {
	help();
	exit();
}
$command = $argv[1];
$arguments = array_splice($argv, 2);

// Initialise frontend
if ($command == 'dump-template') {
	// Handle arguments to dump-template
	if (isset($arguments[0])) {
		if (!is_numeric($arguments[0])) {
			help($command);
			exit();
		} else {
			$pid = $arguments[0];
		}
	} else {
		$pid = 1;
	}

	$tsHelper = new tx_gcdev_TSTemplateHelper();
	$template = $tsHelper->getTemplateForPid($pid);
	echo $tsHelper->formatArrayAsTyposcript($template);
} else if ($command == 'dump-pagetree') {
	// Handle arguments to dump-pagetree
	if (isset($arguments[0])) {
		if (!is_numeric($arguments[0])) {
			help($command);
			exit();
		} else {
			$pid = $arguments[0];
		}
	} else {
		$pid = 0;
	}

	// tihi
	$GLOBALS['BE_USER']->user['admin'] = TRUE;

	// Get page node from row
	if ($pid != 0) {
		$pageRow = t3lib_BEfunc::getRecord('pages', $pid);
		$node = t3lib_tree_pagetree_Commands::getNewNode($pageRow);
	} else {
		$nodeData = array('id' => 0);
		$node = t3lib_div::makeInstance('t3lib_tree_pagetree_Node', $nodeData);
		$node->setText('[Root]');
	}

	// Format tree
	$dataProvider = t3lib_div::makeInstance('t3lib_tree_pagetree_DataProvider');
	$formatter = t3lib_div::makeInstance('Tx_GcDev_PageTreeCLIFormatter', $dataProvider);
	$formatter->format($node);
} else {
	help();
	die();
}


?>