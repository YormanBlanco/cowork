<div class="filters row">
    <!-- Filtro de búsqueda -->
    @if (isset($search) && $search)
        <div class="col-md-4 mb-4">
            <div class="input-group input-group-dynamic">
                <label class="form-label">Busque por nombre de sala</label>
                <input type="text" class="form-control" name="search" value="{{ request('search') }}">
            </div>
        </div>
    @endif

    <!-- Filtro de estado -->
    @if (isset($status) && $status)
        <div class="col-md-4 mb-4">
            <div class="input-group input-group-static">
                <select class="form-control" name="status" id="status">
                    <option value="">Seleccione un estado</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Activa</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactiva</option>
                </select>
            </div>
        </div>
    @endif

    <!-- Filtro de estado reservacion -->
    @if (isset($status_reservation) && $status_reservation)
        <div class="col-md-4 mb-4">
            <div class="input-group input-group-static">
                <select class="form-control" name="status" id="status">
                    <option value="">Seleccione un estado</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Pendiente</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aceptado</option>
                    <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Rechazado</option>
                </select>
            </div>
        </div>
    @endif

    <!-- Botón de Filtrar (Ocupa 2 columnas) -->
    <div class="col-md-2 mb-2">
        <button type="submit" class="btn btn-primary">Filtrar</button>
    </div>

    <!-- Botón de Limpiar Filtros (Ocupa 2 columnas) -->
    <div class="col-md-2 mb-2">
        <a href="{{ request()->url() }}" class="btn btn-secondary">Limpiar Filtros</a>
    </div>
</div>
