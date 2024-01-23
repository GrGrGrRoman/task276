<?php 

namespace App\models;

use App\core\Model;
use App\lib\Db;

use function App\d;

class Account extends Model
{
    public $error;

    public function validate($input, $post)
    {
        $rules =
        [
            'email' => 
            [
                'pattern' => '#^([a-z0-9_.-]{1,20}+)@([a-z0-9_.-]+)\.([a-z\.]{2,10})$#',
                'message' => 'Email указан неверно',
            ],

            'login' => 
            [
                'pattern' => '#^[a-z0-9]{2,15}$#',
                'message' => 'Логин указан неверно (разрешены только латинские буквы и цифры от 2 до 15 символов)',
            ],

            'password' => 
            [
                'pattern' => '#^[a-z0-9]{3,30}$#',
                'message' => 'Пароль указан неверно (разрешены только латинские буквы и цифры от 3 до 30 символов)',
            ],
        ];

        foreach ($input as $val)
        {
			if (!isset($post[$val]) or !preg_match($rules[$val]['pattern'], $post[$val]))
            {
                $_SESSION['errors'][] = $rules[$val]['message'];
				return false;
			}
		}
        return true;        
    }

    public function checkAccount($login, $password)
    {
        $params = 
        [
            'login' => $login,
        ];

        $hash = $this->db->column('SELECT password FROM users WHERE login = :login', $params);

        if (!$hash || !password_verify($password, $hash))
        {
            return false;
        }
        return true; 
    }

    public function getId($email)
    {
        $params = 
        [
            'email' => $email,
        ];

        return $this->db->column('SELECT id FROM users WHERE email = :email', $params);
    }

    public function checkEmailExists($email)
    {
        $params = 
        [
            'email' => $email,
        ];

        if ($this->db->column('SELECT id FROM users WHERE email = :email', $params))
        {
            return false;
        }
        return true;
    }

    public function checkLoginExists($login)
    {
        $params = 
        [
            'login' => $login,
        ];

        if ($this->db->column('SELECT id FROM users WHERE login = :login', $params))
        {
            return false;
        }
        return true;
    }

    public function accountAdd($post)
    {
        $params =
        [
            'email' => $post['email'],
            'login' => $post['login'],
            'password' => password_hash($post['password'], PASSWORD_BCRYPT),
        ];

        $this->db->query('INSERT INTO users (email, login, password) VALUES (:email, :login, :password)', $params);
        return $this->db->lastInsertId();
    }

    public function isAccountExists($id)
    {
        $params = 
        [
            'id' => $id,
        ];
        return $this->db->column('SELECT id FROM users WHERE id = :id', $params);
    }

    public function login($login): void
    {
        $params = 
        [
            'login' => $login,
        ];

        $accountData = $this->db->row('SELECT * FROM users WHERE login = :login', $params);
        $_SESSION['account'] = $accountData[0];
    }


    public function getList()
    {
        $result = $this->db->row('SELECT id, login, email FROM users');
        return $result;
    }



    static public function checkAuth(): bool
    {
        return isset($_SESSION['account']);
    }
}
