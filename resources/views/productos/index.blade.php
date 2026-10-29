@extends('layouts.app')

@section('title', 'Listado de productos')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Listado de productos</h1>

        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCrear">
            <i class="bi bi-plus-lg"></i> Nuevo producto
        </button>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    {{-- Formulario GET: los filtros aparecen en la URL --}}
    <form method="GET" action="{{ route('productos.index') }}" class="row g-2 mb-4">

        <div class="col-12 col-md-5">
            <label for="buscar" class="form-label">Buscar por nombre</label>
            <input type="text" id="buscar" name="buscar" value="{{ $buscar }}" class="form-control"
                placeholder="Ej. Mouse">
        </div>

        <div class="col-8 col-md-4">
            <label for="categoria" class="form-label">Categoría</label>
            <select id="categoria" name="categoria" class="form-select">
                <option value="">Todas las categorías</option>

                @foreach ($categorias as $cat)
                    <option value="{{ $cat }}" @selected($cat === $categoria)>
                        {{ $cat }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-4 col-md-3 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i> Filtrar
            </button>

            <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">
                Limpiar
            </a>
        </div>
    </form>

    {{-- Tabla de productos --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h2 class="h5 mb-0">Listado de productos</h2>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th class="text-end">Precio</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($productos as $p)
                        <tr>
                            <td>{{ $p['id'] }}</td>
                            <td>{{ $p['nombre'] }}</td>
                            <td>
                                <span class="badge text-bg-secondary">
                                    {{ $p['categoria'] }}
                                </span>
                            </td>
                            <td class="text-end">
                                ${{ number_format((float) $p['precio'], 2) }}
                            </td>
                            <td class="text-end">
                                <a href="{{ route('productos.edit', $p['id']) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>

                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                    data-bs-target="#modalEliminar" data-id="{{ $p['id'] }}"
                                    data-nombre="{{ $p['nombre'] }}">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No hay productos que coincidan con la búsqueda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal para registrar un producto --}}
    <div class="modal fade" id="modalCrear" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <form method="POST" action="{{ route('productos.store') }}" novalidate>
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Nuevo producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombreNuevo" class="form-label">
                                Nombre
                            </label>
                            <input type="text" id="nombreNuevo" name="nombre" value="{{ old('nombre') }}"
                                class="form-control @error('nombre') is-invalid @enderror">
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="categoriaNueva" class="form-label">
                                Categoría
                            </label>
                            <select id="categoriaNueva" name="categoria"
                                class="form-select @error('categoria') is-invalid @enderror">
                                <option value="">Selecciona una categoría</option>
                                <option value="Tecnología" @selected(old('categoria') === 'Tecnología')>
                                    Tecnología
                                </option>
                                <option value="Muebles" @selected(old('categoria') === 'Muebles')>
                                    Muebles
                                </option>
                            </select>
                            @error('categoria')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="precioNuevo" class="form-label">
                                Precio
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" id="precioNuevo" name="precio"
                                    value="{{ old('precio') }}"
                                    class="form-control @error('precio') is-invalid @enderror">
                                @error('precio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-success">
                            Guardar producto
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- Modal de confirmación para eliminar --}}
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="formEliminar" method="POST" class="modal-content">
                @csrf
                @method('DELETE')

                <div class="modal-header">
                    <h5 class="modal-title">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    ¿Seguro que deseas eliminar
                    <strong id="nombreEliminar"></strong>?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger">
                        Eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('modalEliminar').addEventListener(
            'show.bs.modal',
            function(event) {
                const boton = event.relatedTarget;
                const id = boton.dataset.id;

                document.getElementById('nombreEliminar').textContent =
                    boton.dataset.nombre;

                document.getElementById('formEliminar').action =
                    `{{ url('productos') }}/${id}`;
            }
        );
    </script>

@endsection
