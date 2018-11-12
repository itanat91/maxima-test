<?php

namespace app\components;

class Application
{
    private $defaultRoute;

    /**
     * Application constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        if (!empty($config)) {
            $this->setConfiguration($config);
        }
    }

    /**
     * @throws \HttpException
     */
    public function run()
    {
        $uri = $this->getURI();
        if (empty($uri)) {
            if (!isset($this->defaultRoute)) {
                throw new \HttpException('Controller and action does not exist', 404);
            } else {
                /** @var Controller $controllerObj */
                list($controllerName, $actionName, $params) = $this->createController($this->defaultRoute);
                $controllerObj = new $controllerName();
                $controllerObj->runAction($actionName, $params);
            }
        }
        list($controllerName, $actionName, $params) = $this->createController($uri);

        if (!$this->hasClass($controllerName)) {
            throw new \HttpException('Controller does not exist', 404);
        }

        $controllerObj = new $controllerName();

        if (!$this->hasAction($controllerObj, $actionName)) {
            throw new \HttpException('Action does not exist', 404);
        }

        $controllerObj->runAction($actionName, $params);
    }

    private function createController(string $uri)
    {
        $actionName = '';
        $params = [];
        $pathParts = explode('/', $uri);
        $controllerName = 'controllers/' . ucfirst(array_shift($pathParts)) . 'Controller';
        if ($pathParts) {
            $actionAndParams = explode('?', array_shift($pathParts));
            $actionName = 'action' . ucfirst(array_shift($actionAndParams));
            $actionAndParams && $params = $this->getParams(array_shift($actionAndParams));
        }

        return [$controllerName, $actionName, $params];
    }

    /**
     * @param string $params
     * @return array
     */
    private function getParams(string $params): array
    {
        $arrayOfParams = [];
        foreach (explode('&', $params) as $param) {
            list($name, $value) = explode('=', $param);
            $arrayOfParams[$name] = $value;
        }

        return $arrayOfParams;
    }

    /**
     * @param string $className
     * @return bool
     */
    private function hasClass(string $className): bool
    {
        if (!class_exists($className)) {
            return false;
        }

        return true;
    }

    /**
     * @param $controllerObject
     * @param string $actionName
     * @return bool
     */
    private function hasAction($controllerObject, string $actionName): bool
    {
        if (!method_exists($controllerObject, $actionName)) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    private function getURI(): string
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }

        return '';
    }

    /**
     * @param array $config
     */
    private function setConfiguration(array $config)
    {
        if (isset($config['defaultRoute'])) {
            $this->defaultRoute = $config['defaultRoute'];
        }
    }
}