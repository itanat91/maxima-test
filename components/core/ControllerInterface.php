<?php

namespace app\components\core;

interface ControllerInterface
{
    public function runAction(string $actionName, array $params = []);

    public function render(string $view, array $params = []);
}