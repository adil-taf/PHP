<?php

declare(strict_types=1);

namespace App\Controllers;

use App\View;
use PDO;

class HomeController
{
    public function index(): View
    {
        try {
            $db = new PDO('mysql:host=db;dbname=my_db', 'root', 'root', []);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int) $e->getCode());
        }

        $email = 'adil@taf.com';
        $name = 'Adil Taf';
        $isActive = 1;
        $createdAt = date('Y-m-d H:i:s', strtotime('07/11/2023 9:00PM'));

        $query = 'INSERT INTO users (email, full_name, is_active, created_at)
                    VALUES (?, ?, ?, ?)';

        $stmt = $db->prepare($query);

        $stmt->execute([$email, $name, $isActive, $createdAt]);

        $id = (int) $db->lastInsertId();

        $user = $db->query('SELECT * FROM users WHERE id = ' . $id);

        echo "<pre>";
        var_dump($user);
        echo"</pre>";

        return View::make('index', ['foo' => 'bar']);
    }

    public function download()
    {
        header('Content-Type: application/pdf');
        header('Content-Disposotion: attachment; filename="myfile.pdf"');
        readfile(STORAGE_PATH . '/file.pdf');
    }

    public function upload()
    {
        $filePath = STORAGE_PATH . '/' . $_FILES['receipt']['name'];
        move_uploaded_file(
            $_FILES['receipt']['tmp_name'],
            $filePath
        );

        header('Location: /');
        exit;
    }
}
