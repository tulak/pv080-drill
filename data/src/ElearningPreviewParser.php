<?php

namespace Drill;

use Atrox\Matcher;
use DOMElement;
use DOMXPath;



class ElearningPreviewParser
{

    private $questions = [];



    public function __construct($contents)
    {
        $this->questions = $this->toQuestions($contents);
    }



    public function toArray()
    {
        return $this->questions;
    }



    private function toQuestions($contents)
    {
        $matcher = $this->buildMatcher();

        return $matcher($contents);
    }



    protected function buildMatcher()
    {
        return Matcher::multi(
            '//div[@id="aplikace"]/form/table[not(@bgcolor)]//tr/td[position() = 3]',
            [
                'name' => './/text()[2]',
                'answers' => Matcher::multi(
                    './label',
                    [
                        'body' => './text()[last()]',
                        'right' => function (DOMElement $node) {
                            $asterisk = (new DOMXPath($node->ownerDocument))->query('.//font[@color="green"]/text()', $node);

                            return $asterisk->length > 0 && trim($asterisk->item(0)->wholeText) === '*';
                        }
                    ]
                ),
            ]
        )->fromHtml();
    }



    /**
     * @param string $file
     * @return \Drill\QdefParser
     */
    public static function fromFile($file)
    {
        return new static(file_get_contents($file));
    }

}
