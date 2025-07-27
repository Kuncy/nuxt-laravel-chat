<?php

namespace App\Http\Controllers\Spa;

use App\Repositories\Interfaces\GroupRepositoryInterface;

class GroupController extends BaseSpaController
{
    public function __construct(GroupRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

}
