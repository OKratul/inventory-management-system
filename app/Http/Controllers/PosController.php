<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\ProductSku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Gloudemans\Shoppingcart\Facades\Cart;

class PosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $row = (int) request('row', 10);

        if ($row < 1 || $row > 100) {
            abort(400, 'The per-page parameter must be an integer between 1 and 100.');
        }

        $products = Product::with(['category', 'unit'])
                ->filter(request(['search']))
                ->sortable()
                ->paginate($row)
                ->appends(request()->query());

        $customers = Customer::all()->sortBy('name');

        $carts = Cart::content();

        return view('pos.index', [
            'products' => $products,
            'customers' => $customers,
            'carts' => $carts,
        ]);
    }

    /**
     * Handle add product to cart.
     */
    public function addCartItem(Request $request)
    {
        $rules = [
            'id' => 'required|numeric',
            'name' => 'required|string',
            'price' => 'required|numeric',
        ];

        $validatedData = $request->validate($rules);

        Cart::add([
            'id' => $validatedData['id'],
            'name' => $validatedData['name'],
            'qty' => 1,
            'price' => $validatedData['price']
        ]);

        return Redirect::back()->with('success', 'Product has been added to cart!');
    }

    /**
     * Handle update product in cart.
     */
    public function updateCartItem(Request $request, $rowId)
    {
        $rules = [
            'qty' => 'required|numeric',
        ];

        $validatedData = $request->validate($rules);

        Cart::update($rowId, $validatedData['qty']);

        return Redirect::back()->with('success', 'Product has been updated from cart!');
    }

    /**
     * Handle delete product from cart.
     */
    public function deleteCartItem(String $rowId)
    {
        Cart::remove($rowId);

        return Redirect::back()->with('success', 'Product has been deleted from cart!');
    }

    /**
     * Handle create an invoice.
     */
    public function createInvoice(Request $request)
    {
        $rules = [
            'customer_id' => 'required|string'
        ];

        $validatedData = $request->validate($rules);
        $customer = Customer::where('id', $validatedData['customer_id'])->first();
        $carts = Cart::content();

        return view('pos.create', [
            'customer' => $customer,
            'carts' => $carts
        ]);
    }

    public function updateStock($product_id)
    {
        $validatedData = request()->validate([
            'product_sku' => 'required|array', // Make sure 'product_sku' is an array
        ]);

        $products = ProductSku::where('product_id', $product_id)->get();

        foreach ($products as $product) {
            foreach ($validatedData['product_sku'] as $sku) { // Loop through validated product_skus
                if ($sku == $product->product_sku) {
                    $product->update([
                        'stock_status' => 'out_stock',
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Stock Update');
    }


}
