<?php namespace BoundedContext\Event;

class CaseTransformer
{
    public static function to_snakecase($string)
    {
        $str = lcfirst($string);
        $lowerCase = strtolower($string);
        $result = $lowerCase[0];
        $length = strlen($str);
        for ($i = 1; $i < $length; $i++) {
            $result .= ($str[$i] === $lowerCase[$i] ? '' : "_") . $lowerCase[$i];
        }
        return $result;
    }

    public static function assert_not_two_uppercase_letters_in_a_row($str)
    {
        $length = strlen($str);
        for ($i = 1; $i < $length; $i++) {
            if (ctype_upper($str[$i-1]) && ctype_upper($str[$i])) {
                throw new TypeException("'$str' has two uppercase letters in a row, this is not allowed (the snakecase would look ugly and not human readable)");
            }
        }
    }

    public static function to_camelcase($string)
    {
        return str_replace(
            ' ',
            '',
            ucwords(str_replace(array('-', '_'), ' ', $string))
        );
    }
}