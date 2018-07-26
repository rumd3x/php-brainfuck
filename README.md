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


### Decoding
```php
use Rumd3x\Brainfuck\Brainfuck;
echo Brainfuck::encode("++++++++++[>+++++++>++++++++++>+++++++++++>+++++++++++>+++++++++++>+++>+++++++++>+++++++++++>+++++++++++>+++++++++++>++++++++++>+++<<<<<<<<<<<<-]>++.>+.>--.>--.>+.>++.>---.>+.>++++.>--.>.>+++."); 
// outputs: Hello World!
```


Happy brainfucking!
