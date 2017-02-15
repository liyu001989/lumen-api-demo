<?php

$app->get('/', function () use ($app) {
    return $app->version();
});
