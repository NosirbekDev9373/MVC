<?php

namespace App\Core;

use Exception;

class Controller
{
    protected function view(string $path, $data = [], $layout = 'layout')
    {
        extract($data, EXTR_SKIP);
        $viewFile = __DIR__ . "/../Views/{$path}.php";
        if (!file_exists($viewFile)) {
            throw new Exception("View {$path} not found");
        }
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        $layoutFile = __DIR__ . "/../Views/". $layout .".php";
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