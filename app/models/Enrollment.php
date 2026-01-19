<?php
declare(strict_types=1);

require_once __DIR__ . '/../core/Database.php';

class Enrollment
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connection();
    }

    public function enroll(int $studentId, int $courseId): void
    {
        // Avoid duplicates (table also has UNIQUE constraint)
        $stmt = $this->pdo->prepare('INSERT IGNORE INTO enrollments (student_id, course_id) VALUES (?, ?)');
        $stmt->execute([$studentId, $courseId]);
    }

    public function isEnrolled(int $studentId, int $courseId): bool
    {
        $stmt = $this->pdo->prepare('SELECT id FROM enrollments WHERE student_id = ? AND course_id = ? LIMIT 1');
        $stmt->execute([$studentId, $courseId]);
        return (bool)$stmt->fetch();
    }

    public function enrolledCourseIds(int $studentId): array
    {
        $stmt = $this->pdo->prepare('SELECT course_id FROM enrollments WHERE student_id = ?');
        $stmt->execute([$studentId]);

        return array_map(fn($r) => (int)$r['course_id'], $stmt->fetchAll());
    }
}
