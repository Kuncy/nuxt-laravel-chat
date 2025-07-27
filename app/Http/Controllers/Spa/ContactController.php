<?php

namespace App\Http\Controllers\Spa;

use App\Repositories\Interfaces\ContactRepositoryInterface;

class ContactController extends BaseSpaController
{
    public function __construct(ContactRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

}
