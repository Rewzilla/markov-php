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
Constructor.  Creates a new Markov object.
- `$order`		Markov model order (how many things we keep track of)
- `$delimiter`	May be either "something" or "".  If empty, split on character.

### `Markov::add($text)`
Add some text to the current markov model.
- `$text`		String of text to add

### `Markov::add_file($filename)`
Add some text from a file to the current model
- `$filename`	File to read and add

### `Markov::gen($length)`
Generate a markov chain (string).
- `$length`		Maximum length in base units (words, characters, etc)

### `Markov::save($filename)`
Save the current model to a file
- `$filename`	File where the model should be saved

### `Markov::restore($filename)`
Restore a saved model from a file
- `$filename`	File to restore a model from

### Markov::debug_dump()
Dump a PHP array (using print_r()) of the current model

