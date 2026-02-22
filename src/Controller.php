<?php

namespace App\Core;

use Exception;

class Controller
{
    protected static $rootPath;

    public static function setRootPath($path)
    {
        self::$rootPath = $path;
    }
    protected function view(string $path, $data = [], $layout = 'layout')
    {
        extract($data, EXTR_SKIP);
        $viewFile = self::$rootPath . "{$path}.php";
        if (!file_exists($viewFile)) {
            throw new Exception("View {$path} not found");
        }
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        $layoutFile = self::$rootPath . $layout .".php";
        if (!file_exists($layoutFile)) {
            // Agar layout yo'q bo'lsa, faqat content chiqarsin
            echo $content;
            return;
        }
        require $layoutFile;
    }

    protected function redirect(string $url)
    {
        header("Location: {$url}");
        exit;
    }
}