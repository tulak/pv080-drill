<?php

namespace Drill;



class QdefParser
{

    private $questions = [];



    public function __construct($contents)
    {
        $this->questions = $this->toQuestions($this->toBlocks($contents));
    }



    public function toArray()
    {
        return $this->questions;
    }



    private function toBlocks($contents)
    {
        $block = $blocks = [];
        $lines = preg_split('~[\\n\\r]+~', $contents);
        while ($line = array_shift($lines)) {
            if ($line == '--') {
                $blocks[] = $block;
                $block = [];
                continue;
            }

            $block[] = $line;
        }

        return $blocks;
    }



    /**
     * Který z příkazů nesmaže všechny soubory z běžného adresáře?
     *  :r1 for S in *; do rm $S; done
     *  :r2 rm `ls`
     *  :r3 rm `echo *`
     *  :r4 for S in `ls`; do rm $S; done
     *  :r5 for S in `rm`; do ls $S; done
     * :r5 ok
     */
    private function toQuestions(array $blocks)
    {
        $questions = [];

        foreach ($blocks as $block) {
            $question = [
                'name' => '',
                'answers' => [],
            ];

            while ($line = end($block)) {
                if (preg_match('~^\\:r(?P<i>\\d+)\\s+(?P<answer>.*)~i', $line, $m)) {
                    $question['answers'][$m['i']]['right'] = true;
                    if ($m['answer'] !== 'ok') {
                        $question['answers'][$m['i']]['comment'] = $m['answer'];
                    }

                } elseif (preg_match('~^\\s+\\:r(?P<i>\\d+)\\s+(?P<body>.*)$~i', $line, $m)) {
                    $question['answers'][$m['i']]['body'] = $m['body'];

                } else {
                    break;
                }

                array_pop($block);
            }

            foreach ($question['answers'] as $i => $answer) {
                $question['answers'][$i] += ['right' => false];
            }

            $question['answers'] = array_values($question['answers']);
            $question['name'] = implode("\n", $block); // glue the rest of block

            $questions[] = $question;
        }

        return $questions;
    }



    /**
     * @param string $file
     * @return QdefParser
     */
    public static function fromFile($file)
    {
        return new static(file_get_contents($file));
    }

}
