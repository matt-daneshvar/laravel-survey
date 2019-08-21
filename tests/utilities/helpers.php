<?php

function make($class, $attributes = [])
{
    return factory($class)->make($attributes);
}

function create($class, $attributes = [])
{
    return factory($class)->create($attributes);
}
