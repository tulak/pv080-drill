<?php

namespace Drill;



class JsonQuestions
{

    private $questions = [];



    public function addQuestions(array $questions)
    {
        $this->questions = array_merge($this->questions, $questions);

        return $this;
    }



    public function write($file)
    {
        file_put_contents($file, json_encode($this->questions, JSON_PRETTY_PRINT));
        $this->questions = [];
    }

}

