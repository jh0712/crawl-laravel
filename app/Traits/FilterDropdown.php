<?php

namespace App\Traits;

trait FilterDropdown
{
    public function getQueryOrderBy($query)
    {
        $orderData = [
            'order_sequence' => null,
            'order_column'   => null,
        ];
        $orderBy = $query->orders;
        if (isset($orderBy[0])) {
            $orderDetail                 = $orderBy[0];
            $orderData['order_sequence'] = $orderDetail['direction'];
            $orderData['order_column']   = $orderDetail['column'];
        }

        return $orderData;
    }

    public function datatableAdvanceFilterSearch($request, $query)
    {
        // Filter
        $columns = $query->columns;
        foreach ($columns as $column) {
            if ($request->{$column}) {
                $searchColumn = $request->{$column};
                if (is_string($searchColumn)) {
                    $searchColumn = [$searchColumn];
                }
                $query = $query->whereIn($column, $searchColumn);
            }
        }
        // Search
        if ($request->search) {
            $searchItem = $request->search;
            $query      = $query->where(function ($query) use ($searchItem, $columns) {
                foreach ($columns as $index => $column) {
                    if (0 === $index) {
                        $query->where($column, 'like', '%'.$searchItem.'%');
                    } else {
                        $query->orWhere($column, 'like', '%'.$searchItem.'%');
                    }
                }
            });
        }
        // order
        $orderSequence = $request->order_sequence ? $request->order_sequence : 'desc';
        $orderColumn   = $this->orderColumn($columns, $request->order_column);

        return $query->orderBy($orderColumn, $orderSequence);
    }

