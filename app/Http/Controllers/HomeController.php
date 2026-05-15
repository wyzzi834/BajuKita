<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->filled('kategori'), function ($query) use ($request) {
                $query->where('category_id', $request->kategori);
            })
            ->where('status', 'publish')
            ->latest()
            ->paginate(8)
            ->withQueryString();

        return view('home', [
            'products' => $products,
            'categories' => Categorie::orderBy('name')->get(),
            'operator' => config('operator'),
        ]);
    }

    public function checkout(Product $product)
    {
        if ($product->status !== 'publish') {
            return back()->with('error', 'Produk sedang sold out.');
        }

        $invoice = Invoice::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'price' => $product->price,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('home')
            ->with('success', 'Checkout berhasil. Nomor invoice kamu #INV-' . $invoice->id . ' sedang menunggu konfirmasi operator.');
    }

    public function orders()
    {
        return view('orders', [
            'orders' => Invoice::with('product')
                ->where('user_id', auth()->id())
                ->latest()
                ->get(),
            'operator' => config('operator'),
        ]);
    }

    public function destroyOrder(Invoice $invoice)
    {
        abort_if($invoice->user_id !== auth()->id(), 403);

        $invoice->delete();

        return redirect()
            ->route('orders.index')
            ->with('success', 'Pemesanan berhasil dihapus');
    }
}
