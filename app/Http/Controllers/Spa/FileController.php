<?php

namespace App\Http\Controllers\Spa;

use App\Repositories\Interfaces\FileRepositoryInterface;

class FileController extends BaseSpaController
{
    public function __construct(FileRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

}
