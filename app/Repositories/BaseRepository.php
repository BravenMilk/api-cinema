<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class BaseRepository
{
    protected $model;

    protected $relations = [];

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all records with optional pagination and filtering.
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAll(array $filters = [], int $perPage = 10)
    {
        $query = $this->model->newQuery();

        if (!empty($this->relations)) {
            $query->with($this->relations);
        }

        if (isset($filters['search']) && !empty($filters['search'])) {
            // Asumsi model punya array $searchable
            $searchable = $this->model->searchable ?? [];
            if (!empty($searchable)) {
                $query->where(function ($q) use ($filters, $searchable) {
                    foreach ($searchable as $field) {
                        $q->orWhere($field, 'like', '%' . $filters['search'] . '%');
                    }
                });
            }
        }

        // Filter lain bisa ditambahkan dinamis
        foreach ($filters as $key => $value) {
            if ($key !== 'search' && $key !== 'page' && $key !== 'limit' && !empty($value)) {
                 // Cek jika field ada di tabel (sederhana)
                 // Bisa diperluas dengan scope di model
                 try {
                    $query->where($key, $value);
                 } catch (\Exception $e) {
                    // Ignore invalid columns
                 }
            }
        }

        $results = $query->paginate($perPage);
        $this->relations = []; // Reset relations for next query
        return $results;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function find($id): ?Model
    {
        return $this->model->find($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update($id, array $data): bool
    {
        $record = $this->find($id);
        return $record ? $record->update($data) : false;
    }

    public function findBy(string $column, $value): ?Model
    {
        return $this->model->where($column, $value)->first();
    }

    public function delete($id): bool
    {
        $record = $this->find($id);
        return $record ? $record->delete() : false;
    }
    
    public function with(array $relations): self
    {
         $this->relations = $relations;
         return $this;
    }
}

