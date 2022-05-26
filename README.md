# Web-Dictionary
#Config

Чтобы прописать подключение к базам данных нужно переименовать файл config.php.example в config.php

Внутри файла прописать данные к базе данных

    $dbh = new pdo( 'mysql:host=localhost;
    				dbname=', // название базы данных
                    '', // имя пользователя
                    '', // пароль
                    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

В базу данных нужно добавить файл volga_it.sql.example, но для начала его нужно переименовать в volga_it.sql
