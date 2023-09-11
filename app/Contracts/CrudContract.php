<?php

namespace App\Contracts;

interface CrudContract
{
    /**
     * Find a record by ID
     * @param unknown $id
     * @param array $with
     * @param array $select
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id, array $with = [], $select = ['*']);
    public function findLock($id, array $with = [], $select = ['*']);

    /**
     * Add a new record
     * @param array $attributes
     * @param boolean $forceFill
     * @return boolean|Illuminate\Database\Eloquent\Model
     */
    public function add(array $attributes = [], $forceFill = false);

    /**
     * Edit a specific record
     * @param unknown $id
     * @param array $attributes
     * @param boolean $forceFill
     * @return boolean|Illuminate\Database\Eloquent\Model
     */
    public function edit($id, array $attributes = [], $forceFill = false);
    public function editLock($id, array $attributes = [], $forceFill = false);

    /**
     * Delete a specific record
     * @param unknown $ids
     * @return integer
     */
    public function delete($ids);

    /**
     * Delete all recrods
     * @return integer
     */
    public function deleteAll();

    /**
     * Retrieve all records.
     * @param array $with
     * @param number $perPage
     * @param array $select
     * @return \Illuminate\Support\Collection
     */
    public function all(array $with = [], $perPage = 0, $select = ['*'], $sort = ['created_at' => 'desc']);

    public function filterOption(array $with = [], $select = ['*']);
}
