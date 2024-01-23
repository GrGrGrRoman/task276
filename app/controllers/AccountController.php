<?php 

namespace App\controllers;

use App\core\Controller;
use App\lib\Db;
use App\models\Account;
use Google\Client;
use Google\Service\Oauth2;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use function App\d;
use function App\dd;

class AccountController extends Controller
{
    

    public function loginAction()
    {
        if (!empty($_POST) and $_POST["token"] == $_COOKIE['PHPSESSID'])
        {
            if (!$this->model->validate(['login', 'password',], $_POST))
            {
                $_SESSION['errors'][] = 'ОШИБКА ВХОДА!';
            }
            // проверка записей на соответствие
            elseif (!$this->model->checkAccount($_POST['login'], $_POST['password']))
            {
                $message = 'Проверьте правильность ввода логина/пароля';
                $_SESSION['errors'][] = $message;
                $_SESSION['loginInput'] = $_POST['login'];
                $logger = new Logger('ACCESS');
                $logger->pushHandler(new StreamHandler(LOG . DIRECTORY_SEPARATOR . 'loginWarning.txt', Logger::WARNING));
                $logger->warning($message, array('user' => $_POST['login'], 'password' => $_POST['password'], 'time' => date('H:i:s d.m.Y')));
            }
            else
            {
                $this->model->login($_POST['login']);
                $this->view->redirect('/');
            }
        }
        $this->view->render('Вход');
    }

    public function registerAction()
    {
        if (!empty($_POST) and $_POST["token"] == $_COOKIE['PHPSESSID'])
        {
            if (!$this->model->validate(['email', 'login', 'password',], $_POST))
            {
                $_SESSION['errors'][] = 'Проверьте правильность ввода логина/пароля';
            }
            elseif (!$this->model->checkEmailExists($_POST['email']))
            {
                $_SESSION['errors'][] = 'Этот email уже используется';
            }
            elseif (!$this->model->checkLoginExists($_POST['login']))
            {
                $_SESSION['errors'][] = 'Этот логин уже используется';
            }
            elseif ($_POST['password'] != $_POST['password_repeat'])
            {
                $_SESSION['errors'][] = 'Пароли не совпадают';
            }
        else
            {
                $this->model->accountAdd($_POST);
                $_SESSION['loginInput'] = $_POST['login'];
                $_SESSION['success'][] = 'Вы успешно зарегистрированы';
                $this->view->redirect('login');
            }        
        }
        $this->view->render('Регистрация');
    }
    
    public function register_googleAction()
    {
        $gClient = new Client();
        $gClient->setApplicationName('SF_Task27_Auth');
        $gClient->setClientId(GOOGLE_CLIENT_ID);
        $gClient->setClientSecret(GOOGLE_CLIENT_SECRET);
        $gClient->setRedirectUri(GOOGLE_REDIRECT_URL);
        $gClient->addScope('profile');

        $google_oauthV2 = new Oauth2($gClient);

        if (isset($_GET['code']))
        {
            $gClient->authenticate($_GET['code']); 
            $_SESSION['token'] = $gClient->getAccessToken();
            header('Location: ' . filter_var(GOOGLE_REDIRECT_URL, FILTER_SANITIZE_URL));
        }

        if (isset($_SESSION['token']))
        { 
            $gClient->setAccessToken($_SESSION['token']); 
        }
        
        if ($gClient->getAccessToken())
        { 
            // Get user profile data from google 
            $gpUserProfile = $google_oauthV2->userinfo->get();

            $gpUserData = array(); 
            $gpUserData['oauth_uid'] = !empty($gpUserProfile['id'])?$gpUserProfile['id']:''; 
            $gpUserData['first_name'] = !empty($gpUserProfile['given_name'])?$gpUserProfile['given_name']:''; 
            $gpUserData['last_name'] = !empty($gpUserProfile['family_name'])?$gpUserProfile['family_name']:''; 
            $gpUserData['email'] = !empty($gpUserProfile['email'])?$gpUserProfile['email']:''; 
            $gpUserData['gender'] = !empty($gpUserProfile['gender'])?$gpUserProfile['gender']:''; 
            $gpUserData['locale'] = !empty($gpUserProfile['locale'])?$gpUserProfile['locale']:''; 
            $gpUserData['picture'] = !empty($gpUserProfile['picture'])?$gpUserProfile['picture']:'';

            // записываем в сессию
            $_SESSION['account']['login'] = $gpUserData['first_name'] . ' ' . $gpUserData['last_name'];
            $_SESSION['account']['role'] = 'Google Account';
            $_SESSION['success'][] = 'Вы успешно зарегистрированы с помощью учетной записи Google';

            $this->view->redirect('/');
        }
        else
        { 
            // Get login url 
            $authUrl = $gClient->createAuthUrl(); 
             
            // Render google login button
            $vars = 
            [
                'button' => '<a href="'.filter_var($authUrl, FILTER_SANITIZE_URL).'" class="btn  btn-link btn-sm">Sign in with Google</a>',
            ]; 
            $this->view->render('Регистрация Google API', $vars); 
        }
    }

    public function logoutAction()
    {
        unset($_SESSION['account']);
        session_destroy();
        $this->view->redirect('login');
    }   
    
    public function listAction()
    {
        $result = $this->model->getList();

        $vars = 
        [
            'list' => $result,
        ];

        if (!Account::checkAuth())
        {
            $this->view->redirect('login');
        }

        $this->view->render('Список пользователей', $vars);
    }    

}