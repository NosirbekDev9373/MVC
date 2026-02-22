<?php

namespace App\Core;

class AdminMiddleware
{
    /**
     * Admin ekanligini tekshirish
     */
    public static function check()
    {
        // Session boshlanganligini tekshirish
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 1. Login bo'lmaganligini tekshirish
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        // 2. Role tekshirish
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            // Admin emas - bosh sahifaga yo'naltirish
            header('Location: /');
            exit;
        }

        // Admin - davom et
        return true;
    }
}