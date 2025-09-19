<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DoughList;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DoughController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doughs = DoughList::orderBy('id', 'DESC')->get();
        return view('backend.pages.dough.index', compact('doughs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.pages.dough.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $today = Carbon::now();

        $request->validate([
            'dough_litter' => 'required|string|max:255',

        ]);

        $dough = new DoughList();
        $dough->dough_litter = $request->dough_litter;
        if ($request->dough_litter == 10) {
            $dough->dough_total_weight = 30;
            $dough->dough_num_of_cajas = 6;
        } else if ($request->dough_litter == 11) {
            $dough->dough_total_weight = 33;
            $dough->dough_num_of_cajas = 7;
        } else if ($request->dough_litter == 12) {
            $dough->dough_total_weight = 36;
            $dough->dough_num_of_cajas = 8;
        } else if ($request->dough_litter == 13) {
            $dough->dough_total_weight = 39;
            $dough->dough_num_of_cajas = 9;
        } else if ($request->dough_litter == 14) {
            $dough->dough_total_weight = 42;
            $dough->dough_num_of_cajas = 10;
        } else if ($request->dough_litter == 15) {
            $dough->dough_total_weight = 45;
            $dough->dough_num_of_cajas = 11;
        } else if ($request->dough_litter == 16) {
            $dough->dough_total_weight = 48;
            $dough->dough_num_of_cajas = 12;
        }

        $dough->day =  $today->format('l');
        $dough->date =  $today->format('Y-m-d');;
        $dough->save();

        return redirect()->route('dough.index')->with('success', 'Dough added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
         $dough = DoughList::findOrFail($id);
        return view('backend.pages.dough.edit', compact('dough'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'dough_litter' => 'required|string|max:255',

        ]);

        $dough = DoughList::findOrFail($id);
        $dough->dough_litter = $request->dough_litter;
        if ($request->dough_litter == 10) {
            $dough->dough_total_weight = 30;
            $dough->dough_num_of_cajas = 6;
        } else if ($request->dough_litter == 11) {
            $dough->dough_total_weight = 33;
            $dough->dough_num_of_cajas = 7;
        } else if ($request->dough_litter == 12) {
            $dough->dough_total_weight = 36;
            $dough->dough_num_of_cajas = 8;
        } else if ($request->dough_litter == 13) {
            $dough->dough_total_weight = 39;
            $dough->dough_num_of_cajas = 9;
        } else if ($request->dough_litter == 14) {
            $dough->dough_total_weight = 42;
            $dough->dough_num_of_cajas = 10;
        } else if ($request->dough_litter == 15) {
            $dough->dough_total_weight = 45;
            $dough->dough_num_of_cajas = 11;
        } else if ($request->dough_litter == 16) {
            $dough->dough_total_weight = 48;
            $dough->dough_num_of_cajas = 12;
        }

        $dough->save();

        return redirect()->route('dough.index')->with('success', 'Dough updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dough = DoughList::findOrFail($id);
        $dough->delete();

        return redirect()->route('dough.index')->with('success', 'Dough deleted successfully.');
    }
}
