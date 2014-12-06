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

function ticks()
{
    return '`' . $string . '`';
}

function tickCommaSeperate(array $columns)
{
    return '`' . implode('`,`', $columns) . '`';
}

function commaSeperate(array $columns)
{
    return implode(',', $columns);
}