<?php

namespace App\Filters\V1;

use App\Filters\QueryFilter;

class UsersFilter extends QueryFilter
{

    protected $validParameters = [

        'username' => ['eq'],
        'email' => ['eq'],
        'role' => ['eq'],
        'updatedAt' => ['gt', 'lt'],
        'createdAt' => ['gt', 'lt'],
    ];

    protected $columnMap = [
        'updatedAt' => 'updated_at',
        'createdAt' => 'created_at',
    ];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
    ];
}
