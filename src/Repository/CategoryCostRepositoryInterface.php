<?php

namespace SONFin\Repository;


interface CategoryCostRepositoryInterface extends RepositoryInterface
{
    public function sumByPeriod(string $dateStart, string $dateEnd, int $userId): array;
}