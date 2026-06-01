<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::where('user_id', Auth::id())
            ->orderBy('date', 'desc')
            ->paginate(20);
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'note' => 'nullable|string'
        ]);

        Expense::create([
            'user_id' => Auth::id(),
            'date' => $request->date,
            'category' => $request->category,
            'amount' => $request->amount,
            'note' => $request->note
        ]);

        return redirect()->route('expenses.create')
            ->with('success', 'Pengeluaran berhasil ditambahkan!');
    }

    public function edit(Expense $expense)
    {
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }
        return view('expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'date' => 'required|date',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'note' => 'nullable|string'
        ]);

        $expense->update($request->all());

        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil diupdate!');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }

        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Pengeluaran berhasil dihapus!');
    }
}
