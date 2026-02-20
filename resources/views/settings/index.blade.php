@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="mb-4 fw-bold">Configuración de Usuario</h2>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4"
                        role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4"
                        role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- App Settings -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-palette-fill me-2 text-primary"></i>Apariencia</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-1">Modo Nocturno (Dark Mode)</h6>
                                <p class="text-muted small mb-0">Cambia la interfaz a un esquema de colores oscuros para
                                    descansar la vista y ahorrar energía.</p>
                            </div>
                            <div class="form-check form-switch fs-4 border-0">
                                <input class="form-check-input" type="checkbox" role="switch" id="darkModeSwitch">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Database settings -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-server me-2 text-danger"></i>Base de Datos</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <h6 class="fw-bold mb-2">Respaldar Información (Backup)</h6>
                            <p class="text-muted small">Crea una copia de seguridad segura de todas tus cuentas,
                                transacciones y proyecciones en un archivo `.sql`.</p>
                            <form action="{{ route('settings.backup') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="btn btn-outline-primary shadow-sm rounded-pill px-4 fw-semibold d-flex align-items-center gap-2">
                                    <i class="bi bi-cloud-arrow-down-fill"></i> Generar Respaldo
                                </button>
                            </form>
                        </div>

                        <hr class="text-muted opacity-25">

                        <div class="mt-4">
                            <h6 class="fw-bold mb-2 text-danger">Restaurar Información (Restore)</h6>
                            <p class="text-muted small">Alerta: Esta acción eliminará los datos actuales y restaurará el
                                sistema desde el último archivo `.sql` guardado. Se recomienda generar un respaldo nuevo
                                antes de restaurar.</p>
                            <form action="{{ route('settings.restore') }}" method="POST"
                                onsubmit="return confirm('¿⚠️ ESTÁS TOTALMENTE SEGURO? ⚠️\n\nTodos los datos actuales se borrarán y se reemplazarán con la información del último respaldo. Esta acción NO se puede deshacer.');">
                                @csrf
                                <button type="submit"
                                    class="btn btn-outline-danger shadow-sm rounded-pill px-4 fw-semibold d-flex align-items-center gap-2">
                                    <i class="bi bi-cloud-arrow-up-fill"></i> Restaurar Último Respaldo
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
