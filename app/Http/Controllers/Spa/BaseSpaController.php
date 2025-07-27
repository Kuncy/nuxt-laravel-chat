<?php

namespace App\Http\Controllers\Spa;

use App\Events\ModelCreated;
use App\Events\ModelDeleted;
use App\Events\ModelUpdated;
use App\Http\Requests\UpdateOrCreateModelRequest;
use App\Repositories\Interfaces\IEloquentRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Throwable;


abstract class BaseSpaController extends Controller
{
    /**
     * @var class-string<JsonResource>
     */
    protected string $resourceClass = JsonResource::class;

    protected IEloquentRepository $repository;

    protected bool $useObjectCache = false;
    protected string $cachePrefix = '';

    /**
     * @var int
     * Cache Lifetime in s
     */
    protected int $objectCacheLifeTime = 300;

    public function __construct(IEloquentRepository $repository)
    {
        $this->repository = $repository;
    }


    /**
     * @param string $message
     * @param int $statusCode
     * @param array $additionalData
     * @return JsonResponse
     * @author Kuncy
     * */
    protected function errorResponse(string $message, int $statusCode = 500, array $additionalData = []): JsonResponse
    {
        return response()->json(['message' => $message, ...$additionalData], $statusCode);
    }

    /**
     * @param Throwable $e
     * @return JsonResponse
     * @author Kuncy
     */
    protected function logAndReturnError(Throwable $e): JsonResponse
    {
        Log::error($e);
        $additionalData = [];
        if (env('APP_DEBUG', false)) {
            $additionalData['trace'] = $e->getTrace();
        }
        return $this->errorResponse($e->getMessage(), 500, $additionalData);
    }

    /**
     * @param array $data
     * @param int $statusCode
     * @return JsonResponse
     * @author Kuncy
     */
    public function successResponse(array $data = [], int $statusCode = 200): JsonResponse
    {
        return response()->json($data, $statusCode);
    }

    /**
     * @param Request $request
     * @return JsonResponse|JsonResource
     * @author Kuncy
     */
    public function getById(Request $request): JsonResponse|JsonResource
    {
        $id = $request->route('id');
        try {
            if ($this->useObjectCache) {
                $data = Cache::remember("{$this->cachePrefix}_$id", $this->objectCacheLifeTime, function () use ($id) {
                    return $this->repository->getById($id);
                });
            } else {
                $data = $this->repository->getById($id);
            }
            return new $this->resourceClass($data);
        } catch (ModelNotFoundException) {
            return $this->errorResponse("No entity with id $id found.", 404);
        }
    }

    /**
     * @param UpdateOrCreateModelRequest $request
     * @return JsonResponse|JsonResource
     * @author Kuncy
     */
    public function create(UpdateOrCreateModelRequest $request): JsonResponse|JsonResource
    {
        if (!empty($this->getCreateValidationRules())) {
            try {
                $validator = Validator::make($request->input('data'), $this->getCreateValidationRules());
                $validated = $validator->validate();

                $newEntity = $this->repository->create($validated);

                Event::dispatch(new ModelCreated($newEntity, $request->user()->id));

                return new $this->resourceClass($newEntity);
            } catch (ValidationException $e) {
                Log::error($e);
                $additionalData = [
                    'validationErrors' => [
                        ...$e->validator->errors()->toArray()
                    ]
                ];
                return $this->errorResponse("Validation on the given data failed.", 422, $additionalData);
            } catch (Throwable $e) {
                return $this->logAndReturnError($e);
            }
        }
        try {
            $model = $this->repository->create($request->input('data'));

            // if cache is used, delete the all entry here.
            if ($this->useObjectCache) {
                if ($this->cachePrefix === '') {
                    throw new \RuntimeException("Invalid Cache-Prefix. Prefix must not be empty.");
                }
                Cache::forget("{$this->cachePrefix}_all");
            }

            return new $this->resourceClass($model);
        } catch (Throwable $e) {
            return $this->logAndReturnError($e);
        }
    }

    protected function getCreateValidationRules(): array
    {
        return [];
    }

