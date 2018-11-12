<?php

namespace app\components;

use app\components\core\ViewInterface;

class View implements ViewInterface
{
    /**
     * @param string $view
     * @param array $params
     * @return string
     * @throws \HttpException
     * @throws \Throwable
     */
    public static function render(string $view, array $params = [])
    {
        return self::renderFile($view, $params);
    }

    /**
     * @param string $viewFile
     * @param array $params
     * @return string
     * @throws \HttpException
     * @throws \Throwable
     */
    public static function renderFile(string $viewFile, array $params = [])
    {
        if (!is_file($viewFile)) {
            throw new \HttpException("The view file does not exist: $viewFile");
        }

        return self::renderPhpFile($viewFile, $params);
    }

    /**
     * @param $file
     * @param array $params
     * @return string
     * @throws \Throwable
     */
    private static function renderPhpFile($file, $params = [])
    {
        $_obInitialLevel_ = ob_get_level();
        ob_start();
        ob_implicit_flush(false);
        extract($params, EXTR_OVERWRITE);
        try {
            require($file);
            return ob_get_clean();
        } catch (\Exception $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        } catch (\Throwable $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        }
    }
}