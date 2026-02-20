<?php

namespace App\Http\Controllers;
use App\Models\Bin;
use Illuminate\Http\Request;
class DashboardController extends Controller
{
    public function index()
    {
        $bins = Bin::all();
        return view('dashboard', compact('bins'));
    }
    public function addTrash(Request $request)
    {
        $request->validate([
            'bin_id' => 'required|exists:bins,id',
            'weight' => 'required|integer|min:1'
        ]);

        $bin = Bin::find($request->bin_id);
        $weight = $request->weight;
        $points = $weight;
        $bin->current_weight += $weight;
        $bin->total_points += $points;
        $bin->save();

        return redirect()->back()->with('success', "Berhasil membuang sampah! Kamu dapat +{$points} poin.");
    }
    
    // Reset untuk testing
    public function reset()
    {
        Bin::truncate();
        // Buat data dummy awal
        Bin::create(['name' => 'Lobby Utama', 'type' => 'Organik', 'max_capacity' => 5000]);
        Bin::create(['name' => 'Parkiran Area', 'type' => 'Plastik', 'max_capacity' => 5000]);
        Bin::create(['name' => 'Cafetaria', 'type' => 'Kertas', 'max_capacity' => 5000]);
        
        return redirect()->route('dashboard');
    }
}