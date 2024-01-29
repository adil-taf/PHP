<?php

declare(strict_types=1);

namespace App\Models;

use App\Model;

class SignUp extends Model
{
    public function __construct(protected User $userModel, protected Invoice $invoiceModel)
    {
        parent::__construct();
    }
    public function register(array $userInfo, array $invoiceInfo): int
    {
        try {
            $this->db->beginTransaction();

            $userId = $this->userModel->create($userInfo['email'], $userInfo['name']);
            $invoiceId = $this->invoiceModel->create(
                $invoiceInfo['invoiceNumber'],
                $invoiceInfo['amount'],
                $userId,
                $invoiceInfo['status']
            );

            $this->db->commit();
        } catch (\Throwable $e) {
            if ($this->db->isTransactionActive()) {
                $this->db->rollBack();
            }
            throw $e;
        }

        return $invoiceId;
    }
}
