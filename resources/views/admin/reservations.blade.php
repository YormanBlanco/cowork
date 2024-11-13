@extends('layouts.template')

@section('contenido')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Reservaciones</h1>
        @if(session('user') && session('user')->role === 'client')
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoomModal">Crear reservación</button>
        @endif
    </div>

    <form method="GET" action="{{ route('reservations.index') }}">
        @include('layouts.filters', [
            'search' => true,
            'status_reservation' => true,
        ])
    </form>

    <div class="card">
        <div class="table-responsive">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr>                       
                        <th>Sala</th>
                        <th>Cliente</th>
                        <th>Fecha/Hora</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservations as $reservation)
                        <tr>
                            <td>{{ $reservation->room->name }}</td>
                            <td>{{ $reservation->user->name }}</td>
                            <td>{{ $reservation->reservation_date_es  }}</td>
                            <td class="align-middle text-center">
                                <span class="badge badge-sm
                                    @if ($reservation->status === \App\Models\Reservation::PENDING) bg-secondary
                                    @elseif ($reservation->status === \App\Models\Reservation::APPROVED) bg-success
                                    @elseif ($reservation->status === \App\Models\Reservation::REJECTED) bg-danger
                                    @endif
                                ">
                                    {{ $reservation->status_text }}
                                </span>
                            </td>
                            <td class="align-middle text-center">
                                <!-- Botón Editar Estado -->
                                @if(session('user') && session('user')->role === 'admin')
                                    <button 
                                        class="btn btn-icon btn-2 btn bg-gradient-info" 
                                        type="button" title="Editar estado de la reservación"
                                        data-bs-toggle="modal" onclick="openEditStatusModal({{ json_encode($reservation) }})"
                                    >
                                        <span class="btn-inner--icon"><i class="material-symbols-rounded">edit</i></span>
                                    </button>
                                @endif
                            </td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-3">
            {{ $reservations->links('layouts.pagination') }}
        </div>

        {{-- Modal para editar status --}}
        <div class="modal fade" id="editReservationStatusModal" tabindex="-1" aria-labelledby="editReservationStatusModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editReservationStatusModalLabel">Editar Estado de la Reservación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div id="editSuccessAlert" class="alert alert-success text-white d-none" role="alert">
                            <strong>El estado de la reservación ha sido actualizado con éxito.</strong> 
                        </div>

                        <form id="editReservationStatusForm">
                            @csrf
                            <input type="hidden" id="reservationId" name="reservation_id">
        
                            <div class="mb-3">
                                <label for="reservationStatus" class="form-label">Estado</label>
                                <select class="form-control" id="reservationStatus" name="status" required>
                                    <option value="0">Pendiente</option>
                                    <option value="1">Aceptada</option>
                                    <option value="2">Rechazada</option>
                                </select>
                            </div>
        
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Guardar cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>  

        <!-- Modal para crear reservacion -->
        <div class="modal fade" id="createRoomModal" tabindex="-1" aria-labelledby="createRoomModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createRoomModalLabel">Crear Sala</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div id="createSuccessAlert" class="alert alert-success text-white d-none" role="alert">
                            <strong>La reservación ha sido creada correctamente.</strong>
                        </div>
                        <div id="createErrorAlert" class="alert alert-danger text-white d-none" role="alert">
                            Ocurrió un problema al crear la reservación. Intenta de nuevo.
                        </div>
                        <div id="createErrorAlertHorario" class="alert alert-danger text-white d-none" role="alert">
                            La sala ya está reservada en esa fecha y hora
                        </div>
        
                        <!-- Formulario de creación de reservacion -->
                        <form id="createReservationForm">
                            @csrf
                            <!-- Select de Salas -->
                            <div class="mb-3">
                                <label for="roomSelect" class="form-label">Selecciona una Sala</label>
                                <select id="roomSelect" class="form-select" name="room_id" required>
                                    <option value="">Selecciona una sala</option>
                                </select>
                            </div>
        
                            <!-- Campo de Fecha y Hora -->
                            <div class="mb-3">
                                <label for="reservationDateTime" class="form-label">Fecha y Hora</label>
                                <input type="datetime-local" class="form-control" id="reservationDateTime" name="datetime" required>
                            </div>
        
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Crear Sala</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        

        
    </div>
</div>

<script>

    function openEditStatusModal(reservation) {
        document.getElementById('reservationId').value = reservation.id;
        document.getElementById('reservationStatus').value = reservation.status;

        // Abrir el modal
        const editModal = new bootstrap.Modal(document.getElementById('editReservationStatusModal'));
        editModal.show();
    }


    // Enviar datos del formulario de edicion
    document.getElementById('editReservationStatusForm').addEventListener('submit', async function(event) {
        event.preventDefault();

        const reservationId = document.getElementById('reservationId').value;
        const status = document.getElementById('reservationStatus').value;

        try {
            const response = await fetch(`/admin/reservations/${reservationId}/status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ status })
            });

            if (response.status === 200) {
                document.getElementById('editSuccessAlert').classList.remove('d-none');
                setTimeout(() => location.reload(), 1500);
            } else {
                throw new Error('Error al actualizar el estado de la reservación');
            }
        } catch (error) {
            alert('Ocurrió un problema al actualizar el estado de la reservación. Intenta de nuevo.');
        }
    });

    // Cargar las salas disponibles cuando se abre el modal
    document.getElementById('createRoomModal').addEventListener('shown.bs.modal', async function () {
        try {
            // Llamada para obtener las salas desde la ruta definida
            const response = await fetch("{{ route('client.rooms') }}");
            const rooms = await response.json();

            const roomSelect = document.getElementById('roomSelect');
            roomSelect.innerHTML = '<option value="">Selecciona una sala</option>';

            rooms.forEach(room => {
                const option = document.createElement('option');
                option.value = room.id;
                option.textContent = room.name;
                roomSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error al cargar las salas:', error);
        }
    });

    // Enviar datos del formulario de creación de sala
    document.getElementById('createReservationForm').addEventListener('submit', async function(event) {
        event.preventDefault();

        const room_id = document.getElementById('roomSelect').value;
        const reservation_date = document.getElementById('reservationDateTime').value;

        try {
            const response = await fetch("{{ route('client.reservations.create') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    room_id,
                    reservation_date,
                })
            });

            console.log(response)

            if (response.status === 200) {
                document.getElementById('createSuccessAlert').classList.remove('d-none');
                setTimeout(() => location.reload(), 1500); // Recargar la página o actualizar la lista
            } else {
                if (response.status === 422){
                    document.getElementById('createErrorAlertHorario').classList.remove('d-none');
                }
                else{
                    throw new Error('Error al crear la resrvación');
                }
            }
        } catch (error) {
            document.getElementById('createErrorAlert').classList.remove('d-none');
        }
    });


</script>


</script>
@endsection