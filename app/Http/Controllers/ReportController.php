<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agenda;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Default: Tampilkan data bulan ini
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $type = $request->input('type', 'all'); // all, general, meeting_room

        // Query Dasar
        $query = Agenda::with('user')
            ->whereBetween('start_time', [
                $startDate . ' 00:00:00', 
                $endDate . ' 23:59:59'
            ])
            ->orderBy('start_time', 'asc');

        // Filter Tipe (Jika user memilih filter)
        if ($type !== 'all') {
            $query->where('type', $type);
        }

        $agendas = $query->get();

        return view('reports.index', compact('agendas', 'startDate', 'endDate', 'type'));
    }

    public function print(Request $request)
    {
        // Logika sama persis dengan index, tapi return view cetak
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $type = $request->input('type', 'all');

        $query = Agenda::with('user')
            ->whereBetween('start_time', [
                $startDate . ' 00:00:00', 
                $endDate . ' 23:59:59'
            ])
            ->orderBy('start_time', 'asc');

        if ($type !== 'all') {
            $query->where('type', $type);
        }

        $agendas = $query->get();

        return view('reports.print', compact('agendas', 'startDate', 'endDate', 'type'));
    }
}