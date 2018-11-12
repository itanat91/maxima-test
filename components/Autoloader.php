<?php

namespace app\components;

class Autoloader
{
//    private $namespacesMap = [];

    /**
     * @param string $namespace
     * @param string $rootDir
     * @return bool
     */
//    private function addNamespace(string $namespace, string $rootDir): bool
//    {
//        if (is_dir($rootDir)) {
//            $this->namespacesMap[$namespace] = $rootDir;
//            return true;
//        }
//
//        return false;
//    }

    public function register()
    {
        spl_autoload_register([$this, 'autoload']);
    }

    /**
     * @param string $class
     * @return bool
     */
    protected function autoload(string $class): bool
    {
        $pathParts = explode('\\', $class);
        if (is_array($pathParts)) {
            include end($pathParts) . '.php';

            return true;
        }
//        $pathParts = explode('\\', $class);
//
//        if (is_array($pathParts)) {
//            $lastPos = strrpos($class, '\\');
//            $namespace = substr($class, 0, $lastPos);
//
//            if (empty($this->namespacesMap[$namespace])) {
//                unset($pathParts[0]);
//                $pathParts = array_values($pathParts);
//                $className = $pathParts[count($pathParts) - 1];
//                unset($pathParts[count($pathParts) - 1]);
//                $dir = str_replace('\\', '/', implode('\\', $pathParts));
//                $this->addNamespace($namespace, ROOT . '/' . $dir);
//            }
//            $filePath = $this->namespacesMap[$namespace] . '/' . $className . '.php';
//            include $filePath;
//
//            return true;
//        }

        return false;
    }
}