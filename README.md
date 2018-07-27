# php-brainfuck
A nice PHP Utility made out of boredom for encoding a string into brainfuck code and vice-versa.

## Installation
To install via composer add this to your composer.json
```json
"minimum-stability": "dev",
"repositories": [
	{ "type": "git", "url": "https://github.com/rumd3x/php-brainfuck.git" }
]
```
And then run
```sh
  composer require "rumd3x/php-brainfuck:*"
```


## Usage
### Encoding
```php
use Rumd3x\Brainfuck\Brainfuck;

echo Brainfuck::encode("Hello World!"); 
// outputs: ++++++++++[>+++++++>++++++++++>+++++++++++>+++++++++++>+++++++++++>+++>+++++++++>+++++++++++>+++++++++++>+++++++++++>++++++++++>+++<<<<<<<<<<<<-]>++.>+.>--.>--.>+.>++.>---.>+.>++++.>--.>.>+++.
```


There's also a second a third optional parameters for the encoding.

The second is for replacing non-ASCII characters.

And the third is for generating an minified or pretty-printed version of the code.
```php
use Rumd3x\Brainfuck\Brainfuck;

echo Brainfuck::encode("Some ASCII: ABC+ŤĎ and some non-ASCII: Ąąśćł.'", true); // will replace non-ascii with their ascii counterpart 
echo Brainfuck::encode("Prettifying'", false, true); // will output pretty printed brainfuck code
```


### Decoding
```php
use Rumd3x\Brainfuck\Brainfuck;

echo Brainfuck::decode("++++++++++[>+++++++>++++++++++>+++++++++++>+++++++++++>+++++++++++>+++>+++++++++>+++++++++++>+++++++++++>+++++++++++>++++++++++>+++<<<<<<<<<<<<-]>++.>+.>--.>--.>+.>++.>---.>+.>++++.>--.>.>+++."); 
// outputs: Hello World!
```


Happy brainfucking!
