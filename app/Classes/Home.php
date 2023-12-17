<?php

declare(strict_types=1);

namespace App\Classes;

class Home
{
    public function index(): string
    {
        return <<<FORM
<form action="/upload" method="post" enctype="multipart/form-data">
  <input type="file" name="receipt" />
  <button type="submit">Upload</button>
</form>
FORM;
    }

    public function upload()
    {
        $filePath = STORAGE_PATH . '/' . $_FILES['receipt']['name'];
        move_uploaded_file(
            $_FILES['receipt']['tmp_name'],
            $filePath
        );
        echo"<pre>";
        var_dump(pathinfo($filePath));
        echo"</pre>";
    }
}
