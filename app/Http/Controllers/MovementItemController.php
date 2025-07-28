<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\MovementItem;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MovementItemController extends Controller
{
    public function in(Request $request) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = MovementItem::with('creator');


            if ($request->has('move')) {
                $query->where('move', $request->move);
            }

            if ($request->has('reference')) {
                $query->where('reference', $request->reference);
            }
            if ($request->has('name')) {
                $query->where('name', $request->name);
            }

            $query->orderBy('created_at', 'desc');




            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('created_by_name', fn($item) => $item->creator->name ?? '-')
                ->addColumn('reference_info', fn($item) => $item->get_reference_info())
                ->addColumn('status_badge', function ($row) {
                    $statusClass = [
                        'draft' => 'bg-gray-100 text-gray-800',
                        'pending' => 'bg-blue-100 text-blue-800',
                        'done' => 'bg-green-100 text-green-800',
                        'cancel' => 'bg-red-100 text-red-800'
                    ];
                    return '<span class="px-2 py-1 text-xs font-semibold rounded-full ' . $statusClass[$row->status] . '">' . ucfirst($row->status) . '</span>';
                })
                ->addColumn('buying_price', fn($item) => 'Rp ' . number_format($item->buying_price, 0, ',', '.'))
                ->addColumn('grand_total', fn($item) => 'Rp ' . number_format($item->grand_total, 0, ',', '.'))
                ->addColumn('created_at', fn($item) => $item->created_at->format('d-m-Y H:i'))
                ->addColumn('action', function ($row) {
                    $btn = '<div class="flex justify-end gap-2">';
                    $btn .= '<a href="' . route('movement-items.show', $row->id) . '" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">';
                    $btn .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>';
                    $btn .= '</a>';
                    $btn .= '<a href="' . route('movement-items.edit', $row->id) . '" class="p-2 text-green-600 hover:bg-green-50 rounded-lg">';
                    $btn .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>';
                    $btn .= '</a>';


                    $btn .= '<button type="button" data-id="' . $row->id . '" data-name="' . $row->id . '"class="delete-jo p-2 text-red-600 hover:bg-green-50 rounded-lg">';
                    $btn .= '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>';
                    $btn .= '</button>';

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['reference_info', 'status_badge', 'action'])

                ->make(true);
        }
        return view('sparepart.move_in.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function supply()
    {

        return view('sparepart.supply.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $movementItem = MovementItem::find($id);
        return view('sparepart.move_check', compact('movementItem'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MovementItem $movementItem)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
            'status' => 'required|in:draft,pending,done,cancel',
        ]);

        // dd($movementItem);

        // Simpan perubahan
        $movementItem->update($validated);

        // Jika status diubah menjadi 'done', update product quantity
        if ($request->status == 'done') {
            $product = Product::find($movementItem->product_id);
            // dd($product);
            if ($movementItem->move == 'in') {
                $product->increment('stok', $movementItem->quantity);
            } elseif ($movementItem->move == 'out') {
                $product->decrement('stok', $movementItem->quantity);
            }
        }

        return redirect()->route('movement-items.index')
            ->with('success', 'Movement item updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $movement = MovementItem::findOrFail($id);
        $movement->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}
