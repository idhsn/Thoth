<?php
declare(strict_types=1);

require_once __DIR__ . '/../core/Database.php';

class Course
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connection();
    }

    public function all(): array
    {
        return $this->pdo->query('SELECT id, title, description FROM courses ORDER BY id DESC')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, title, description FROM courses WHERE id = ?');
        $stmt->execute([$id]);
        $course = $stmt->fetch();
        return $course ?: null;
    }
}
