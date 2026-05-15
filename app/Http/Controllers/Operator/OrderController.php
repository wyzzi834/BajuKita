<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Invoice::query()
            ->with(['product', 'user'])
            ->when($request->filled('invoice'), function ($query) use ($request) {
                $invoice = str_replace(['#', 'INV', 'inv', '-'], '', $request->invoice);

                $query->whereRaw('CAST(id AS TEXT) ILIKE ?', ["%{$invoice}%"]);
            })
            ->when($request->filled('cari'), function ($query) use ($request) {
                $keyword = $request->cari;

                $query->where(function ($query) use ($keyword) {
                    $query->whereHas('user', fn ($query) => $query->where('name', 'ILIKE', "%{$keyword}%"))
                        ->orWhereHas('product', fn ($query) => $query->where('name', 'ILIKE', "%{$keyword}%"));
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->latest()
            ->get();

        return view('operator.orders.index', [
            'orders' => $orders,
            'pendingCount' => Invoice::where('status', 'pending')->count(),
            'successCount' => Invoice::where('status', 'success')->count(),
            'failedCount' => Invoice::where('status', 'failed')->count(),
            'expiredCount' => Invoice::where('status', 'expired')->count(),
        ]);
    }

    public function updateStatus(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,success,failed,expired'],
        ]);

        $invoice->update($validated);

        return redirect()
            ->route('operator.order.index')
            ->with('success', 'Status invoice #INV-' . $invoice->id . ' berhasil diperbarui');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()
            ->route('operator.order.index')
            ->with('success', 'Invoice #INV-' . $invoice->id . ' berhasil dihapus');
    }
}
