<?php

namespace App\Repositories;

use App\Brand;

class BrandRepository
{
    /**
     * Get all of the tasks for a given user.
     *
     * @return Collection
     */
    public function getAll()
    {
        return Brand::all();
    }
}