    /**
     * @param UpdateOrCreateModelRequest $request
     * @return JsonResponse|JsonResource
     * @author Kuncy
     */
    public function update(UpdateOrCreateModelRequest $request): JsonResponse|JsonResource
    {
        $id = $request->route('id');
        if (!empty($this->getUpdateValidationRules())) {
            try {
                $validator = Validator::make($request->input('data'), $this->getUpdateValidationRules());
                $validated = $validator->validate();

                $newEntity = $this->repository->update($id, $validated);

                Event::dispatch(new ModelUpdated($newEntity, $request->user()->id));

                return new $this->resourceClass($newEntity);
            } catch (ValidationException $e) {
                Log::error($e);
                $additionalData = [
                    'validationErrors' => [
                        ...$e->validator->errors()->toArray()
                    ]
                ];
                return $this->errorResponse("Validation on the given data failed.", 422, $additionalData);
            } catch (Throwable $e) {
                return $this->logAndReturnError($e);
            }
        }

        try {
            $model = $this->repository->update($id, $request->input('data'));

            // if cache is used, delete the updated entry here.
            if ($this->useObjectCache) {
                if ($this->cachePrefix === '') {
                    throw new \RuntimeException("Invalid Cache-Prefix. Prefix must not be empty.");
                }
                if (Cache::has("{$this->cachePrefix}_$id")) {
                    Cache::forget("{$this->cachePrefix}_$id");
                }
                Cache::forget("{$this->cachePrefix}_all");
            }

            return new $this->resourceClass($model);
        } catch (Throwable $e) {
            return $this->logAndReturnError($e);
        }
    }

    protected function getUpdateValidationRules(): array
    {
        return [];
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @author Kuncy
     */
    public function delete(Request $request): JsonResponse
    {
        $id = $request->route('id');
        try {
            $this->repository->deleteById($id);

            Event::dispatch(new ModelDeleted($this->repository->model::class, $id, $request->user()->id));

            // if cache is used, delete the deleted entry here.
            if ($this->useObjectCache) {
                if ($this->cachePrefix === '') {
                    throw new \RuntimeException("Invalid Cache-Prefix. Prefix must not be empty.");
                }
                if (Cache::has("{$this->cachePrefix}_$id")) {
                    Cache::forget("{$this->cachePrefix}_$id");
                }
                Cache::forget("{$this->cachePrefix}_all");
            }

            return $this->successResponse(["message" => "Entity with id "]);
        } catch (ModelNotFoundException) {
            return $this->errorResponse("Model with id $id not found!", 404);
        } catch (Throwable $e) {
            return $this->logAndReturnError($e);
        }
    }

    public function getFilteredCollection(Request $request): JsonResource|JsonResponse
    {
        try {
            $perPage = $request->input('perPage') ?? 20;
            $page = $request->input('page') ?? 1;
            $orderBy = $request->input('orderBy') ?? 'id';
            $orderDesc = $request->input('orderDesc') ?? false;
            $filter = $request->input('filter') ?? [];

            if (is_string($filter)) {
                $filter = json_decode($filter, true, 512, JSON_THROW_ON_ERROR);
            }

            // fix for nuxt
            if (count($filter) > 0 && is_string($filter[0])) {
                $newFilterArr =[];
                foreach($filter as $item){
                    $newFilterArr[] = json_decode($item, true, 512, JSON_THROW_ON_ERROR);
                }
                $filter = $newFilterArr;
            }

            return $this->resourceClass::collection($this->repository->getFilteredCollection(
                $perPage, $page, $orderBy, $orderDesc, $filter
            ));
        } catch (Throwable $e) {
            return $this->logAndReturnError($e);
        }
    }

    public function getAll(): JsonResponse|JsonResource
    {
        try {
            if ($this->useObjectCache) {
                $data = Cache::remember("{$this->cachePrefix}_all", $this->objectCacheLifeTime, function (){
                    return $this->repository->getAll();
                });
            } else {
                $data = $this->repository->getAll();
            }
            return $this->resourceClass::collection($data);
        } catch (Throwable $e) {
            return $this->logAndReturnError($e);
        }
    }
}
