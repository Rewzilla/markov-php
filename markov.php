<?php

class Markov {

	private $start;		// 1d array of starting points
	private $model;		// 2d array of seen->array(possibilities)
	private $order;		// markov order (defaults to 3)
	private $delimiter;	// Token delimiter (defaults to " ")

	/* Constructor
	 *
	 * $order	: Markov order
	 */
	public function __construct($order = 3, $delimiter = " ") {

		$this->start = array();
		$this->model = array();
		$this->order = $order;
		$this->delimiter = $delimiter;

	}

	/* Add a string of text to the current markov model
	 *
	 * $text	: String of text to add
	 */
	public function add($text) {

		$text = Markov::split($text, $this->delimiter);

		if(!isset($this->start[Markov::slice_str($text, 0, $this->order)]))
			$this->start[Markov::slice_str($text, 0, $this->order)] = 1;
		else
			$this->start[Markov::slice_str($text, 0, $this->order)]++;

		for($i=0; $i<count($text)-$this->order; $i++) {

			if(!isset($this->model[Markov::slice_str($text, $i, $this->order)]))
				$this->model[Markov::slice_str($text, $i, $this->order)] = array($text[$i+$this->order] => 1);
			else if(!isset($this->model[Markov::slice_str($text, $i, $this->order)][$text[$i+$this->order]]))
				$this->model[Markov::slice_str($text, $i, $this->order)][$text[$i+$this->order]] = 1;
			else
				$this->model[Markov::slice_str($text, $i, $this->order)][$text[$i+$this->order]]++;

		}

	}

	/* Add the entire contents of a file
	 *
	 * $filename	: The file to read/add
	 */
	public function add_file($filename) {

		$this->add(file_get_contents($filename));

	}

	/* Generate a markov chain based on the current model
	 *
	 * $length	: Maximum length of generated string
	 */
	public function gen($length) {

		$output = Markov::split(Markov::weighted_random($this->start), $this->delimiter);

		for($i=0; $i<$length; $i++) {

			$last = Markov::join(array_slice($output, ($this->order) * -1, $this->order), $this->delimiter);

			if(isset($this->model[$last]))
				$output[] = Markov::weighted_random($this->model[$last]);
			else
				break;

		}

		return Markov::join($output, $this->delimiter);

	}

	/* Save the current model to a file
	 *
	 * $filename	: File to save to
	 */
	public function save($filename) {

		$state = array(
			"start" => $this->start,
			"chain" => $this->model,
			"order" => $this->order,
		);

		file_put_contents($filename, serialize($state));

	}

	/* Restore a model from a saved file
	 *
	 * $filename	: File to restore from
	 */
	public function restore($filename) {

		$state = unserialize(file_get_contents($filename));

		$this->start = $state["start"];
		$this->model = $state["chain"];
		$this->order = $state["order"];

	}

	/* Get a "random" value from the array based on weighted probability
	 *
	 * $arr		: Array in the form array("option1" => 5, "option2" =>3, ...)
	 */
	private function weighted_random($arr) {

		$tmp = array();

		foreach($arr as $word => $freq) {

			for($i=0; $i<$freq; $i++)
				$tmp[] = $word;

		}

		return $tmp[array_rand($tmp)];

	}

	/* Custom split function to allow empty delimiter (split on characters)
	 * Returns an array of the text, split by delimiter
	 *
	 * $text		: The text to be split
	 * $delimiter	: If empty string, split on characters, otherwise on delimiter
	 */
	public function split($text, $delimiter) {

		if($delimiter == "")
			return str_split($text);
		else
			return explode($delimiter, $text);

	}

	/* To remain consistent with split.  Joins text on delimiter.
	 * Returns the joined string
	 *
	 * $text		: Text to join
	 * $delimiter	: Delimiter to join on
	 */
	public function join($text, $delimiter) {

		return implode($delimiter, $text);

	}

	/* Takes an array of words, a start offset and a length
	 * Returns a string of those words
	 *
	 * $words	: An array of words
	 * $offset	: Where to start the slice
	 * $length	: How far to slice
	 */
	private function slice_str($words, $offset, $length) {

		$slice = array();

		for($i=$offset; $i<$offset+$length; $i++)
			$slice[] = $words[$i];

		return Markov::join($slice, $this->delimiter);

	}

	/* Dumps the current model as PHP arrays to STDOUT
	 */
	public function debug_dump() {

		$obj = array(
			"order" => $this->order,
			"start" => $this->start,
			"chain" => $this->model,
		);

		print_r($obj);

	}

}

?>