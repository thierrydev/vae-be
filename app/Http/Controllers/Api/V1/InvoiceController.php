<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Invoice;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Filters\V1\InvoicesFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\InvoiceResource;
use App\Http\Resources\V1\InvoiceCollection;
use App\Http\Requests\V1\UpdateInvoiceRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\V1\BulkCreateInvoiceRequest;

class InvoiceController extends Controller
{
    /**
     * Get all Invoices
     * 
     * @return InvoiceCollection
     */
    public function index(Request $request): InvoiceCollection
    {
        $filter = new InvoicesFilter();
        $queryItems = $filter->transformQuery($request);

        if (count($queryItems) == 0) {
            return new InvoiceCollection(Invoice::paginate());
        } else {
            $invoices = Invoice::where($queryItems)->paginate();

            return new InvoiceCollection($invoices->appends($request->query()));
        }
    }


    /**
     * Create an Invoice
     *
     * @param  Request $request
     * @return InvoiceResource
     */
    public function store(Request $request):InvoiceResource
    {
        return new InvoiceResource(Invoice::create($request->all()));
    }

    /**
     * Create many Invoices
     *
     * @param  InvoiceCollection $request
     * @return JsonResponse
     */

    public function bulkCreate(BulkCreateInvoiceRequest $request): JsonResponse
    {
        $bulk = collect($request->all())->map(function ($arr, $key) {
            return Arr::except($arr, ['customerId', 'billedDate', 'paidDate']);
        });

        Invoice::insert($bulk->toArray());
        $statusCode = Response::HTTP_CREATED;
        return new JsonResponse(Response::$statusTexts[$statusCode], $statusCode);
    }

    /**
     * Get an Invoice
     * @param Invoice
     * @return JsonResponse
     */
    public function show(Invoice $invoice): JsonResponse
    {
        $invoice = new InvoiceResource($invoice);
        if (!$invoice) {
            $statusCode = Response::HTTP_NO_CONTENT;
            return new JsonResponse(Response::$statusTexts[$statusCode], $statusCode);
        }
        $statusCode = Response::HTTP_OK;
        return new JsonResponse(Response::$statusTexts[$statusCode], $statusCode);
    }


    /**
     * Update an Invoice
     *
     * @param  UpdateInvoiceRequest  $request
     * @return JsonResponse
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice): JsonResponse
    {
        $invoice = Invoice::find($request->id);
        if (!$invoice) {
            $statusCode = Response::HTTP_NO_CONTENT;
            return new JsonResponse(Response::$statusTexts[$statusCode], $statusCode);
        }
        $invoice->update($request->except('id'));
        $statusCode = Response::HTTP_ACCEPTED;
        return new JsonResponse(Response::$statusTexts[$statusCode], $statusCode);
    }

    /**
     * Delete an Invoice
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(Request $request, Invoice $invoice): JsonResponse
    {
        $request->validate([
            'id' => 'required|exists:invoices'
        ]);
        $invoice = Invoice::where('id', $request->id)->first();

        if (!$invoice) {
            $statusCode = Response::HTTP_NO_CONTENT;
            return new JsonResponse(Response::$statusTexts[$statusCode], $statusCode);
        }

        $invoice->delete();

        $statusCode = Response::HTTP_ACCEPTED;
        return new JsonResponse(Response::$statusTexts[$statusCode], $statusCode);
    }
}
