@extends('layouts.template')

@section('contenido')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Salas</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoomModal">Crear sala</button>
    </div>

    <form method="GET" action="{{ route('rooms.index') }}">
        @include('layouts.filters', [
            'search' => true,
            'status' => true,
        ])
    </form>

    <div class="card">
        <div class="table-responsive">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rooms as $room)
                        <tr>
                            <td>{{ $room->name }}</td>
                            <td>{{ $room->description }}</td>
                            <td class="align-middle text-center">
                                <span 
                                    class="badge badge-sm {{ $room->status === 1 ? 'bg-success' : 'bg-secondary' }}"
                                >
                                    {{ 
                                        $room->status === 1 ? 'Activa' : 'Inactiva'
                                    }}
                                </span>                                
                            </td>
                            <td class="align-middle text-center">
                                <!-- Botón Editar -->
                                <button class="btn btn-icon btn-2 btn bg-gradient-info" type="button" title="Editar sala" data-bs-toggle="modal" data-bs-target="#editRoomModal" onclick="openEditModal({{ $room }})">
                                    <span class="btn-inner--icon"><i class="material-symbols-rounded">edit</i></span>
                                </button>

                                <!-- Botón Eliminar -->
                                <button class="btn btn-icon btn-2 btn-primary" type="button" title="Eliminar sala" onclick="confirmDeleteRoom({{ $room->id }}, '{{ $room->name }}')">
                                    <span class="btn-inner--icon"><i class="material-symbols-rounded">delete</i></span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-3">
            {{ $rooms->links('layouts.pagination') }}
        </div>
        
    </div>
</div>

<!-- Modal para crear sala -->
<div class="modal fade" id="createRoomModal" tabindex="-1" aria-labelledby="createRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createRoomModalLabel">Crear sala</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div id="createSuccessAlert" class="alert alert-success text-white d-none" role="alert">
                    <strong>La sala ha sido creada correctamente.</strong>
                </div>
                <div id="createErrorAlert" class="alert alert-danger text-white d-none" role="alert">
                    Ocurrió un problema al crear la sala. Intenta de nuevo.
                </div>

                <!-- Formulario de creación de sala -->
                <form id="createRoomForm">
                    @csrf
                    <div class="mb-3">
                        <label for="createRoomName" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="createRoomName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="createRoomDescription" class="form-label">Descripción</label>
                        <input type="text" class="form-control" id="createRoomDescription" name="description">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear sala</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para editar sala -->
<div class="modal fade" id="editRoomModal" tabindex="-1" aria-labelledby="editRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoomModalLabel">Editar sala</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div id="editSuccessAlert" class="alert alert-success text-white d-none" role="alert">
                    <strong>La sala ha sido actualizada correctamente.</strong> 
                </div>
                <div id="editErrorAlert" class="alert alert-danger text-white d-none" role="alert">
                    Ocurrió un problema al actualizar la sala. Intenta de nuevo.
                </div>

                <!-- Formulario de edición de sala -->
                <form id="editRoomForm">
                    @csrf
                    <input type="hidden" id="editRoomId">
                    <div class="mb-3">
                        <label for="editRoomName" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="editRoomName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editRoomDescription" class="form-label">Descripción</label>
                        <input type="text" class="form-control" id="editRoomDescription" name="description">
                    </div>
                    
                    <!-- Checkbox de estado -->
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="editRoomStatus" name="status" onchange="toggleStatusLabel()">
                        <label class="form-check-label" for="editRoomStatus" id="editRoomStatusLabel">Activo</label>
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


<script>

// Función para abrir el modal de edición con datos precargados
function openEditModal(room) {
    document.getElementById('editRoomId').value = room.id;
    document.getElementById('editRoomName').value = room.name;
    document.getElementById('editRoomDescription').value = room.description;
        
    // Configurar el estado del checkbox y el texto de acuerdo al estado de la sala
    const statusCheckbox = document.getElementById('editRoomStatus');
    const statusLabel = document.getElementById('editRoomStatusLabel');
    statusCheckbox.checked = room.status === 1;
    statusLabel.textContent = room.status === 1 ? 'Activo' : 'Inactivo';
}

// Función para cambiar el texto del estado según el checkbox
function toggleStatusLabel() {
    const statusLabel = document.getElementById('editRoomStatusLabel');
    const statusCheckbox = document.getElementById('editRoomStatus');
    statusLabel.textContent = statusCheckbox.checked ? 'Activo' : 'Inactivo';
}

// Enviar datos del formulario de creación
document.getElementById('createRoomForm').addEventListener('submit', async function(event) {
    event.preventDefault();

    const name = document.getElementById('createRoomName').value;
    const description = document.getElementById('createRoomDescription').value;

    try {
        const response = await fetch(`/admin/rooms/create`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ name, description })
        });

        if (response.status === 201) {
            document.getElementById('createSuccessAlert').classList.remove('d-none');
            setTimeout(() => location.reload(), 1500);
        } else {
            throw new Error('Error al crear la sala');
        }
    } catch (error) {
        document.getElementById('createErrorAlert').classList.remove('d-none');
    }
});

// Enviar datos del formulario de edición
document.getElementById('editRoomForm').addEventListener('submit', async function(event) {
    event.preventDefault();

    const roomId = document.getElementById('editRoomId').value;
    const name = document.getElementById('editRoomName').value;
    const description = document.getElementById('editRoomDescription').value;
    const status = document.getElementById('editRoomStatus').checked ? 1 : 0;

    try {
        const response = await fetch(`/admin/rooms/${roomId}/update`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ name, description, status })
        });

        if (response.status === 200) {
            document.getElementById('editSuccessAlert').classList.remove('d-none');
            setTimeout(() => location.reload(), 1500);
        } else {
            throw new Error('Error al actualizar la sala');
        }
    } catch (error) {
        document.getElementById('editErrorAlert').classList.remove('d-none');
    }
});

// Función para confirmar y eliminar la sala
async function confirmDeleteRoom(roomId, roomName) {
    const confirmation = confirm(`¿Seguro que deseas eliminar la sala "${roomName}"?`);

    if (confirmation) {
        try {
            const response = await fetch(`/admin/rooms/${roomId}/delete`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            if (response.status === 200) {
                // Mostrar alerta de éxito centrada
                const successAlert = document.createElement('div');
                successAlert.classList.add('alert', 'alert-success', 'text-white');
                successAlert.innerHTML = `<strong>La sala "${roomName}" ha sido eliminada correctamente.</strong>`;

                // Añadir estilos para centrar la alerta en la pantalla
                successAlert.style.position = 'fixed';
                successAlert.style.top = '50%';
                successAlert.style.left = '50%';
                successAlert.style.transform = 'translate(-50%, -50%)';
                successAlert.style.zIndex = '1000';
                successAlert.style.padding = '1rem 2rem';
                successAlert.style.borderRadius = '5px';
                successAlert.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.2)';

                document.body.appendChild(successAlert);

                setTimeout(() => {
                    successAlert.remove();
                    location.reload();  // Recargar la página después de la eliminación
                }, 1500);
            } else {
                throw new Error('Error al eliminar la sala');
            }
        } catch (error) {
            // Mostrar alerta de error
            const errorAlert = document.createElement('div');
            errorAlert.classList.add('alert', 'alert-danger', 'text-white');
            errorAlert.innerHTML = `<strong>Ocurrió un problema al eliminar la sala "${roomName}".</strong>`;
            document.body.appendChild(errorAlert);

            setTimeout(() => {
                errorAlert.remove();
            }, 1500);
        }
    }
}
</script>
@endsection