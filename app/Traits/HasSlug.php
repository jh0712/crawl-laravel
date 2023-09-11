<?php

namespace App\Traits;

trait HasSlug
{
    /**
     * The slug column
     * @var string
     */
    protected $slug = 'name';

    protected $transactionModel;

    /**
     * Find a record by slug
     * @param unknown $slug
     * @param array $with
     * @param array $select
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findBySlug($slug, array $with = [], $select = ['*'])
    {
        return $this->model->where($this->slug, $slug)->with($with)->first($select);
    }

    /**
     * Edit a record by slug
     * @param unknown $slug
     * @param array $attributes
     * @param boolean $forceFill
     * @return boolean
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function editBySlug($slug, array $attributes = [], $forceFill = false)
    {
        $model = $this->model->where($this->slug, $slug)->firstOrFail();

        if ($forceFill) {
            if ($model->fill($attributes)->save()) {
                return $model;
            }
        } else {
            if ($model->forceFill($attributes)->save()) {
                return $model;
            }
        }
        return false;
    }

    /**
     * Delete a record by slug
     * @param unknown $slug
     * @return integer
     */
    public function deleteBySlug($slug)
    {
        return $this->model->where($this->slug, $slug)->delete();
    }

    /**
     * Find record by $attributes = ['column'=>'value']
     * @param array $attributes
     * @return array
     */
    public function firstOrNew(array $attributes, array $values = [])
    {
        if (!is_null($instance = $this->model->where($attributes)->first())) {
            return $instance;
        }
        return $this->model->newModelInstance($attributes + $values);
    }

    /**
     * Find record by $attributes = ['column'=>'value']
     * @param array $attributes
     * @return array or boolean
     */
    public function firstOrCreate(array $attributes, array $values = [])
    {
        if (!is_null($instance = $this->model->where($attributes)->first())) {
            return $instance;
        }

        if ($this->model->newModelInstance($attributes + $values)->save()) {
            return $this->model->latest()->first();
        }
        return false;
    }

    /**
     * Compare array to where table data $attributes = ['column'=>'value']
     * @param array $org_attributes
     * @param array $attributes
     * @param string $rawname
     * @return array
     */
    public function checkByArrayKey(array $org_attributes = [], array $attributes, $rawname = '')
    {
        if ($rawname == '') {
            $db_attributes = $this->model->where($attributes)->get()->keyBy('rawname')->toArray();
        } else {
            $db_attributes = $this->model->where($attributes)->get()->keyBy($rawname)->toArray();
        }
        $org_arr_diff = array_diff_key($org_attributes, $db_attributes);
        $sec_arr_diff = array_diff_key($db_attributes, $org_attributes);
        $org_arr_same = array_intersect_key($org_attributes, $db_attributes);
        $sec_arr_same = array_intersect_key($db_attributes, $org_attributes);

        return $data = [
            'org_arr_diff' => $org_arr_diff,
            'sec_arr_diff' => $sec_arr_diff,
            'org_arr_same' => $org_arr_same,
            'sec_arr_same' => $sec_arr_same,
        ];
    }

    /**
     * Compare array to where table data $attributes = ['column'=>'value']
     * @param array $org_attributes
     * @param array $attributes
     * @param string $with_group
     * @return array
     */
    public function checkByArrayKeyGroupBy(array $org_attributes = [], array $attributes, array $with, $group)
    {
        $db_attributes = $this->model->with($with)->where($attributes)->get()->groupBy($group)->toArray();
        $org_arr_diff  = array_diff_key($org_attributes, $db_attributes);
        $sec_arr_diff  = array_diff_key($db_attributes, $org_attributes);
        $org_arr_same  = array_intersect_key($org_attributes, $db_attributes);
        $sec_arr_same  = array_intersect_key($db_attributes, $org_attributes);

        return $data = [
            'org_arr_diff' => $org_arr_diff,
            'sec_arr_diff' => $sec_arr_diff,
            'org_arr_same' => $org_arr_same,
            'sec_arr_same' => $sec_arr_same,
        ];
    }
}
