<?php

namespace App\Traits;

trait HasCrud
{
    /**
     * Find a record by ID
     * @param unknown $id
     * @param array $with
     * @param array $select
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id, array $with = [], $select = ['*'])
    {
        return $this->model->with($with)->find($id, $select);
    }
    public function findLock($id, array $with = [], $select = ['*'])
    {
        return $this->model->lockForUpdate()->with($with)->find($id, $select);
    }

    /**
     * Add a new record
     * @param array $attributes
     * @param boolean $forceFill
     * @return boolean|Illuminate\Database\Eloquent\Model
     */
    public function add(array $attributes = [], $forceFill = false)
    {
        $model = $this->model->newInstance();

        if ($forceFill) {
            $model->forceFill($attributes);
        } else {
            $model->fill($attributes);
        }
        if ($model->save()) {
            return $model;
        }

        return false;
    }

    /**
     * Edit a specific record
     * @param unknown $id
     * @param array $attributes
     * @param boolean $forceFill
     * @return boolean|Illuminate\Database\Eloquent\Model
     */
    public function edit($id, array $attributes = [], $forceFill = false)
    {
        $model = $this->model->findOrFail($id);
        if ($forceFill) {
            if ($model->forceFill($attributes)->save()) {
                return $model;
            }
        } else {
            if ($model->fill($attributes)->save()) {
                return $model;
            }
        }

        return false;
    }
    public function editLock($id, array $attributes = [], $forceFill = false)
    {
        $model = $this->model->lockForUpdate()->findOrFail($id);
        if ($forceFill) {
            if ($model->forceFill($attributes)->save()) {
                return $model;
            }
        } else {
            if ($model->fill($attributes)->save()) {
                return $model;
            }
        }

        return false;
    }

    /**
     * Delete a specific record
     * @param unknown $ids
     * @return integer
     */
    public function delete($ids)
    {
        return $this->model->destroy($ids);
    }

    /**
     * Delete all recrods
     * @return integer
     */
    public function deleteAll()
    {
        return $this->model->newQuery()->delete();
    }

    /**
     * Retrieve all records.
     * @param array $with
     * @param number $perPage
     * @param array $select
     * @return \Illuminate\Support\Collection
     */
    public function all(array $with = [], $perPage = 0, $select = ['*'], $sort = ['created_at' => 'desc'])
    {
        $results = $this->model->with($with);

        foreach ($sort as $column => $order) {
            $results->orderBy($column, $order);
        }

        return $perPage ? $results->paginate($perPage, $select) : $results->get($select);
    }

    /**
    * Find filter record
    * @param array $with
    * @param array $select
    * @return \Illuminate\Database\Eloquent\Model
    */
    public function filterOption(array $with = [], $select = ['*'])
    {
        return $this->model->with($with)->select($select)->distinct()->get();
    }
}
