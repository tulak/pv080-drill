<?php

use Drill\ElearningPreviewParser;
use Drill\JsonQuestions;

require_once __DIR__ . '/vendor/autoload.php';



(new JsonQuestions())
	->addQuestions(Drill\QdefParser::fromFile(__DIR__ . '/PV004/10_uvod.qdef')->toArray())
	->addQuestions(Drill\QdefParser::fromFile(__DIR__ . '/PV004/20_filesystem.qdef')->toArray())
	->addQuestions(Drill\QdefParser::fromFile(__DIR__ . '/PV004/30_shell.qdef')->toArray())
	->addQuestions(Drill\QdefParser::fromFile(__DIR__ . '/PV004/40_regexp.qdef')->toArray())
	->addQuestions(Drill\QdefParser::fromFile(__DIR__ . '/PV004/50_text.qdef')->toArray())
//	->addQuestions(Drill\QdefParser::fromFile(__DIR__ . '/PV004/pristupova_prava_ls_oct.qdef')->toArray())
//	->addQuestions(Drill\QdefParser::fromFile(__DIR__ . '/PV004/pristupova_prava_oct_ls.qdef')->toArray())
	->write(__DIR__ . '/../questions_PV004.json');

$vb036 = (new JsonQuestions());
foreach (glob(__DIR__ . '/vb036/*.html') as $file) {
	$vb036->addQuestions(ElearningPreviewParser::fromFile($file)->toArray());
}
$vb036->write(__DIR__ . '/../questions_VB036.json');
unset($vb036);

$vb035 = (new JsonQuestions());
foreach (glob(__DIR__ . '/vb035/*.html') as $file) {
	$vb035->addQuestions(ElearningPreviewParser::fromFile($file)->toArray());
}
$vb035->write(__DIR__ . '/../questions_VB035.json');
unset($vb035);
