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
    
    public static function compute($value1, $value2, $operator) {
    
    }
}