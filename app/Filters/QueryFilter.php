<?php

namespace App\Filters;

use Illuminate\Http\Request;

class QueryFilter
{
    protected  $validParameters = [];

    protected  $columnMap = [];

    protected  $operatorMap = [];
    /**
     * Validates and transforms the user query into a valid query
     * @return Array
     * */

    public function transformQuery(Request $request) :array
    {
        $eloquentQuery = [];
        foreach ($this->validParameters as $param => $operators) {
            $query = $request->input($param);
            if (! isset($query)) {
                continue;
            }

            $column = $this->columnMap[$param] ?? $param;
            foreach ($operators as $operator) {
                if (isset($query[$operator])) {
                    $eloquentQuery[] = 
                    // [column, value , search operator]
                    [$column, $this->operatorMap[$operator], $query[$operator]];
                }
            }
        }

        return $eloquentQuery;
    }
}
