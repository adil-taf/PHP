<?php

declare(strict_types=1);

namespace App;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

abstract class Model
{
    protected DB $db;
    protected EntityManager $entityManager;

    public function __construct()
    {
        $this->db = App::db();

        $this->entityManager = EntityManager::create(
            $this->db->getParams(),
            Setup::createAttributeMetadataConfiguration([__DIR__ . '/Entity'])
        );
    }
}
