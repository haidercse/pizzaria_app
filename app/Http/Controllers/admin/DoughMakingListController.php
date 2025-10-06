<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\FlourDistribution;
use App\Models\PhaseTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DoughMakingListController extends Controller
{
    public function YeastSaltList()
    {
        $yeastSalts = DB::table('yeast_salts')->get();
        $flourDistributions = FlourDistribution::all();
        $phaseTables = PhaseTable::all();
        return view('backend.pages.dough_making_list.index', compact('yeastSalts', 'flourDistributions', 'phaseTables'));
    }
    public function updateInline(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->hasPermissionTo('phase.update.inline')) {
            return response()->json(['success' => false, 'message' => 'Invalid field'], 400);
        }
        $phase = PhaseTable::findOrFail($request->id);
        $field = $request->field;
        $value = $request->value;

        // Update field names according to DB schema
        if (in_array($field, [
            'water_l',
            'phase1_tipo00',
            'phase2_tipo00',
            'phase2_tipo1',
            'first_15min',
            'second_8min',
            'third_8min',
            'fourth_8min'
        ])) {
            $phase->$field = $value;
            $phase->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid field'], 400);
    }
}
