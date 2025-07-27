<?php

namespace App\Http\Controllers\Spa\Message;

use App\Http\Controllers\Spa\BaseSpaController;
use App\Repositories\Interfaces\Message\MessageRepositoryInterface;

class MessageController extends BaseSpaController
{
    public function __construct(MessageRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}
