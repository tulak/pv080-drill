<?php

use Atrox\Matcher;



require_once __DIR__ . '/vendor/autoload.php';



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
					$question['answers'][$m['i']]['right'] = TRUE;
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
				$question['answers'][$i] += ['right' => FALSE];
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
		return Matcher::multi('//div[@id="aplikace"]/form/table[not(@bgcolor)]//tr/td[position() = 3]', [
			'name' => './/text()[2]',
			'answers' => Matcher::multi('./label', [
				'body' => './text()[last()]',
				'right' => function (DOMElement $node) {
					$asterisk = (new DOMXPath($node->ownerDocument))->query('.//font[@color="green"]/text()', $node);
					return $asterisk->length > 0 && trim($asterisk->item(0)->wholeText) === '*';
				}
			]),
		])->fromHtml();
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



(new JsonQuestions())
	->addQuestions(QdefParser::fromFile(__DIR__ . '/PV004/10_uvod.qdef')->toArray())
	->addQuestions(QdefParser::fromFile(__DIR__ . '/PV004/20_filesystem.qdef')->toArray())
	->addQuestions(QdefParser::fromFile(__DIR__ . '/PV004/30_shell.qdef')->toArray())
	->addQuestions(QdefParser::fromFile(__DIR__ . '/PV004/40_regexp.qdef')->toArray())
	->addQuestions(QdefParser::fromFile(__DIR__ . '/PV004/50_text.qdef')->toArray())
//	->addQuestions(QdefParser::fromFile(__DIR__ . '/PV004/pristupova_prava_ls_oct.qdef')->toArray())
//	->addQuestions(QdefParser::fromFile(__DIR__ . '/PV004/pristupova_prava_oct_ls.qdef')->toArray())
	->write(__DIR__ . '/../questions_PV004.json');

$vb036 = (new JsonQuestions());
foreach (glob(__DIR__ . '/vb036/*.html') as $file) {
	$vb036->addQuestions(ElearningPreviewParser::fromFile($file)->toArray());
}
$vb036->write(__DIR__ . '/../questions_VB036.json');
