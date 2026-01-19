<?php
declare(strict_types=1);

require_once __DIR__ . '/../core/Database.php';

class Student
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connection();
    }

    public function emailExists(string $email): bool
    {
        $stmt = $this->pdo->prepare('SELECT id FROM students WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        return (bool)$stmt->fetch();
    }

    public function create(string $name, string $email, string $password): int
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare('INSERT INTO students (name, email, password) VALUES (?, ?, ?)');
        $stmt->execute([$name, $email, $hash]);

        return (int)$this->pdo->lastInsertId();
    }

    public function authenticate(string $email, string $password): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, name, email, password FROM students WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $student = $stmt->fetch();

        if (!$student) return null;
        if (!password_verify($password, $student['password'])) return null;

        unset($student['password']);
        return $student;
    }
}
