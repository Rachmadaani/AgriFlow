<?php

namespace App\Http\Controllers;

use App\Models\Plant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlantController extends Controller
{
    public function index()
    {
        $plants = Plant::where('user_id', Auth::id())->orderBy('name')->get();
        return view('plants.index', compact('plants'));
    }

    public function create()
    {
        return view('plants.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'default_price' => 'nullable|numeric|min:0',
            'planting_date' => 'nullable|date'
        ]);

        Plant::create([
            'name' => $request->name,
            'default_price' => $request->default_price,
            'planting_date' => $request->planting_date,
            'user_id' => Auth::id()
        ]);

        return redirect()->route('plants.index')->with('success', 'Tanaman berhasil ditambahkan!');
    }

    public function edit(Plant $plant)
    {
        if ($plant->user_id !== Auth::id()) {
            abort(403);
        }
        return view('plants.edit', compact('plant'));
    }

    public function update(Request $request, Plant $plant)
    {
        if ($plant->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'default_price' => 'nullable|numeric|min:0',
            'planting_date' => 'nullable|date'
        ]);

        $plant->update($request->all());

        return redirect()->route('plants.index')->with('success', 'Tanaman berhasil diupdate!');
    }

    public function destroy(Plant $plant)
    {
        if ($plant->user_id !== Auth::id()) {
            abort(403);
        }

        $plant->delete();
        return redirect()->route('plants.index')->with('success', 'Tanaman berhasil dihapus!');
    }
}
