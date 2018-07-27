<?php
    namespace Rumd3x\Brainfuck;

    use Exception;

    class Brainfuck {

        public static function encode($string, $clean_ascii = false, $pretty = false) {
            if ($clean_ascii) {
                $string = self::clean($string);
            } 

            $pretty_spacer = '   ';

            $cells = [];
            $len = strlen($string);
            for ($c = 0; $c < $len; $c++) {
                $cells[$c] = ord($string[$c]);
            }

            $cell_data = [];
            $repeat_counter = 0;
            $bf = str_repeat("+", 10)."[";
            foreach($cells as $c => $char) {
                if (isset($cells[$c-1]) && $cells[$c-1] === $char) {
                    $cell_data[] = ".";
                    $repeat_counter++;
                    continue;
                }
                $data = [];
                $data['not_rounded'] = $char/10;
                $data['rounded'] = intval(round($data['not_rounded']));
                $cell_data[] = $data;
                $bf .= $pretty ? PHP_EOL.$pretty_spacer.">".str_repeat("+", $data['rounded']) : ">".str_repeat("+", $data['rounded']);
            }

            $move_amount = count($cells) - $repeat_counter;
            $bf .= $pretty ? PHP_EOL.$pretty_spacer.str_repeat("<", $move_amount)."-".PHP_EOL."]" : str_repeat("<", $move_amount)."-]";

            foreach($cell_data as $data) {
                if ($data === ".") {
                    $bf .= ".";
                    continue;
                }
                $diff = $data['not_rounded'] - $data['rounded'];
                $times = abs(intval(strval($diff*10)));
                $sign = $diff >= 0 ? "+" : "-";
                $bf .= $pretty ? PHP_EOL.">".str_repeat($sign, $times)."." : ">".str_repeat($sign, $times).".";
            }

            if ($pretty) {
                $bf = self::prettify($bf);
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

        public static function prettify($bf) {
            $bf = str_replace("++++++", "+++++ +", $bf);
            $bf = str_replace("------", "----- -", $bf);  
            $bf = str_replace("+>", "+ >", $bf);              
            $bf = str_replace("->", "- >", $bf);              
            $bf = str_replace("+<", "+ <", $bf);              
            $bf = str_replace("-<", "- <", $bf);  
            $bf = str_replace("[>", "[ >", $bf);              
            $bf = str_replace("]>", "] >", $bf);              
            $bf = str_replace("[<", "[ <", $bf);              
            $bf = str_replace("]<", "] <", $bf); 
            $bf = str_replace("+[", "+ [", $bf);              
            $bf = str_replace("-[", "- [", $bf);              
            $bf = str_replace("+]", "+ ]", $bf);              
            $bf = str_replace("-]", "- ]", $bf);  
            $bf = str_replace(">[", "> [", $bf);              
            $bf = str_replace("<[", "< [", $bf);              
            $bf = str_replace(">]", "> ]", $bf);              
            $bf = str_replace("<]", "< ]", $bf); 
            $bf = str_replace(">+", "> +", $bf);              
            $bf = str_replace("<+", "< +", $bf);              
            $bf = str_replace(">-", "> -", $bf);              
            $bf = str_replace("<-", "< -", $bf);         
            $bf = str_replace(".", " . ", $bf);         
            while(strpos($bf, ".  .") !== false) $bf = str_replace(".  .", "..", $bf);         
            return $bf;
        }

        public static function clean($orig_text) {
            $text = $orig_text;        
            // Single letters
            $text = preg_replace("/[∂άαáàâãªä]/u",      "a", $text);
            $text = preg_replace("/[∆лДΛдАÁÀÂÃÄ]/u",     "A", $text);
            $text = preg_replace("/[ЂЪЬБъь]/u",           "b", $text);
            $text = preg_replace("/[βвВ]/u",            "B", $text);
            $text = preg_replace("/[çς©с]/u",            "c", $text);
            $text = preg_replace("/[ÇС]/u",              "C", $text);        
            $text = preg_replace("/[δ]/u",             "d", $text);
            $text = preg_replace("/[éèêëέëèεе℮ёєэЭ]/u", "e", $text);
            $text = preg_replace("/[ÉÈÊË€ξЄ€Е∑]/u",     "E", $text);
            $text = preg_replace("/[₣]/u",               "F", $text);
            $text = preg_replace("/[НнЊњ]/u",           "H", $text);
            $text = preg_replace("/[ђћЋ]/u",            "h", $text);
            $text = preg_replace("/[ÍÌÎÏ]/u",           "I", $text);
            $text = preg_replace("/[íìîïιίϊі]/u",       "i", $text);
            $text = preg_replace("/[Јј]/u",             "j", $text);
            $text = preg_replace("/[ΚЌК]/u",            'K', $text);
            $text = preg_replace("/[ќк]/u",             'k', $text);
            $text = preg_replace("/[ℓ∟]/u",             'l', $text);
            $text = preg_replace("/[Мм]/u",             "M", $text);
            $text = preg_replace("/[ñηήηπⁿ]/u",            "n", $text);
            $text = preg_replace("/[Ñ∏пПИЙийΝЛ]/u",       "N", $text);
            $text = preg_replace("/[óòôõºöοФσόо]/u", "o", $text);
            $text = preg_replace("/[ÓÒÔÕÖθΩθОΩ]/u",     "O", $text);
            $text = preg_replace("/[ρφрРф]/u",          "p", $text);
            $text = preg_replace("/[®яЯ]/u",              "R", $text); 
            $text = preg_replace("/[ГЃгѓ]/u",              "r", $text); 
            $text = preg_replace("/[Ѕ]/u",              "S", $text);
            $text = preg_replace("/[ѕ]/u",              "s", $text);
            $text = preg_replace("/[Тт]/u",              "T", $text);
            $text = preg_replace("/[τ†‡]/u",              "t", $text);
            $text = preg_replace("/[úùûüџμΰµυϋύ]/u",     "u", $text);
            $text = preg_replace("/[√]/u",               "v", $text);
            $text = preg_replace("/[ÚÙÛÜЏЦц]/u",         "U", $text);
            $text = preg_replace("/[Ψψωώẅẃẁщш]/u",      "w", $text);
            $text = preg_replace("/[ẀẄẂШЩ]/u",          "W", $text);
            $text = preg_replace("/[ΧχЖХж]/u",          "x", $text);
            $text = preg_replace("/[ỲΫ¥]/u",           "Y", $text);
            $text = preg_replace("/[ỳγўЎУуч]/u",       "y", $text);
            $text = preg_replace("/[ζ]/u",              "Z", $text);
        
            // Punctuation
            $text = preg_replace("/[‚‚]/u", ",", $text);        
            $text = preg_replace("/[`‛′’‘]/u", "'", $text);
            $text = preg_replace("/[″“”«»„]/u", '"', $text);
            $text = preg_replace("/[—–―−–‾⌐─↔→←]/u", '-', $text);
            $text = preg_replace("/[  ]/u", ' ', $text);
        
            $text = str_replace("…", "...", $text);
            $text = str_replace("≠", "!=", $text);
            $text = str_replace("≤", "<=", $text);
            $text = str_replace("≥", ">=", $text);
            $text = preg_replace("/[‗≈≡]/u", "=", $text);       
        
            // Exciting combinations    
            $text = str_replace("ыЫ", "bl", $text);
            $text = str_replace("℅", "c/o", $text);
            $text = str_replace("₧", "Pts", $text);
            $text = str_replace("™", "tm", $text);
            $text = str_replace("№", "No", $text);        
            $text = str_replace("Ч", "4", $text);                
            $text = str_replace("‰", "%", $text);
            $text = preg_replace("/[∙•]/u", "*", $text);
            $text = str_replace("‹", "<", $text);
            $text = str_replace("›", ">", $text);
            $text = str_replace("‼", "!!", $text);
            $text = str_replace("⁄", "/", $text);
            $text = str_replace("∕", "/", $text);
            $text = str_replace("⅞", "7/8", $text);
            $text = str_replace("⅝", "5/8", $text);
            $text = str_replace("⅜", "3/8", $text);
            $text = str_replace("⅛", "1/8", $text);        
            $text = preg_replace("/[‰]/u", "%", $text);
            $text = preg_replace("/[Љљ]/u", "Ab", $text);
            $text = preg_replace("/[Юю]/u", "IO", $text);
            $text = preg_replace("/[ﬁﬂ]/u", "fi", $text);
            $text = preg_replace("/[зЗ]/u", "3", $text); 
            $text = str_replace("£", "(pounds)", $text);
            $text = str_replace("₤", "(lira)", $text);
            $text = preg_replace("/[‰]/u", "%", $text);
            $text = preg_replace("/[↨↕↓↑│]/u", "|", $text);
            $text = preg_replace("/[∞∩∫⌂⌠⌡]/u", "", $text);      
        
            //2) Translation CP1252.
            $trans = get_html_translation_table(HTML_ENTITIES);
            $trans['f'] = '&fnof;';    // Latin Small Letter F With Hook
            $trans['-'] = array(
                '&hellip;',     // Horizontal Ellipsis
                '&tilde;',      // Small Tilde
                '&ndash;'       // Dash
            );

            $trans["+"] = '&dagger;';    // Dagger
            $trans['#'] = '&Dagger;';    // Double Dagger         
            $trans['M'] = '&permil;';    // Per Mille Sign
            $trans['S'] = '&Scaron;';    // Latin Capital Letter S With Caron        
            $trans['OE'] = '&OElig;';    // Latin Capital Ligature OE
            $trans["'"] = array(
                '&lsquo;',  // Left Single Quotation Mark
                '&rsquo;',  // Right Single Quotation Mark
                '&rsaquo;', // Single Right-Pointing Angle Quotation Mark
                '&sbquo;',  // Single Low-9 Quotation Mark
                '&circ;',   // Modifier Letter Circumflex Accent
                '&lsaquo;'  // Single Left-Pointing Angle Quotation Mark
            );
        
            $trans['"'] = array(
                '&ldquo;',  // Left Double Quotation Mark
                '&rdquo;',  // Right Double Quotation Mark
                '&bdquo;',  // Double Low-9 Quotation Mark
            );
        
            $trans['*'] = '&bull;';    // Bullet
            $trans['n'] = '&ndash;';    // En Dash
            $trans['m'] = '&mdash;';    // Em Dash        
            $trans['tm'] = '&trade;';    // Trade Mark Sign
            $trans['s'] = '&scaron;';    // Latin Small Letter S With Caron
            $trans['oe'] = '&oelig;';    // Latin Small Ligature OE
            $trans['Y'] = '&Yuml;';    // Latin Capital Letter Y With Diaeresis
            $trans['euro'] = '&euro;';    // euro currency symbol
            ksort($trans);
        
            foreach ($trans as $k => $v) {
                $text = str_replace($v, $k, $text);
            }
        
            // 3) remove <p>, <br/> ...
            $text = strip_tags($text);
        
            // 4) &amp; => & &quot; => '
            $text = html_entity_decode($text);
        
        
            // transliterate
            if (function_exists('iconv')) {
                $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
            }
        
            // remove non ascii characters
            $text = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $text);      
        
            return $text;
        }

    }