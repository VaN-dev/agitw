<?php
// src/Van/WallBundle/Utils/Utils.php
namespace Van\WallBundle\Utils;

class Utils
{
    public static function sanitizePost($text)
    {
        $sanitized = strip_tags($text);
        return $sanitized;
    }
    
    public static function buildLink($string) {
        
        $pattern1 = '!((https?|ftp://)[-a-zA-Z?-??-?()0-9@:%_+.~#?&;//=]+)!i';
        $pattern2 = '!((www.)[-a-zA-Z?-??-?()0-9@:%_+.~#?&;//=]+)!i';
        
        $stringLink = preg_replace($pattern1, '<a href="$1">$1</a>', $string);
        if($stringLink != $string) {
            return $stringLink;
        }
        else {
            $stringLink = preg_replace($pattern2, '<a href="http://$1">$1</a>', $string);
            if($stringLink != $string) {
                return $stringLink;
            }
        }

        return $string;
    }
}