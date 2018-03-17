# markov-php
Markov library written in PHP

## Features
 - Easily save/restore models to/from files
 - Can handle any base unit
   - characters
   - words
   - pick your own delimiter

## Example Usage
```php
<?php

/*
Create a new markov model, add a sentance, add a text file, and generate
a markov chain.  Then save the model.  The model's order defaults to 3,
and the delimiter defaults to " " (space).
*/

require_once("markov.php");

$m = new Markov();

$m->add("The quick brown fox jumps over the lazy dog");
$m->add_file("/tmp/book.txt");

echo $m->gen(100);

$m->save("/tmp/model.dat");

?>
```
## Documentation

### `Markov::Markov([$order = 3 [, $delimiter = " "]])`
- `$order`		Markov model order (how many things we keep track of)
- `$delimiter`	May be either "something" or "".  If empty, split on character.

### `Markov::add($text)`
- `$text`		String of text to add to the current model

