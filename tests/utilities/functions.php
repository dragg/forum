<?php

/**
 * @param $class
 * @param array $attributes
 * @param int|null $times
 * @return mixed
 */
function create($class, $attributes = [], int $times = null)
{
    return factory($class, $times)->create($attributes);
}

/**
 * @param $class
 * @param array $attributes
 * @param int|null $times
 * @return mixed
 */
function make($class, $attributes = [], int $times = null)
{
    return factory($class, $times)->make($attributes);
}
