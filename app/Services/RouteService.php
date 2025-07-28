<?php

namespace App\Services;

use Illuminate\Support\Facades\Route;

class RouteService
{
    /**
     * creates routes for CRUD-Actions on api for models.
     *
     * @param string $modelName specify model-name for permission-guards
     * @param string $controllerClassName
     */
    public static function createCrudRoutes(string $modelName, string $controllerClassName): void
    {
        Route::get('/', [$controllerClassName, 'getFilteredCollection'])
            ->name("$modelName.getCollection");
            //->middleware(['can:' . $modelName . ':read']);
        Route::post('/get', [$controllerClassName, 'getFilteredCollection'])
            ->name("$modelName.postCollection");
            //->middleware(['can:' . $modelName . ':read']);
        Route::get('/all', [$controllerClassName, 'getAll'])
            ->name("$modelName.getAll");
            //->middleware(['can:' . $modelName . ':read']);
        Route::post('/create', [$controllerClassName, 'create'])
            ->name("$modelName.create");
            //->middleware(['can:' . $modelName . ':create']);
        Route::get('/{id}', [$controllerClassName, 'getById'])
            ->name("$modelName.getById");
           // ->middleware(['can:' . $modelName . ':read']);
        Route::put('/{id}', [$controllerClassName, 'update'])
            ->name("$modelName.update");
           // ->middleware(['can:' . $modelName . ':update']);
        Route::delete('/{id}', [$controllerClassName, 'delete'])
            ->name("$modelName.delete");
           // ->middleware(['can:' . $modelName . ':delete']);
    }
}
