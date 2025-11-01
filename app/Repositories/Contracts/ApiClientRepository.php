<?php

namespace App\Repositories\Contracts;

interface ApiClientRepository
{
    public function getResultsMassive(?int $count = null, int $concurrency = 20);

    public function getResultIndividual();
}
