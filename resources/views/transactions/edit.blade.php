@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ route('accounts.show', $account) }}"
                        class="btn btn-light rounded-circle p-2 me-3 shadow-sm border-0"
                        style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                            class="bi bi-arrow-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                        </svg>
                    </a>
                    <h2 class="mb-0 fw-bold">Editar Transacción</h2>
                </div>

                <p class="text-muted mb-4">Editando transacción de la cuenta: <strong>{{ $account->name }}</strong></p>

                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('transactions.update', $transaction) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tipo de Transacción</label>
                                    <select name="type" class="form-select form-select-lg bg-light" required>
                                        @if ($account->type == 'credit')
                                            <option value="expense" {{ $transaction->type == 'expense' ? 'selected' : '' }}>
                                                💳 Compra / Gasto (+ Deuda)</option>
                                            <option value="withdrawal"
                                                {{ $transaction->type == 'withdrawal' ? 'selected' : '' }}>💵 Retiro de
                                                Efectivo (+ Deuda)</option>
                                            <option value="payment" {{ $transaction->type == 'payment' ? 'selected' : '' }}>
                                                📈 Pago de Tarjeta (- Deuda)</option>
                                        @else
                                            <option value="income" {{ $transaction->type == 'income' ? 'selected' : '' }}>📈
                                                Ingreso (+)</option>
                                            <option value="expense" {{ $transaction->type == 'expense' ? 'selected' : '' }}>
                                                📉 Gasto (-)</option>
                                            <option value="withdrawal"
                                                {{ $transaction->type == 'withdrawal' ? 'selected' : '' }}>💵 Retiro de
                                                Efectivo (-)</option>
                                        @endif
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Fecha</label>
                                    <input type="date" name="transaction_date"
                                        class="form-control form-control-lg bg-light" required
                                        value="{{ $transaction->transaction_date->format('Y-m-d') }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Monto</label>
                                    <div class="input-group input-group-lg">
                                        <select name="currency" class="form-select bg-light border-end-0 text-muted"
                                            style="max-width: 100px;">
                                            <option value="HNL"
                                                {{ $transaction->original_currency == 'HNL' ? 'selected' : '' }}>L</option>
                                            <option value="USD"
                                                {{ $transaction->original_currency == 'USD' ? 'selected' : '' }}>$</option>
                                        </select>
                                        <input type="number" step="0.01" name="amount" class="form-control bg-light"
                                            required value="{{ $transaction->original_amount }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Comisión (Opcional)</label>
                                    <div class="input-group input-group-lg">
                                        <span
                                            class="input-group-text bg-light border-end-0 text-muted">{{ $transaction->original_currency === 'USD' ? '$' : 'L' }}</span>
                                        <input type="number" step="0.01" name="fee_amount"
                                            class="form-control bg-light border-start-0 ps-0"
                                            value="{{ $transaction->fee_amount }}">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Descripción</label>
                                    <input type="text" name="description" class="form-control form-control-lg bg-light"
                                        placeholder="¿Para qué fue esto?" value="{{ $transaction->description }}">
                                </div>
                            </div>

                            <div class="text-end mt-5 pt-3 border-top">
                                <a href="{{ route('accounts.show', $account) }}"
                                    class="btn btn-light fw-semibold me-3 px-4 py-2 rounded-pill">Cancelar</a>
                                <button type="submit"
                                    class="btn btn-primary fw-semibold px-5 py-2 rounded-pill shadow-sm">Actualizar
                                    Transacción</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