    public function datatableFilterSearch($request, $allResults, $emptyData)
    {
        // Index view should load by template.default.datatables-toolbar-query, template.default.datatables-filter-query views.

        // Use function getFilterSelectDropDown() on function index()

        // Use raw on query column on where, or it will where like on searching

        $filter_data = [];
        $has_filter  = false;

        // Unset null value of key
        if (isset($request['d_filter_search'])) {
            $d_data = $request['d_filter_search'];
            foreach ($d_data as $r_key => $r_val) {
                if (is_null($r_val)) {
                    unset($d_data[$r_key]);
                } else {
                    $has_filter = true;
                }
            }
            if ($has_filter) {
                $filter_data = $d_data;
            }
        }

        // Skip on deault loading
        if ($has_filter) {
            $app_arr               = $allResults->select('*')->distinct()->get();
            $get_query_column_arr  = $emptyData->getQuery()->columns;
            $query_column_name_arr = [];
            foreach ($get_query_column_arr as $expression) {
                $query_column_name = \Illuminate\Support\Str::after($expression->getValue(), 'as ');
                if ($query_column_name != 'action') {
                    $query_column_name_arr[$query_column_name] = $query_column_name;
                }
            }

            $raw_trans_arr = [];
            foreach ($query_column_name_arr as $q_val) {
                if (\Illuminate\Support\Str::contains($q_val, 'raw')) {
                    $q_trans_arr             = explode('_', $q_val);
                    $q_trans_arr             = array_diff($q_trans_arr, ['raw']);
                    $q_trans                 = implode('_', $q_trans_arr);
                    $get_arr                 = $app_arr->groupBy($q_trans)->keys();
                    $raw_trans_arr[$q_trans] = $this->dropdownQueryNameTransArray($get_arr);
                }
            }

            // Reset search order to last
            if (!empty($filter_data['search'])) {
                $temp_data = $filter_data['search'];
                unset($filter_data['search']);
                $filter_data = array_merge($filter_data, ['search' => $temp_data]);
            }
            // dd(get_defined_vars());
            // dd($filter_data, $query_column_name_arr, $raw_trans_arr);
            $filter_where_arr = [];
            foreach ($filter_data as $name => $trans) {
                // Check column is exist on query
                if (isset($query_column_name_arr[$name])) {
                    if (isset($raw_trans_arr[$name])) {
                        if (isset($raw_trans_arr[$name][$trans])) {
                            $allResults->whereIn($name, $raw_trans_arr[$name][$trans]);
                            $filter_where_arr[$name] = $trans;
                        } else {
                            $allResults->where($name, 'LIKE', "%{$trans}%");
                        }
                    } else {
                        $allResults->where($name, 'LIKE', "%{$trans}%");
                    }
                }

                // Check search is value
                if ($name == 'search' && !empty($filter_data['search'])) {
                    // Get searching string on array
                    $searching_string_arr = [];
                    foreach ($raw_trans_arr as $raw_key => $raw_arr) {
                        $searching_string_arr[$raw_key] = array_keys($raw_arr);
                        foreach ($searching_string_arr[$raw_key] as $s_key => $s_val) {
                            if (false === strpos(strtolower($s_val), strtolower($trans))) {
                                unset($searching_string_arr[$raw_key][$s_key]);
                            }
                        }

                        $searching_string_arr[$raw_key] = array_values($searching_string_arr[$raw_key]);
                        if (empty($searching_string_arr[$raw_key])) {
                            unset($searching_string_arr[$raw_key]);
                        }
                    }

                    // As new SQL table where on filter
                    if (!empty($filter_where_arr)) {
                        $binding_arr = [];
                        foreach ($allResults->bindings as $bindings_val) {
                            $binding_arr = array_merge($binding_arr, $bindings_val);
                        }
                        $allResults = \Illuminate\Support\Facades\DB::table(\Illuminate\Support\Facades\DB::raw("({$allResults->toSql()}) as combined_filter_search_table"))
                            ->select('*')
                        ;
                        $allResults->bindings['join'] = $binding_arr;

                        $combined_filter_where_arr = [];
                        foreach ($filter_where_arr as $filter_key => $filter_val) {
                            $s_where_column = 'combined_filter_search_table.'.$filter_key;
                            $s_sel_column   = 's_'.$filter_key;
                            $allResults->addSelect(\Illuminate\Support\Facades\DB::raw('concat('.$filter_key.') as '.$s_sel_column));

                            // Match string is contain on filter
                            if (false !== strpos(strtolower($filter_val), strtolower($filter_data[$name]))) {
                                $allResults->whereIn($s_where_column, $raw_trans_arr[$filter_key][$filter_val]);
                            } else {
                                $combined_filter_where_arr = [$s_where_column => "{$filter_data[$name]}"];
                            }
                        }

                        // Searching string is not found from filter get where
                        if (count($combined_filter_where_arr) == count($filter_where_arr)) {
                            foreach ($combined_filter_where_arr as $c_f_key => $c_f_val) {
                                $allResults->where($c_f_key, $c_f_val);
                            }
                        }
                        // dd(get_defined_vars(), $allResults->get(), strtolower($filter_val), strtolower($filter_data[$name]));
                    }

                    foreach ($query_column_name_arr as $q_column_name) {
                        // Skip where on exsit filter
                        if (!isset($filter_where_arr[$q_column_name])) {
                            if (isset($searching_string_arr[$q_column_name])) {
                                $where_in_arr = [];
                                foreach ($searching_string_arr[$q_column_name] as $s_s_val) {
                                    $where_in_arr = array_merge($where_in_arr, $raw_trans_arr[$q_column_name][$s_s_val]);
                                }
                                $allResults->orWhereIn($q_column_name, $where_in_arr);
                            } else {
                                $allResults->orWhere($q_column_name, 'LIKE', "%{$trans}%");
                            }
                        }
                    }
                }
                if ($name == 'date_range' && !empty($filter_data['date_range'])) {
                    $date_arr   = explode(' - ', $filter_data['date_range']);
                    $start_date = $date_arr[0].' 00:00:00';
                    $end_date   = $date_arr[1].' 23:59:59';
                    $allResults->whereBetween('created_at', [$start_date, $end_date]);
                }
            }
        }
        // dd(get_defined_vars(), $allResults->get());

        return $allResults;
    }

    public function dropdownQueryNameTransArray($results)
    {
        $array = [];
        foreach ($results as $result) {
            $translated           = __($result);
            $array[$translated][] = $result;
        }

        return $array;
    }

    public function dropdownConvertTranslation($results, $key)
    {
        $array = [];
        foreach ($results as $result) {
            if ($result->{$key}) {
                $translated         = __($result->{$key});
                $array[$translated] = $translated;
            }
        }

        return $array;
    }

    public function getFilterSelectDropDown($filterData)
    {
        $filterDataFirst = $filterData->first();
        $filterSel       = [];
        if ($filterDataFirst) {
            foreach ($filterDataFirst as $col_name => $trans_name) {
                $filterSel[$col_name] = $this->dropdownConvertTranslation($filterData, $col_name);
            }
        }

        return $filterSel;
    }

    private function orderColumn($column, $request_column)
    {
        if ($request_column) {
            foreach ($column as $col) {
                if ($request_column == $col) {
                    return $request_column;
                }
            }
        }
        $possibleColumns = ['created_at', 'id', 'updated_at'];
        foreach ($possibleColumns as $possibleColumn) {
            foreach ($column as $col) {
                if ($possibleColumn == $col) {
                    return $possibleColumn;
                }
            }
        }

        return $column[0];
    }
}
