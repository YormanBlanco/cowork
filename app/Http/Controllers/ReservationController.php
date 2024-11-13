<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;

class ReservationController extends Controller
{
    public function getReservations(Request $request)
    {
        $reservations = Reservation::with([
            'user',
            'room'
        ])
            ->orderBy('id', 'desc');

        //Si el cliente es quien consulta
        $user = session('user') ? session('user') : null;
        if ($user && $user->role === 'client') {  
            $reservations->where('user_id', $user->id);
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower(trim($request->search));
            $reservations->whereHas('room', function($query) use ($search) {
                $query->whereRaw("LOWER(name) LIKE '%{$search}%'");
            });
        }

        if ($request->has('status') && !empty($request->status)) {
            $status = intval($request->status);
            if ($status === 0 || $status === 1 || $status === 2) {
                $reservations->where('status', $status);
            }
        }  

        $reservations = $reservations->paginate(10);

        return view('admin.reservations', compact('reservations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'reservation_date' => 'required|date|after:now',
        ]);

        $existingReservation = Reservation::where('room_id', $validated['room_id'])
            ->where('reservation_date', $validated['reservation_date'])
            ->first();

        if ($existingReservation) {
            return response()->json(['error' => 'La sala ya está reservada en esa fecha y hora.'], 422);
        }

        $user = session('user') ? session('user') : null;

        $reservation = Reservation::create([
            'user_id' => $user->id,
            'room_id' => $validated['room_id'],
            'reservation_date' => $validated['reservation_date'],
            'status' => Reservation::PENDING
        ]);

        return response()->json($reservation, 200);
    }

    public function updateStatus(Request $request, $id)
    {
        // if (!auth()->user()->isAdmin()) {
        //     return response()->json(['error' => 'No autorizado'], 403);
        // }

        $validated = $request->validate([
            'status' => 'required|in:0,1,2',
        ]);

        $reservation = Reservation::findOrFail($id);
        $reservation->update($validated);

        return response()->json(['message' => 'Reservación actualizada'], 200);
    }
}
