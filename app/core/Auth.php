<?php
declare(strict_types=1);

require_once __DIR__ . '/../models/Student.php';

class Auth
{
    public static function check(): bool
    {
        return isset($_SESSION['student_id']) && is_numeric($_SESSION['student_id']);
    }

    public static function id(): ?int
    {
        return self::check() ? (int)$_SESSION['student_id'] : null;
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: /login');
            exit;
        }
    }

    public static function login(int $studentId): void
    {
        $_SESSION['student_id'] = $studentId;
    }

    public static function logout(): void
    {
        unset($_SESSION['student_id']);
        session_regenerate_id(true);
    }
}
