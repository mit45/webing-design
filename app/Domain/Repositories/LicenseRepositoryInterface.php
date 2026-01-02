<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\License;

interface LicenseRepositoryInterface
{
    public function save(License $license): License;
    public function findByKey(string $key): ?License;
}
