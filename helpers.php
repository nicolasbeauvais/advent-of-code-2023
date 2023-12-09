#!/usr/bin/php
<?php

function debug(...$args): void
{
    echo implode(' ', $args) . PHP_EOL;
}
