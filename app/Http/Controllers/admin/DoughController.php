<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DoughList;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\Auth;

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
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        try {
            $today = Carbon::now()->format('Y-m-d');

            $request->validate([
                'dough_litter' => 'required|integer|min:10|max:16',
            ]);

            // check if already inserted today
            $exists = DoughList::where('date', $today)->exists();
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dough litter is already inserted for today!'
                ], 400);
            }

            $dough = new DoughList();
            $dough->dough_litter = $request->dough_litter;

            $this->calculateValues($dough, $request->dough_litter);

            $dough->day = Carbon::now()->format('l');
            $dough->date = $today;
            $dough->save();

            return response()->json([
                'success' => true,
                'message' => 'Dough added successfully.',
                'data' => $dough
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get one dough
     */
    public function getDough($id)
    {
        $dough = DoughList::findOrFail($id);
        return response()->json($dough);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'dough_litter' => 'required|integer|min:10|max:16',
            ]);

            $dough = DoughList::findOrFail($id);
            $dough->dough_litter = $request->dough_litter;

            // calculation logic
            $this->calculateValues($dough, $request->dough_litter);

            $dough->save();

            return response()->json([
                'success' => true,
                'message' => 'Dough updated successfully.',
                'data' => $dough
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        if (!$user || !$user->hasPermissionTo('dough.destroy') || $user->is_superadmin != 1) {
            return response()->json(['success' => false, 'message' => 'Invalid field'], 400);
        }

        try {

            $dough = DoughList::findOrFail($id);
            $dough->delete();

            return response()->json([
                'success' => true,
                'message' => 'Dough deleted successfully.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Common function to calculate values
     */
    private function calculateValues($dough, $liter)
    {
        switch ($liter) {
            case 10:
                $dough->dough_total_weight = 30;
                $dough->dough_num_of_cajas = 6;
                break;
            case 11:
                $dough->dough_total_weight = 33;
                $dough->dough_num_of_cajas = 7;
                break;
            case 12:
                $dough->dough_total_weight = 36;
                $dough->dough_num_of_cajas = 8;
                break;
            case 13:
                $dough->dough_total_weight = 39;
                $dough->dough_num_of_cajas = 9;
                break;
            case 14:
                $dough->dough_total_weight = 42;
                $dough->dough_num_of_cajas = 10;
                break;
            case 15:
                $dough->dough_total_weight = 45;
                $dough->dough_num_of_cajas = 11;
                break;
            case 16:
                $dough->dough_total_weight = 48;
                $dough->dough_num_of_cajas = 12;
                break;
            default:
                $dough->dough_total_weight = 0;
                $dough->dough_num_of_cajas = 0;
        }
    }
}
