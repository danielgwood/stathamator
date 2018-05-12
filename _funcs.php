<?php

/**
 * Return an item from an array.
 *
 * @param  int   $index
 * @param  array $array
 * @param  bool  $lcfirst
 * @return string
 */
function getString($array, $index, $lcfirst = false)
{
    $phrase = $array[$index];

    if ($lcfirst) {
        $phrase = lcfirst($phrase);
    }

    return htmlentities($phrase);
}

/**
 * Does a string start with a string?
 *
 * @param  string $string
 * @param  string $query
 * @return bool
 */
function startsWith($string, $query)
{
    return substr($string, 0, strlen($query)) === $query;
}

/**
 * Truncate a string preserving words.
 *
 * @param  string $string
 * @param  int    $length
 * @param  bool   $ellipsis
 * @return string
 */
function truncateString($string, $length, $ellipsis = false)
{
    if (strlen($string) <= $length) {
        return $string;
    }

    if ($ellipsis) {
        $length -= 1;
    }

    $string = substr($string, 0, $length + 1); // leave one more character
    if ($last_space = strrpos($string, ' ')) { // space exists AND is not on position 0
        $string = substr($string, 0, $last_space);

    } else {
        $string = substr($string, 0, $length);
    }

    if ($ellipsis) {
        $string .= '…';
    }

    return $string;
}
