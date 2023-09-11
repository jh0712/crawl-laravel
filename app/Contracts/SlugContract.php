<?php

namespace App\Contracts;

interface SlugContract
{
    /**
     * Find a record by slug
     * @param unknown $slug
     * @param array $with
     * @param array $select
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findBySlug($slug, array $with = [], $select = ['*']);

    /**
     * Edit a record by slug
     * @param unknown $slug
     * @param array $attributes
     * @param boolean $forceFill
     * @return boolean
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function editBySlug($slug, array $attributes = [], $forceFill = false);

    /**
     * Delete a record by slug
     * @param unknown $slug
     * @return integer
     */
    public function deleteBySlug($slug);
}
