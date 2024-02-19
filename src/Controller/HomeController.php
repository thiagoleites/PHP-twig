<?php 

declare(strict_types=1);

namespace App\Controller;

use App\Controller;

class HomeController extends Controller {
    public function index(): void {
        echo $this->twig->render('home.html');
    }
}