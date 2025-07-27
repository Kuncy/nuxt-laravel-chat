<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ModelDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public string $modelClass, public int $modelId, public int $userId)
    {
    }
}
