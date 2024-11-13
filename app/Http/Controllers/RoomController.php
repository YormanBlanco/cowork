<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;

class RoomController extends Controller
{
    public function getRooms(Request $request)
    {
        $rooms = Room::orderBy('name', 'asc');

        if ($request->has('search') && !empty($request->search)) {
            $rooms->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if ($request->has('status') && !empty($request->status)) {
            $status = intval($request->status);
            if ($status === 0 || $status === 1 || $status === 2) {
                $rooms->where('status', $status);
            }
        }   
        
        //Si el cliente es quien consulta
        $user = session('user') ? session('user') : null;
        if ($user && $user->role === 'client') {  
            $rooms->where('status', Room::ACTIVE);
            return $rooms->get();
        }

        $rooms = $rooms->paginate(10);

        return view('admin.rooms', compact('rooms'));
    }

    public function store(Request $request)
    {
        //$this->authorize('admin');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $room = Room::create($validated);

        return response()->json($room, 201);
    }

    public function update(Request $request, $id)
    {
        //$this->authorize('admin');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|integer',
        ]);

        $room = Room::findOrFail($id);
        $room->update($validated);

        return response()->json(['message' => 'Sala actualizada'], 200);
    }

    public function destroy(Room $room)
    {
        //$this->authorize('admin');

        $room->delete();

        return response()->json(['message' => 'Sala eliminada'], 200);
    }
}
