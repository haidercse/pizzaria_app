<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\PizzaDough;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PizzaDoughController extends Controller
{
    public function index()
    {
        $file = PizzaDough::latest()->first();
        return view('backend.pages.dough.pizza_dough', compact('file'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:20000'
        ]);

        // Delete old file
        $old = PizzaDough::latest()->first();
        if ($old) {
            Storage::delete('public/' . $old->file_path);
            $old->delete();
        }

        // Save new file
        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('files', $fileName, 'public');

        PizzaDough::create([
            'file_name' => $fileName,
            'file_path' => $filePath,
        ]);

        return back()->with('success', 'File uploaded successfully!');
    }
}
