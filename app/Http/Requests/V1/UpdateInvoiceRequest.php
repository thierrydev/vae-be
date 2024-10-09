<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Config;

class UpdateInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();

        return $user != null && $user->tokenCan('update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $method = $this->method();
        $invoiceStatuses = array_merge(
            Config::get('constants.INVOICE_STATUSES'),
            array_map('strtolower',(Config::get('constants.INVOICE_STATUSES')))
        );
        $dateFormat = Config::get('constants.PAID_DATE_FORMAT');
        if ($method == 'PUT') {
            return [
                '*.customerId' => ['required', 'integer'],
                '*.amount' => ['required', 'numeric'],
                '*.status' => ['required', Rule::in($invoiceStatuses)],
                '*.billedDate' => ['required',  $dateFormat],
                '*.paidDate' => [$dateFormat, 'nullable'],
            ];
        } else {
            return [
                '*.customerId' => ['sometimes', 'required', 'integer'],
                '*.amount' => ['sometimes', 'required', 'numeric'],
                '*.status' => ['sometimes', 'required', Rule::in($invoiceStatuses)],
                '*.billedDate' => ['sometimes', 'required',  $dateFormat],
                '*.paidDate' => ['sometimes', $dateFormat, 'nullable'],
            ];
        }
    }

    protected function prepareForValidation()
    {

        if ($this->customerId) {
            $this->merge([
                'customer_id' => $this->customerId,
            ]);
        }
        if ($this->paidDate) {
            $this->merge([
                'paid_date' => $this->paidDate,
            ]);
        }
        if ($this->billedDate) {
            $this->merge([
                'billed_date' => $this->billedDate,
            ]);
        }
    }
}
