<?php 

namespace App\controllers;

use App\core\Controller;
use App\lib\Db;
use App\models\Account;

use function App\d;

class MainController extends Controller
{
    public function indexAction()
    {
        $this->view->render('Главная страница');
    }
    
    public function secretAction()
    {
        if (!Account::checkAuth())
        {
            $this->view->redirect('login');
        }
        $this->view->render('Сектретная страница');
    }
    
}