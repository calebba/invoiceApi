<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    // Begin database transaction
    DB::beginTransaction();

    try {
        // Create the associated invoice items
        foreach ($request->input('items') as $item) {
            $product = Product::find($item['product_id']);
            if ($item['item_quantity'] > $product->quantity) {
                // Rollback the transaction and return an error response
                DB::rollBack();
                return response()->json(['errors' => 'Product quantity entered more than available', 'product' => $product], 400);
            }

            // Subtract the item_quantity from the product_quantity
            $product->quantity -= $item['item_quantity'];
            $product->save();
        }

        // Create the invoice
        $invoice = Invoice::create($request->only([
            'user_id', 'invoice_code', 'issue_date', 'due_date', 'customer_id',
            'tax_percentage'
        ]));

        // Create the associated invoice items
        foreach ($request->input('items') as $item) {
            $item['invoice_id'] = $invoice->id;
            $item['item_name'] = $product->name; // Assuming you want to use the last product's name
            $item['item_unit_price'] = $product->unit_price;
            $item['item_cost'] = $product->unit_price * $item['item_quantity'];
            $createItem = Item::create($item);
        }

        // Commit the transaction
        DB::commit();

        return response()->json(['message' => 'Invoice created successfully', 'invoice' => $invoice->load('items')]);
    } catch (\Exception $e) {
        // Something went wrong, rollback the transaction
        DB::rollBack();
        return response()->json(['errors' => 'An error occurred while processing the request. Please try again.'], 500);
    }
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

    // Begin database transaction
    DB::beginTransaction();

    try {
        // Update the invoice
        $invoice = Invoice::find($id);
        if (!$invoice) {
            // Rollback the transaction and return an error response
            DB::rollBack();
            return response()->json(['errors' => 'Invoice not found'], 404);
        }

        $invoice->update($request->only([
            'user_id', 'invoice_code', 'issue_date', 'due_date', 'customer_id',
            'tax_percentage'
        ]));

        // Update or create the associated invoice items
        foreach ($request->input('items') as $item) {
            $itemDetails = Item::find($item['id']);
            $product = Product::find($item['product_id']);

            if ($itemDetails) {
                $oldQuantity = $itemDetails->item_quantity;
                $newQuantity = $item['item_quantity'];

                // Compare the difference in item_quantity
                if($oldQuantity > $newQuantity){
                    $newproductQuantity = $oldQuantity - $newQuantity;
                    $newproductQuantity = $product->quantity + $newproductQuantity;
                }elseif($oldQuantity < $newQuantity){
                    $newproductQuantity = $newQuantity - $oldQuantity;
                    $newproductQuantity = $product->quantity - $newproductQuantity;
                }else{
                    $newproductQuantity = $product->quantity;
                }

                // Update the item details
                $itemDetails->update([
                    'item_quantity' => $newQuantity,
                    'item_name' => $product->name,
                    'item_unit_price' => $product->unit_price,
                    'item_cost' => $product->unit_price * $newQuantity,
                    'description' => $item['description'],
                ]);

                // Update the product quantity based on the difference
                $product->quantity = $newproductQuantity;
                $product->save();

                // Check if the new invoice quantity is less than or equal to the product quantity
                if ($newQuantity > $product->quantity) {
                    // Rollback the transaction and return an error response
                    DB::rollBack();
                    return response()->json(['errors' => 'Product quantity too low'], 400);
                }
            }
        }

        // Commit the transaction
        DB::commit();

        return response()->json(['message' => 'Invoice updated successfully', 'invoice' => $invoice->load('items')]);
    } catch (\Exception $e) {
        // Something went wrong, rollback the transaction
        DB::rollBack();
        return response()->json(['errors' => 'An error occurred while processing the request. Please try again.'], 500);
    }
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
