<?php

use Drill\JsonQuestions;
use Drill\TxtParser;

require_once __DIR__ . '/vendor/autoload.php';



(new JsonQuestions())
    ->addQuestions(TxtParser::fromFile(__DIR__ . '/data-PV065-vypracovane-otazky-verze-19-1-2015-pv065-unix.txt')->toArray())
    ->write(__DIR__ . '/../datasets/questions_PV065.json');
