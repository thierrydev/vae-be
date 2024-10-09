<?php

namespace App\Http\Requests\V1;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Http\FormRequest;

class BulkCreateInvoiceRequest extends FormRequest
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
        $invoiceStatuses = array_merge(
            Config::get('constants.INVOICE_STATUSES'),
            array_map('strtolower',Config::get('constants.INVOICE_STATUSES'))
        );
        $dateFormat = Config::get('constants.PAID_DATE_FORMAT');
        return [
            '*.customerId' => ['required', 'integer'],
            '*.amount' => ['required', 'numeric'],
            '*.status' => ['required', Rule::in($invoiceStatuses)],
            '*.billedDate' => ['required',  $dateFormat],
            '*.paidDate' => [$dateFormat, 'nullable'],
        ];
    }

    protected function prepareForValidation()
    {
        $data = [];

        foreach ($this->toArray() as $obj) {
            $obj['customer_id'] = $obj['customerId'] ?? null;
            $obj['billed_date'] = $obj['billedDate'] ?? null;
            $obj['paid_date'] = $obj['paidDate'] ?? null;

            $data[] = $obj;
        }

        $this->merge($data);
    }
}
