<?php
declare(strict_types=1);

require_once __DIR__ . '/../core/BaseController.php';

class HomeController extends BaseController
{
    public function index(): void
    {
        $this->render('home');
    }
}
