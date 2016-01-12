<?php

namespace Drill;

use UnexpectedValueException;



class TxtParser
{

    private $questions = [];



    public function __construct($contents)
    {
        $this->questions = $this->parse($contents);
    }



    public function toArray()
    {
        return $this->questions;
    }



    private function parse($contents)
    {
        $questions = [];

        $lines = preg_split("~[\n\r]~", $contents);

        $question = ['name' => '', 'answers' => []];
        while (($line = array_shift($lines)) !== null) {
            if (trim($line) === "") {
                $question['name'] = preg_replace(["~[\\s\\n]{2,}~", "~\\t+~"], [' ', ' '], $question['name']);
                $questions[] = $question;
                $question = ['name' => '', 'answers' => []];
                continue;
            }

            if (preg_match('~^\\*\\s+(?P<answer>\\S.*)\\z~s', $line, $m)) { // right answer
                $question['answers'][] = ['body' => trim($m['answer']), 'right' => true];

            } elseif (preg_match('~^\\s+(?P<answer>\\S.*)\\z~s', $line, $m)) { // answer
                $question['answers'][] = ['body' => trim($m['answer']), 'right' => false];

            } elseif (preg_match('~^\\S~', $line, $m)) { // question
                $question['name'] = trim($question['name'] . " \n" . $line);

            } else {
                throw new UnexpectedValueException("$line");
            }
        }

        return array_filter($questions);
    }



    /**
     * @param string $file
     * @return TxtParser
     */
    public static function fromFile($file)
    {
        return new static(file_get_contents($file));
    }
}
