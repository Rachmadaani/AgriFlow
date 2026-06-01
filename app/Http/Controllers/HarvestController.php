<?php

namespace App\Http\Controllers;

use App\Models\Harvest;
use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HarvestController extends Controller
{
    public function index()
    {
        $harvests = Harvest::where('user_id', Auth::id())
            ->with('plant')
            ->orderBy('date', 'desc')
            ->paginate(20);
        return view('harvests.index', compact('harvests'));
    }

    public function create()
    {
        $plants = Plant::where('user_id', Auth::id())->orderBy('name')->get();
        return view('harvests.create', compact('plants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plant_id' => 'required|exists:plants,id',
            'date' => 'required|date',
            'weight_kg' => 'required|numeric|min:0.1',
            'price_per_kg' => 'nullable|numeric|min:0'
        ]);

        // Jika harga tidak diisi, pakai default price dari tanaman
        $price = $request->price_per_kg;
        if (!$price) {
            $plant = Plant::find($request->plant_id);
            $price = $plant->default_price;
        }

        Harvest::create([
            'plant_id' => $request->plant_id,
            'user_id' => Auth::id(),
            'date' => $request->date,
            'weight_kg' => $request->weight_kg,
            'price_per_kg' => $price
        ]);

        return redirect()->route('harvests.create')
            ->with('success', 'Panen berhasil ditambahkan!');
    }

    public function edit(Harvest $harvest)
    {
        if ($harvest->user_id !== Auth::id()) {
            abort(403);
        }
        $plants = Plant::where('user_id', Auth::id())->get();
        return view('harvests.edit', compact('harvest', 'plants'));
    }

    public function update(Request $request, Harvest $harvest)
    {
        if ($harvest->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'plant_id' => 'required|exists:plants,id',
            'date' => 'required|date',
            'weight_kg' => 'required|numeric|min:0.1',
            'price_per_kg' => 'required|numeric|min:0'
        ]);

        $harvest->update($request->all());

        return redirect()->route('harvests.index')->with('success', 'Panen berhasil diupdate!');
    }

    public function destroy(Harvest $harvest)
    {
        if ($harvest->user_id !== Auth::id()) {
            abort(403);
        }

        $harvest->delete();
        return redirect()->route('harvests.index')->with('success', 'Data panen berhasil dihapus!');
    }
}
