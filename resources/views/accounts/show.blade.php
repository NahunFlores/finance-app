@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div class="d-flex align-items-center">
                <a href="{{ route('accounts.index') }}"
                    class="btn btn-light rounded-circle p-2 me-3 shadow-sm border-0 d-flex align-items-center justify-content-center"
                    style="width: 40px; height: 40px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                    </svg>
                </a>
                <div>
                    <h2 class="mb-0 fw-bold">{{ $account->name }}</h2>
                    <span
                        class="badge {{ $account->type == 'credit' ? 'bg-danger' : 'bg-primary' }} bg-opacity-10 text-{{ $account->type == 'credit' ? 'danger' : 'primary' }} mt-1 px-2 py-1 rounded-3">
                        Cuenta
                        {{ ucfirst($account->type) == 'Credit' ? 'de Crédito' : (ucfirst($account->type) == 'Debit' ? 'de Débito' : 'de Efectivo') }}
                    </span>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('accounts.edit', $account) }}"
                    class="btn btn-outline-primary shadow-sm rounded-pill px-4 fw-semibold d-flex align-items-center gap-2">
                    <i class="bi bi-pencil-square"></i> Editar Cuenta
                </a>
                <form action="{{ route('accounts.destroy', $account) }}" method="POST"
                    onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta cuenta? Todas sus transacciones también serán eliminadas de forma permanente.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="btn btn-outline-danger shadow-sm rounded-pill px-4 fw-semibold d-flex align-items-center gap-2">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4 mb-5">
            <!-- Balance Card -->
            <div class="col-md-5">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden"
                    style="background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue)); color: white;">
                    <div class="card-body p-4 p-md-5 d-flex flex-column justify-content-center position-relative">
                        <div class="position-absolute end-0 bottom-0 opacity-10" style="right: -20px; bottom: -20px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="150" height="150" fill="currentColor"
                                class="bi bi-wallet2" viewBox="0 0 16 16">
                                <path
                                    d="M12.136.326A1.5 1.5 0 0 1 14 1.78V3h.5A1.5 1.5 0 0 1 16 4.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 13.5v-9a1.5 1.5 0 0 1 1.432-1.499L12.136.326zM5.562 3H13V1.78a.5.5 0 0 0-.621-.484L5.562 3zM1.5 4a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-13z" />
                            </svg>
                        </div>

                        @if ($account->type == 'credit')
                            @php
                                $limit_hnl = 0;
                                $limit_usd = 0;
                                $avail_hnl = 0;
                                $avail_usd = 0;

                                if ($account->credit_limit) {
                                    if ($account->currency === 'HNL') {
                                        $limit_hnl = $account->credit_limit;
                                        $limit_usd = $rate > 0 ? $account->credit_limit / $rate : 0;
                                        $avail_hnl = $account->available_credit;
                                        $avail_usd = $rate > 0 ? $account->available_credit / $rate : 0;
                                    } else {
                                        $limit_usd = $account->credit_limit;
                                        $limit_hnl = $account->credit_limit * $rate;
                                        $avail_usd = $account->available_credit;
                                        $avail_hnl = $account->available_credit * $rate;
                                    }
                                }
                            @endphp

                            <h6 class="text-white fw-bold mb-3 text-uppercase tracking-wider text-center"
                                style="letter-spacing: 1px;">
                                Estado de Cuenta
                            </h6>

                            <div class="bg-white bg-opacity-10 rounded-4 p-3 shadow-sm position-relative z-1">
                                <div class="row text-end border-bottom border-white border-opacity-25 pb-2 mb-2">
                                    <div class="col-4"></div>
                                    <div class="col-4 ps-0 pe-2 fw-bold text-warning small" style="font-size: 0.75rem;">
                                        LEMPIRAS</div>
                                    <div class="col-4 ps-0 pe-2 fw-bold text-warning small" style="font-size: 0.75rem;">
                                        DÓLARES</div>
                                </div>

                                @if ($account->credit_limit)
                                    <div class="row align-items-center mb-2">
                                        <div class="col-4 text-white-50 small pe-0">Límite de Crédito:</div>
                                        <div class="col-4 text-end fw-semibold pe-2 small">
                                            {{ number_format($limit_hnl, 2) }}</div>
                                        <div class="col-4 text-end fw-semibold pe-2 small">
                                            {{ number_format($limit_usd, 2) }}</div>
                                    </div>
                                    <div class="row align-items-center mb-2">
                                        <div class="col-4 text-white-50 small pe-0">Crédito Disp.:</div>
                                        <div class="col-4 text-end fw-bold pe-2 small">{{ number_format($avail_hnl, 2) }}
                                        </div>
                                        <div class="col-4 text-end fw-bold pe-2 small">{{ number_format($avail_usd, 2) }}
                                        </div>
                                    </div>
                                @endif

                                <div class="row align-items-center mt-3 pt-2 border-top border-white border-opacity-25">
                                    <div class="col-4 text-white fw-bold small pe-0">Saldo al Corte:</div>
                                    <div
                                        class="col-4 text-end fw-bold {{ $account->balance > 0 ? 'text-danger' : 'text-white' }} pe-2">
                                        {{ number_format($account->balance, 2) }}</div>
                                    <div
                                        class="col-4 text-end fw-bold {{ $account->usd_balance > 0 ? 'text-danger' : 'text-white' }} pe-2">
                                        {{ number_format($account->usd_balance, 2) }}</div>
                                </div>
                            </div>

                            <p class="text-center text-white-50 small mt-3 mb-0 position-relative z-1">Deuda Unificada
                                Aprox: L{{ number_format($account->total_hnl_debt, 2) }}</p>
                        @else
                            <h6 class="text-white-50 fw-semibold mb-2 text-uppercase tracking-wider">
                                Saldo Disponible
                            </h6>
                            <h1 class="display-4 fw-bold mb-0">
                                L{{ number_format($account->balance, 2) }}
                            </h1>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Add Transaction Form Card -->
            <div class="col-md-7">
                <div class="card h-100 border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                        <h5 class="fw-bold text-dark mb-0">Registrar Transacción</h5>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('transactions.store', $account) }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold text-muted">Tipo</label>
                                    <select name="type" class="form-select bg-light border-0" required>
                                        @if ($account->type == 'credit')
                                            <option value="expense">💳 Compra / Gasto</option>
                                            <option value="withdrawal">💵 Retiro de Efectivo (-)</option>
                                            <option value="payment">📈 Pago de Tarjeta (+)</option>
                                        @else
                                            <option value="income">📈 Ingreso (+)</option>
                                            <option value="expense">📉 Gasto (-)</option>
                                            <option value="withdrawal">💵 Retiro de Efectivo (-)</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold text-muted">Monto</label>
                                    <div class="input-group">
                                        <select name="currency" class="form-select bg-light border-0 text-muted"
                                            style="max-width: 80px;">
                                            <option value="{{ $account->currency }}" selected>
                                                {{ $account->currency === 'USD' ? '$' : 'L' }}</option>
                                            <option value="{{ $account->currency === 'USD' ? 'HNL' : 'USD' }}">
                                                {{ $account->currency === 'USD' ? 'L' : '$' }}</option>
                                        </select>
                                        <input type="number" step="0.01" name="amount"
                                            class="form-control bg-light border-0 px-2" placeholder="0.00" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold text-muted">Fecha</label>
                                    <input type="date" name="transaction_date" class="form-control bg-light border-0"
                                        value="{{ date('Y-m-d') }}" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label small fw-semibold text-muted">Comisión (Opcional)
                                        ({{ $account->currency }})</label>
                                    <div class="input-group">
                                        <span
                                            class="input-group-text bg-light border-0 text-muted">{{ $account->currency === 'USD' ? '$' : 'L' }}</span>
                                        <input type="number" step="0.01" name="fee_amount"
                                            class="form-control bg-light border-0 px-2" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label small fw-semibold text-muted">Descripción</label>
                                    <input type="text" name="description" class="form-control bg-light border-0"
                                        placeholder="¿Para qué fue esto?">
                                </div>

                                <div class="col-12 mt-4 text-end">
                                    <button type="submit"
                                        class="btn btn-primary px-4 py-2 fw-semibold rounded-pill">Guardar
                                        Transacción</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions Table -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 fw-bold">Transacciones Recientes</h4>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4 text-muted fw-semibold border-bottom-0">Fecha</th>
                            <th class="py-3 px-4 text-muted fw-semibold border-bottom-0">Descripción</th>
                            <th class="py-3 px-4 text-muted fw-semibold border-bottom-0">Tipo</th>
                            <th class="py-3 px-4 text-muted fw-semibold border-bottom-0 text-end">Monto</th>
                            <th class="py-3 px-4 text-muted fw-semibold border-bottom-0 text-end">Comisión</th>
                            <th class="py-3 px-4 text-muted fw-semibold border-bottom-0 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $tx)
                            <tr>
                                <td class="py-3 px-4 text-secondary">{{ $tx->transaction_date->format('M d, Y') }}</td>
                                <td class="py-3 px-4 fw-medium text-dark">{{ $tx->description ?? 'Desconocido' }}</td>
                                <td class="py-3 px-4">
                                    @php
                                        $badgeColor = match ($tx->type) {
                                            'income', 'payment' => 'success',
                                            'expense', 'withdrawal' => 'danger',
                                            default => 'secondary',
                                        };
                                    @endphp
                                    <span
                                        class="badge bg-{{ $badgeColor }} bg-opacity-10 text-{{ $badgeColor }} rounded-pill px-3 py-1 fw-semibold text-uppercase"
                                        style="font-size: 0.7rem;">
                                        {{ $tx->type }}
                                    </span>
                                </td>
                                <td
                                    class="py-3 px-4 text-end fw-bold text-{{ in_array($tx->type, ['expense', 'withdrawal']) ? 'danger' : 'success' }}">
                                    @if ($tx->original_currency && $tx->original_currency !== $account->currency)
                                        <div class="small text-muted fw-normal mb-1">
                                            {{ in_array($tx->type, ['expense', 'withdrawal']) ? '-' : '+' }}{{ $tx->original_currency === 'USD' ? '$' : 'L' }}{{ number_format($tx->original_amount, 2) }}
                                            {{ $tx->original_currency }}
                                        </div>
                                    @endif
                                    {{ in_array($tx->type, ['expense', 'withdrawal']) ? '-' : '+' }}{{ $account->currency === 'USD' ? '$' : 'L' }}{{ number_format($tx->amount, 2) }}
                                </td>
                                <td class="py-3 px-4 text-end text-danger small fw-semibold">
                                    {{ $tx->fee_amount > 0 ? ($account->currency === 'USD' ? '$' : 'L') . number_format($tx->fee_amount, 2) : '-' }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('transactions.edit', $tx) }}"
                                            class="btn btn-sm btn-outline-secondary rounded-circle"
                                            title="Editar Transacción" style="width: 32px; height: 32px; padding: 4px;">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('transactions.destroy', $tx) }}" method="POST"
                                            class="m-0"
                                            onsubmit="return confirm('¿Eliminar esta transacción? El saldo de la cuenta será restaurado.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle"
                                                title="Eliminar Transacción"
                                                style="width: 32px; height: 32px; padding: 4px;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <div class="py-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                            fill="currentColor" class="bi bi-clock-history mb-3 text-secondary opacity-50"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z" />
                                            <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0v1z" />
                                            <path
                                                d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z" />
                                        </svg>
                                        <p class="mb-0">Aún no se han registrado transacciones.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
