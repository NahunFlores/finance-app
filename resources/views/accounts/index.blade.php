@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="mb-1">Mis Cuentas</h2>
                <p class="text-muted mb-0">Administra tus finanzas y tarjetas de crédito</p>
            </div>
            <a href="{{ route('accounts.create') }}" class="btn btn-primary shadow-sm d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg"
                    viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z" />
                </svg>
                Agregar Nueva Cuenta
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            @foreach ($accounts as $account)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm position-relative overflow-hidden">
                        <!-- Accent top line -->
                        <div class="position-absolute top-0 start-0 w-100"
                            style="height: 4px; background: {{ $account->type == 'credit' ? 'var(--primary-blue)' : 'var(--secondary-blue)' }};">
                        </div>

                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title fw-bold mb-0">{{ $account->name }}</h5>
                                <span
                                    class="badge {{ $account->type == 'credit' ? 'bg-danger bg-opacity-10 text-danger' : 'bg-primary bg-opacity-10 text-primary' }} rounded-pill px-3 py-2 fw-semibold text-uppercase"
                                    style="font-size: 0.75rem;">
                                    {{ $account->type }}
                                </span>
                            </div>

                            <div class="mt-4 mb-4">
                                @if ($account->type == 'credit')
                                    <p class="text-muted small mb-1">Deuda Total Aprox.</p>
                                    <h3 class="mb-2 fw-bold text-danger">
                                        L{{ number_format($account->total_hnl_debt, 2) }}
                                    </h3>
                                    <div class="d-flex justify-content-between text-muted small mt-2 bg-light p-2 rounded">
                                        <span>HNL: <strong>L{{ number_format($account->balance, 2) }}</strong></span>
                                        <span>USD: <strong>${{ number_format($account->usd_balance, 2) }}</strong></span>
                                    </div>
                                @else
                                    <p class="text-muted small mb-1">Saldo Disponible</p>
                                    <h3 class="mb-0 fw-bold text-success">
                                        {{ $account->currency === 'USD' ? '$' : 'L' }}{{ number_format($account->balance, 2) }}
                                        {{ $account->currency }}
                                    </h3>
                                    @if ($account->currency === 'USD')
                                        <p class="small text-muted mb-0 mt-1"><i class="bi bi-arrow-return-right"></i> ~
                                            L{{ number_format($account->hnl_balance, 2) }} HNL</p>
                                    @endif
                                @endif
                            </div>

                            @if ($account->type == 'credit' && $account->credit_limit)
                                <div class="p-3 bg-light rounded-3 mt-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="small text-muted">Límite de Crédito</span>
                                        <span
                                            class="small fw-semibold text-dark">{{ $account->currency === 'USD' ? '$' : 'L' }}{{ number_format($account->credit_limit, 2) }}</span>
                                    </div>
                                    @php
                                        $usedLimit = $account->credit_limit - $account->available_credit;
                                        $percentage =
                                            $account->credit_limit > 0
                                                ? ($usedLimit / $account->credit_limit) * 100
                                                : 0;
                                        $percentage = min(100, max(0, $percentage));
                                    @endphp
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar {{ $percentage > 85 ? 'bg-danger' : 'bg-primary' }}"
                                            role="progressbar" style="width: {{ $percentage }}%"
                                            aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-2 mb-2">
                                        <span class="small text-muted">Disponible</span>
                                        <span
                                            class="small fw-bold text-success">{{ $account->currency === 'USD' ? '$' : 'L' }}{{ number_format($account->available_credit, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mt-3">
                                        <div class="text-center">
                                            <div class="small text-muted fw-semibold">Día de Corte</div>
                                            <div class="fw-bold">{{ $account->cut_off_day ?: '-' }}</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="small text-muted fw-semibold">Día de Pago</div>
                                            <div class="fw-bold text-danger">{{ $account->payment_due_day ?: '-' }}</div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="p-3 bg-light rounded-3 mt-3 d-flex align-items-center justify-content-center"
                                    style="min-height: 98px;">
                                    <span class="text-muted small"><i class="bi bi-wallet2 me-2"></i> Cuenta
                                        {{ ucfirst($account->type) == 'Credit' ? 'de Crédito' : (ucfirst($account->type) == 'Debit' ? 'de Débito' : 'de Efectivo') }}
                                        Estándar</span>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer bg-transparent border-0 p-4 pt-0 d-flex gap-2">
                            <a href="{{ route('accounts.show', $account) }}"
                                class="btn btn-outline-primary w-100 fw-semibold rounded-3 py-2">Administrar Cuenta</a>
                            <a href="{{ route('accounts.edit', $account) }}"
                                class="btn btn-outline-secondary px-3 py-2 rounded-3" title="Editar Cuenta"><i
                                    class="bi bi-pencil-square"></i></a>
                            <form action="{{ route('accounts.destroy', $account) }}" method="POST" class="m-0"
                                onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta cuenta? Todas sus transacciones también serán eliminadas de forma permanente.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger px-3 py-2 rounded-3"
                                    title="Eliminar Cuenta"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            @if ($accounts->isEmpty())
                <div class="col-12 text-center py-5">
                    <div class="p-5 bg-white rounded-4 shadow-sm">
                        <div class="display-1 text-muted mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor"
                                class="bi bi-wallet" viewBox="0 0 16 16">
                                <path
                                    d="M0 3a2 2 0 0 1 2-2h13.5a.5.5 0 0 1 0 1H15v2a1 1 0 0 1 1 1v8.5a1.5 1.5 0 0 1-1.5 1.5h-12A2.5 2.5 0 0 1 0 12.5V3zm1 1.732V12.5A1.5 1.5 0 0 0 2.5 14h12a.5.5 0 0 0 .5-.5V5H2a1.99 1.99 0 0 1-1-.268zM1 3a1 1 0 0 0 1 1h12V2H2a1 1 0 0 0-1 1z" />
                            </svg>
                        </div>
                        <h3 class="fw-bold">No se encontraron cuentas</h3>
                        <p class="text-muted mb-4">Comienza a administrar tus finanzas agregando tu primera cuenta o tarjeta
                            de crédito.</p>
                        <a href="{{ route('accounts.create') }}" class="btn btn-primary px-4 py-2 rounded-pill">Agrega Tu
                            Primera Cuenta</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
