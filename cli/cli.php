<?php

function help($command = FALSE) {
	echo <<<EOT
Usage:

	./typo3/cli_dispatch.phpsh gc_dev dump-template [<pid>]
		Dumps the template for the given pid. pid defaults to 1.

	./typo3/cli_dispatch.phpsh gc_dev dump-pagetree [--max-depth <N>] [<pid>]
		Dumps the pagetree for the given pid. pid defaults to 0.
EOT;
}

function getArgNum($arguments, $i) {
	if (!is_numeric($arguments[$i])) {
		help();
		exit();
	}

	return $arguments[$i];
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
	$pid = end($arguments);
	if (isset($arguments[0])) {
		$pid = getArgNum(0);
	} else {
		$pid = 1;
	}

	$tsHelper = new tx_gcdev_TSTemplateHelper();
	$template = $tsHelper->getTemplateForPid($pid);
	echo $tsHelper->formatArrayAsTyposcript($template);
} else if ($command == 'dump-pagetree') {
	$maxDepth = FALSE;
	$pid = 0;
	for ($i = 0; $i < count($arguments); $i++) {
		if ($arguments[$i] === '--max-depth') {
			$maxDepth = getArgNum($arguments, ++$i);
		} else if (count($arguments) - 1 === $i) {
			$pid = getArgNum($arguments, $i);
		}
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
	$formatter = t3lib_div::makeInstance('Tx_GcDev_PageTreeCLIFormatter', $dataProvider, $maxDepth);
	$formatter->format($node);
} else {
	help();
	die();
}


?>