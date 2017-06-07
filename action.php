<?php

function main(array $args) : array
{
    echo "Started my PHP Action";

    $name = $args["name"] ?? "World";

    return [
        "greeting" => "Hello $name!",
        "time" => date("r"),
    ];
}
