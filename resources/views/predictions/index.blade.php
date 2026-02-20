@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="mb-1 fw-bold">Proyecciones</h2>
                <p class="text-muted mb-0">Pronostica tus pagos de tarjetas de crédito y saldos futuros</p>
            </div>
        </div>

        <div class="row g-5">
            @foreach ($accounts as $account)
                <div class="col-12">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                        <div
                            class="card-header bg-white border-bottom-0 p-4 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        fill="currentColor" class="bi bi-graph-up-arrow" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                            d="M0 0h1v15h15v1H0V0Zm10 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V4.9l-3.613 4.417a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61L13.445 4H10.5a.5.5 0 0 1-.5-.5Z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="mb-0 fw-bold">{{ $account->name }}</h4>
                                    <span class="text-muted small">Pronóstico de Tarjeta de Crédito</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <p class="text-muted small mb-1">Deuda Total Aprox.</p>
                                <h3 class="mb-0 fw-bold text-danger">
                                    L{{ number_format($account->total_hnl_debt, 2) }}
                                </h3>
                                @if ($account->currency === 'USD' || $account->usd_balance > 0)
                                    <p class="small text-muted mb-0"><i class="bi bi-info-circle"></i> Convertido a Lempiras
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead style="background-color: var(--bg-light);">
                                        <tr>
                                            <th class="py-3 px-4 text-secondary fw-semibold border-bottom-0">Mes Objetivo
                                            </th>
                                            <th class="py-3 px-4 text-secondary fw-semibold border-bottom-0 text-end">Cuotas
                                                Fijas (Minicuota)</th>
                                            <th class="py-3 px-4 text-secondary fw-semibold border-bottom-0 text-end">Deuda
                                                Total Proyectada</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($predictions[$account->id] as $prediction)
                                            <tr>
                                                <td class="py-4 px-4">
                                                    <span class="fw-bold text-dark">{{ $prediction['month'] }}</span>
                                                </td>
                                                <td class="py-4 px-4 text-end">
                                                    <span
                                                        class="fw-bold text-danger bg-danger bg-opacity-10 px-3 py-1 rounded-pill d-inline-block text-start"
                                                        style="min-width: 140px;">
                                                        <div class="text-end">
                                                            L{{ number_format($prediction['expected_minimum_payment'], 2) }}
                                                        </div>
                                                    </span>
                                                </td>
                                                <td class="py-4 px-4 text-end">
                                                    <span class="fw-bold text-dark fs-5 d-inline-block text-start"
                                                        style="min-width: 140px;">
                                                        <div class="text-end">
                                                            L{{ number_format($prediction['projected_debt'], 2) }}</div>
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @if ($accounts->isEmpty())
                <div class="col-12 text-center py-5">
                    <div class="p-5 bg-white rounded-4 shadow-sm">
                        <div class="display-1 text-muted mb-4 opacity-50">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor"
                                class="bi bi-graph-up" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M0 0h1v15h15v1H0V0Zm14.817 3.113a.5.5 0 0 1 .07.704l-4.5 5.5a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61 4.15-5.073a.5.5 0 0 1 .704-.07Z" />
                            </svg>
                        </div>
                        <h3 class="fw-bold">No Se Encontraron Tarjetas de Crédito</h3>
                        <p class="text-muted mb-4">Las proyecciones solo están disponibles para tarjetas de crédito. Agrega
                            una tarjeta de crédito para comenzar a pronosticar.</p>
                        <a href="{{ route('accounts.create') }}" class="btn btn-primary px-4 py-2 rounded-pill">Agregar una
                            Tarjeta de Crédito</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
