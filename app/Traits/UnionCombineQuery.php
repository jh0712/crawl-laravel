<?php

namespace App\Traits;

use Illuminate\Database\Query\Expression;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

trait UnionCombineQuery
{
    public function getCombineConectionQueryData($connection, $allResults)
    {
        // Move bindings to join, due to join will run begore union
        $allResults->getQuery()->bindings['join'] = Arr::flatten($allResults->getQuery()->bindings);
        // after move to join, set union bindings to null
        $allResults->getQuery()->bindings['union'] = [];
        $allResults->getQuery()->bindings['where'] = [];

        return DB::connection($connection)->table(DB::raw("({$allResults->toSql()}) as combined_tables"))
            ->mergeBindings($allResults->getQuery())
            ->select('*')
        ;
    }

    public function getCombineQueryData($allResults, $select = '*')
    {
        // Move bindings to join, due to join will run begore union
        $allResults->getQuery()->bindings['join'] = Arr::flatten($allResults->getQuery()->bindings);
        // after move to join, set union bindings to null
        $allResults->getQuery()->bindings['union'] = [];
        $allResults->getQuery()->bindings['where'] = [];

        return DB::table(DB::raw("({$allResults->toSql()}) as combined_tables"))
            ->mergeBindings($allResults->getQuery())
            ->select($select)
        ;
    }

    public function getAdvanceCombineQueryData($allResults)
    {
        $selects = [];
        // Move bindings to join, due to join will run begore union
        $allResults->getQuery()->bindings['join'] = Arr::flatten($allResults->getQuery()->bindings);
        // after move to join, set union bindings to null
        $allResults->getQuery()->bindings['union'] = [];
        $allResults->getQuery()->bindings['where'] = [];

        $columns = $allResults->getQuery()->columns;
        foreach ($columns as $column) {
            if ($column instanceof Expression) {
                $reflection = new \ReflectionClass($column);
                $property   = $reflection->getProperty('value');
                $property->setAccessible(true);
                $expressionValue = $property->getValue($column);
                $matches         = [];
                if (false !== strpos($expressionValue, ' as ')) {
                    $afterAs = trim(substr($expressionValue, strpos($expressionValue, ' as ') + strlen(' as ')));
                }
                if (false !== strpos($expressionValue, ' AS ')) {
                    $afterAs = trim(substr($expressionValue, strpos($expressionValue, ' AS ') + strlen(' AS ')));
                }
                $selects[] = $afterAs;
            } else {
                if (false !== strpos($column, ' as ') || false !== strpos($column, ' as ')) {
                    if (false !== strpos($column, ' as ')) {
                        $afterAs = trim(substr($column, strpos($column, ' as') + strlen(' as ')));
                    }
                    if (false !== strpos($column, ' AS ')) {
                        $afterAs = trim(substr($column, strpos($column, ' AS ') + strlen(' AS ')));
                    }
                    $selects[] = $afterAs;
                } else {
                    if (false !== strpos($column, '.')) {
                        $dotPosition  = strpos($column, '.');
                        $wordAfterDot = substr($column, $dotPosition + 1);
                        $selects[]    = $wordAfterDot;
                    } else {
                        $selects[] = $column;
                    }
                }
            }
        }

        return DB::table(DB::raw("({$allResults->toSql()}) as combined_tables"))
            ->mergeBindings($allResults->getQuery())
            ->select($selects)
        ;
    }
}
