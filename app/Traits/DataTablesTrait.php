<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait DataTablesTrait
{
    protected function getBaseQuery(Request $request)
    {
        return [
            'draw' => $request->get('draw'),
            'start' => $request->get('start'),
            'length' => $request->get('length'),
            'search' => $request->get('search')['value'],
            'order' => $request->get('order'),
            'columns' => $request->get('columns')
        ];
    }

    protected function formatResponse($draw, $query, $results, $transformer)
    {
        $totalRecords = $query->count();
        
        return response()->json([
            'draw' => (int)$draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $results->map($transformer)
        ]);
    }

    protected function applySearch(Builder $query, $search, array $searchableColumns)
    {
        if (!empty($search)) {
            $query->where(function($q) use ($search, $searchableColumns) {
                foreach ($searchableColumns as $column) {
                    if (str_contains($column, '.')) {
                        // Handle relationship searches
                        [$relation, $field] = explode('.', $column);
                        $q->orWhereHas($relation, function($query) use ($field, $search) {
                            $query->where($field, 'like', "%{$search}%");
                        });
                    } else {
                        $q->orWhere($column, 'like', "%{$search}%");
                    }
                }
            });
        }

        return $query;
    }

    protected function applyOrder(Builder $query, $order, $columns, array $orderableColumns, array $relationColumns = [])
    {
        if (!empty($order)) {
            $columnIndex = $order[0]['column'];
            $columnName = $columns[$columnIndex]['data'];
            $direction = $order[0]['dir'];

            if (in_array($columnName, $orderableColumns)) {
                if (isset($relationColumns[$columnName])) {
                    // Handle relationship ordering
                    [$relation, $field] = explode('.', $relationColumns[$columnName]);
                    $query->join($relation, "$relation.id", '=', "{$query->getModel()->getTable()}.{$relation}_id")
                          ->orderBy("$relation.$field", $direction);
                } else {
                    $query->orderBy($columnName, $direction);
                }
            }
        }

        return $query;
    }
}