<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Config;

class StoreCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();

        return $user != null && $user->tokenCan('create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $customerTypes = array_merge(
            Config::get('constants.CUSTOMER_TYPES'),
            array_map('strtolower',Config::get('constants.CUSTOMER_TYPES'))
        );
        return [
            'name' => ['required'],
            'type' => ['required', Rule::in($customerTypes)],
            'email' => ['required', 'email'],
            'address' => ['required'],
            'city' => ['required'],
            'state' => ['required'],
            'postalCode' => ['required'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'postal_code' => $this->postalCode,
        ]);
    }
}
