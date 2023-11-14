<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('items')->get();
        return response()->json(['invoices' => $invoices], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'invoice_code' => 'required|string|unique:invoices,invoice_code',
            'issue_date' => 'required|date',
            'due_date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'tax_percentage' => 'required|integer',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.item_quantity' => 'required|integer',
            'items.*.item_unit_price' => 'required|numeric',
            'items.*.description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 400);
        }

        // Create the associated invoice items
        foreach ($request->input('items') as $item) {
            $product = Product::find($item['product_id']);
            if ($item['item_quantity'] > $product->quantity) {
                return response()->json(['errors' => 'Product quantity entere more than available', 'product' => $product], 400);
            }
        }

        // Create the invoice
        $invoice = Invoice::create($request->only([
            'user_id', 'invoice_code', 'issue_date', 'due_date', 'customer_id',
            'tax_percentage'
        ]));

        // Create the associated invoice items
        foreach ($request->input('items') as $item) {
            $product = Product::find($item['product_id']);
            $item['invoice_id'] = $invoice->id;
            $item['item_name'] = $product->name;
            $item['item_unit_price'] = $product->unit_price;
            $item['item_cost'] = $product->unit_price * $item['item_quantity'] ;
            $createItem = Item::create($item);
        }

        return response()->json(['message' => 'Invoice created successfuly','invoice' => $invoice->load('items')]);
    }


    public function updateInvoice(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'invoice_code' => 'required|string',
            'issue_date' => 'required|date',
            'due_date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'tax_percentage' => 'required|integer',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.item_quantity' => 'required|integer',
            'items.*.item_unit_price' => 'required|numeric',
            'items.*.description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 400);
        }

        // Create the associated invoice items
        foreach ($request->input('items') as $item) {
            $product = Product::find($item['product_id']);
            if ($item['item_quantity'] > $product->quantity) {
                return response()->json(['errors' => 'Product quantity entere more than available', 'product' => $product], 400);
            }
        }
         // Create the invoice
         $invoice = Invoice::find($id);
         if($invoice){
            $invoice->update($request->only([
                'user_id', 'invoice_code', 'issue_date', 'due_date', 'customer_id',
                'tax_percentage'
            ]));
        }else{
            return response()->json(['errors' => 'Invoice not found'], 404);
        }

        // Create the associated invoice items
        foreach ($request->input('items') as $item) {
            $itemDetails = Item::find($item['id']);
            if($itemDetails){
                $product = Product::find($item['product_id']);
                $itemDetails->update([
                    'item_quantity' => $item['item_quantity'],
                    'item_name' => $product->name,
                    'item_unit_price' => $product->unit_price,
                    'item_cost' => $product->unit_price * $item['item_quantity'],
                    'description' => $item['description'],
                ]);
            }
        }

        return response()->json(['message' => 'Invoice updated successfuly','invoice' => $invoice->load('items')]);
    }

    public function show($id)
    {
        $invoice = Invoice::with('items')->find($id);
        if($invoice){
            return response()->json(['invoice' => $invoice],200);
        }else{
            return response()->json(['error' => 'Invoice not found'],404);
        }

    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->invoiceItems()->delete();
        $invoice->delete();

        return response()->json(['message' => 'Invoice deleted successfully']);
    }

    public function destroyItem($id)
    {
        Item::findOrFail($id);
        return response()->json(['message' => 'Invoice item deleted successfully']);
    }
}
