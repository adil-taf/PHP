<?php

declare(strict_types=1);

namespace App;

use Doctrine\ORM\EntityManager;

abstract class Model
{
    protected DB $db;
    protected EntityManager $entityManager;

    public function __construct()
    {
        $this->db = App::db();
        $this->entityManager = App::entityManager();
    }
}
