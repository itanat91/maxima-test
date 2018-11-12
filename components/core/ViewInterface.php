<?php

namespace app\components\core;

interface ViewInterface
{
    public static function render(string $view, array $params = []);
}