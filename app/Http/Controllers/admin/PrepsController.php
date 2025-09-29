<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Prep;
use Illuminate\Http\Request;

class PrepsController extends Controller
{
    public function index()
    {
        $preps = Prep::all();
        return view('backend.pages.preps.index', compact('preps'));
    }
    public function list()
    {
        $preps = Prep::all();
        return view('backend.pages.preps.list', compact('preps'));
    }

    public function create()
    {
        return view('backend.pages.preps.create');
    }

    public function show($id)
    {
        $prep = Prep::findOrFail($id);
        return response()->json($prep);
    }
    public function store(Request $request)
    {
        $prep = Prep::create($request->all());
        return response()->json(['success' => true, 'prep' => $prep]);
    }

    public function edit($id)
    {
        $prep = Prep::findOrFail($id);
        return response()->json($prep);
    }

    public function update(Request $request, $id)
    {
        $prep = Prep::findOrFail($id);
        $prep->update($request->all());
        return response()->json(['success' => true, 'prep' => $prep]);
    }
    public function destroy($id)
    {
        $prep = Prep::findOrFail($id);
        $prep->delete();
        return response()->json(['success' => true]);
    }
}
