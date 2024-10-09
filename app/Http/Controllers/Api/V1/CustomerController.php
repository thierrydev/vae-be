<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Filters\V1\CustomersFilter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\V1\CustomerResource;
use App\Http\Resources\V1\CustomerCollection;
use App\Http\Requests\V1\StoreCustomerRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\V1\UpdateCustomerRequest;

class CustomerController extends Controller
{
    /**
     * Get all Customers
     * @param bool includeInvoices
     * @return CustomerCollection
     */
    public function index(Request $request) :CustomerCollection
    {
        $filter = new CustomersFilter();
        $filterItems = $filter->transformQuery($request);

        $includeInvoices = $request->query('includeInvoices');

        $customers = Customer::where($filterItems);

        if ($includeInvoices) {
            $customers = $customers->with('invoices');
        }

        return new CustomerCollection($customers->paginate()->appends($request->query()));
    }

    /**
     * Create Customer
     *
     * @param  StoreCustomerRequest $request
     * @return CustomerResource
     */
    public function store(StoreCustomerRequest $request) :CustomerResource
    {
        return new CustomerResource(Customer::create($request->all()));
    }

    /**
     * Get Customer
     * @param bool includeInvoices
     * @return CustomerCollection
     */
    public function show(Customer $customer): CustomerResource
    {
        $includeInvoices = request()->query('includeInvoices');

        if ($includeInvoices) {
            return new CustomerResource($customer->loadMissing('invoices'));
        }
        return new CustomerResource($customer);
    }

    /**
     * Update Customer
     *
     * @param  UpdateCustomerRequest  $request
     * @return JsonResponse
     */
    public function update(UpdateCustomerRequest $request, Customer $customer):JsonResponse
    {
        $customer = Customer::find($request->id);

        if (!$customer) {
            $statusCode = Response::HTTP_NO_CONTENT;
            return new JsonResponse(Response::$statusTexts[$statusCode], $statusCode);
        }

        $customer->update($request->all());
        $statusCode = Response::HTTP_ACCEPTED;
        return new JsonResponse(Response::$statusTexts[$statusCode], $statusCode);
    }

    /**
     * Delete Customer
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request, Customer $customer): JsonResponse
    {

        $request->validate([
            'id' => 'required|exists:customers'
        ]);
        $customer = Customer::where('id', $request->id)->first();

        if (!$customer) {
            $statusCode = Response::HTTP_NO_CONTENT;
            return new JsonResponse(Response::$statusTexts[$statusCode], $statusCode);
        }

        $customer->delete();

        $statusCode = Response::HTTP_ACCEPTED;
        return new JsonResponse(Response::$statusTexts[$statusCode], $statusCode);
    }
}
