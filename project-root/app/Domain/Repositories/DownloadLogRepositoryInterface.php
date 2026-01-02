<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\DownloadLog;

interface DownloadLogRepositoryInterface
{
    public function save(DownloadLog $log): DownloadLog;
    public function mostDownloadedProducts\(int $limit = 10): array;
}
