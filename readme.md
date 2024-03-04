# Продвинутый Backend. Практическая работа 27.6 #
## Авторизация и аутенфикация ##

В качестве практической работы мы создадим систему регистраций:

- ООП MVC.
- Защита от CSRF и XSS уязвимостей.  
- Система регистраций позволяет регистрироваться при помощи пары логин-пароль.
- Сделана страница авторизации, на которой пользователь вводит заранее созданные логин и пароль.
- Сделана авторизация через Google отдельной кнопкой "Авторизация Google".
- Система с ролями «user» и «Google Account».
- Сделана секретная страница "Secret", на которую нельзя попасть, пока пользователь не авторизован. На этой странице отображен текст и картинка. Текст виден всем авторизованным пользователям, картинка — только пользователям с ролью  «Google Account».
- Сделана система хранения логов с использование пакета Monolog, которая записывает все неудачные попытки авторизации через логин и пароль.

Тип БД в проекте: sqlite.

Для работы приложения необходим composer. В коренвом каталоге приложения выполните команду composer update.

В папке app/config в файле config.php нужно обратить внимание на порт тестового web-сервера для корректной переадресации на разрешенный URI авторизации Google.








