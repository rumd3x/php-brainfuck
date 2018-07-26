<?php
    namespace Rumd3x\Brainfuck;
    
    class Brainfuck {

        public static function encode($string) {            
            $cells = [];
            $len = mb_strlen($string);
            for ($c = 0; $c < $len; $c++) {
                $cells[$c] = ord($string[$c]);
            }

            $cell_data = [];
            $bf = str_repeat("+", 10)."[";
            foreach($cells as $char) {
                $data = [];
                $data['not_rounded'] = $char/10;
                $data['rounded'] = intval(round($data['not_rounded']));
                $cell_data[] = $data;
                $bf .= ">".str_repeat("+", $data['rounded']);
            }
            $bf .= str_repeat("<", count($cells))."-]";
            foreach($cell_data as $data) {
                $diff = $data['not_rounded'] - $data['rounded'];
                $times = abs(intval(strval($diff*10)));
                $sign = $diff >= 0 ? "+" : "-";
                // var_dump($sign);
                // var_dump($times);
                // die;
                $bf .= ">".str_repeat($sign, $times).".";
            }
            return $bf;            
        }

        public static function decode($string) {
            $cells = [];
            $pointer = 0;
            $result = '';
            $sl = strlen($string);
            for ($p = 0; $p < $sl; $p++) {
                $char = $string[$p];
                if ($char === '>') $pointer++;
                if ($char === '<') $pointer--;
                if (!isset($cells[$pointer])) $cells[$pointer] = 0;
                if ($char === '+') $cells[$pointer]++;
                if ($char === '-') $cells[$pointer]--;
                if ($char === '.') $result .= chr($cells[$pointer]);
                if ($char === ']') {
                    $close_pos = $p;
                    if ($cells[$pointer] !== 0) {
                        $other_brackets = 0;
                        while ($string[$p] !== '[') {
                            if ($other_brackets > 0) {
                                $other_brackets--;
                            } else {
                                $p--;
                            }
                            if ($string[$p] === ']') $other_brackets++;
                            if ($p < -255) throw new Exception("Syntax error near ']' at position {$close_pos}: matching bracket not found!");
                        }
                    }
                }
            }
            return $result;
        }

    }

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $time = microtime(true);

    echo Brainfuck::encode('Hello World!');
    // echo Brainfuck::decode("++++++++++[>+++++++>++++++++++>+++++++++++>+++++++++++>+++++++++++>+++>+++++++++>+++++++++++>+++++++++++>+++++++++++>++++++++++>+++<<<<<<<<<<<<-]>++.>+.>--.>--.>+.>++.>---.>+.>++++.>--.>.>+++.");

    var_dump(microtime(true) - $time);