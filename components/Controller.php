<?php

namespace app\components;

use app\components\core\ControllerInterface;

class Controller implements ControllerInterface
{
    public $defaultAction = 'actionIndex';

    protected $layout;

    private $defaultLayout = 'default';

    private $viewPath;

    public function __construct()
    {
        $className = end(explode('\\', static::class));
        $this->viewPath = 'views/' . lcfirst(str_replace('Controller', '', $className));
    }

    /**
     * @param string $actionName
     * @param array $params
     * @return mixed
     */
    public function runAction(string $actionName, array $params = [])
    {
        if ($actionName === '') {
            $actionName = $this->defaultAction;
        }

        return $this->$actionName;
    }

    /**
     * @param string $view
     * @param array $params
     * @return mixed
     */
    public function render(string $view, array $params = []) {
        $content = View::render($this->getView($view), $params);

        return $this->renderContent($content);
    }

    /**
     * @param string $view
     * @return string
     */
    private function getView(string $view): string
    {
        return $this->viewPath . '/' . $view;
    }

    /**
     * @param $content
     * @return mixed
     */
    private function renderContent($content)
    {
        $layoutFile = $this->findLayoutFile();
        if ($layoutFile !== false) {
            return View::renderFile($layoutFile, ['content' => $content]);
        }
    }

    /**
     * @return string
     */
    private function findLayoutFile(): string
    {
        $layout = $this->layout ?? $this->defaultLayout;

        return ROOT . "/views/layouts/$layout.php";
    }
}