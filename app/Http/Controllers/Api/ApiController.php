<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    protected $model;
    protected $resource;
    protected array $with = [];
    protected bool $paginate = true;
    protected int $perPage = 9;
    protected array $filterableFields = [];

    public function index(Request $request)
    {
        $query = $this->model::with($this->with);

        if (method_exists(new $this->model, 'scopeActive')) {
            $query->active();
        }

        foreach ($this->filterableFields as $field) {
            if ($request->has($field) && $request->get($field) !== null && $request->get($field) !== 'all') {
                $query->where($field, $request->get($field));
            }
        }

        if ($this->paginate) {
            $items = $query->latest()->paginate($request->get('limit', $this->perPage));
        } else {
            $items = $query->latest()->get();
        }

        return $this->resource::collection($items);
    }

    public function show($id)
    {
        $item = $this->model::with($this->with)->findOrFail($id);
        return new $this->resource($item);
    }
}
