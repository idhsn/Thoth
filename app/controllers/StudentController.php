<?php
declare(strict_types=1);

require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Csrf.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Enrollment.php';

class StudentController extends BaseController
{
    public function showRegister(): void
    {
        $this->render('student/register', ['errors' => [], 'old' => []]);
    }

    public function register(): void
    {
        Csrf::verify($_POST['_csrf'] ?? null);

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = (string)($_POST['password'] ?? '');

        $errors = [];
        if ($name === '') $errors[] = 'Name is required.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
        if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';

        $studentModel = new Student();

        if (!$errors && $studentModel->emailExists($email)) {
            $errors[] = 'Email already used.';
        }

        if ($errors) {
            $this->render('student/register', ['errors' => $errors, 'old' => ['name' => $name, 'email' => $email]]);
            return;
        }

        $id = $studentModel->create($name, $email, $password);
        Auth::login($id);
        $this->redirect('/student/dashboard');
    }

    public function showLogin(): void
    {
        $this->render('student/login', ['errors' => [], 'old' => []]);
    }

    public function login(): void
    {
        Csrf::verify($_POST['_csrf'] ?? null);

        $email = trim($_POST['email'] ?? '');
        $password = (string)($_POST['password'] ?? '');

        $errors = [];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
        if ($password === '') $errors[] = 'Password is required.';

        if ($errors) {
            $this->render('student/login', ['errors' => $errors, 'old' => ['email' => $email]]);
            return;
        }

        $studentModel = new Student();
        $student = $studentModel->authenticate($email, $password);

        if (!$student) {
            $this->render('student/login', ['errors' => ['Invalid credentials.'], 'old' => ['email' => $email]]);
            return;
        }

        Auth::login((int)$student['id']);
        $this->redirect('/student/dashboard');
    }

    public function logout(): void
    {
        Csrf::verify($_POST['_csrf'] ?? null);
        Auth::logout();
        $this->redirect('/login');
    }

    public function dashboard(): void
    {
        Auth::requireLogin();

        $courseModel = new Course();
        $enrollmentModel = new Enrollment();

        $courses = $courseModel->all();
        $enrolledIds = $enrollmentModel->enrolledCourseIds(Auth::id());

        $this->render('student/dashboard', [
            'courses' => $courses,
            'enrolledIds' => $enrolledIds,
        ]);
    }

    public function course(string $id): void
    {
        Auth::requireLogin();

        if (!ctype_digit($id)) {
            http_response_code(404);
            echo 'Course not found';
            return;
        }

        $courseModel = new Course();
        $enrollmentModel = new Enrollment();

        $course = $courseModel->find((int)$id);
        if (!$course) {
            http_response_code(404);
            echo 'Course not found';
            return;
        }

        $isEnrolled = $enrollmentModel->isEnrolled(Auth::id(), (int)$id);

        $this->render('student/course', [
            'course' => $course,
            'isEnrolled' => $isEnrolled,
        ]);
    }

    public function enroll(string $id): void
    {
        Auth::requireLogin();
        Csrf::verify($_POST['_csrf'] ?? null);

        if (!ctype_digit($id)) {
            http_response_code(404);
            echo 'Course not found';
            return;
        }

        $enrollmentModel = new Enrollment();
        $enrollmentModel->enroll(Auth::id(), (int)$id);

        $this->redirect('/student/course/' . $id);
    }
}
