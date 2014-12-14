<?php

if ( ! function_exists('dd'))
{
    /**
     * Dump the passed variables and end the script.
     *
     * @param  mixed
     * @return void
     */
    function dd()
    {
        array_map(function($x) { var_dump($x); }, func_get_args()); die;
    }

}

function ticks($field)
{
    return '`' . $field . '`';
}

function tickCommaSeperate(array $columns)
{
    return '`' . implode('`,`', $columns) . '`';
}

function commaSeperate(array $columns)
{
    return implode(',', $columns);
}

function keysToLower($mixed)
{
    return is_null($mixed) ? $mixed : array_change_key_case($mixed);
}

function pretty($number)
{
    return number_format($number);
}