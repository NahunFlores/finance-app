@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ route('accounts.index') }}" class="btn btn-light rounded-circle p-2 me-3 shadow-sm border-0"
                        style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                            class="bi bi-arrow-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                        </svg>
                    </a>
                    <h2 class="mb-0 fw-bold">Agregar Nueva Cuenta</h2>
                </div>

                <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-bottom p-4">
                        <h5 class="mb-0 text-muted fw-semibold">Detalles de la Cuenta</h5>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('accounts.store') }}" method="POST">
                            @csrf

                            <div class="row g-4 mb-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Nombre de la Cuenta</label>
                                    <input type="text" name="name" class="form-control form-control-lg bg-light"
                                        required placeholder="ej. Visa Blue Rewards">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Tipo de Cuenta</label>
                                    <select name="type" class="form-select form-select-lg bg-light" required>
                                        <option value="debit">Cuenta de Débito</option>
                                        <option value="credit">Tarjeta de Crédito</option>
                                        <option value="cash">Efectivo / Billetera</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Moneda</label>
                                    <select name="currency" class="form-select form-select-lg bg-light" required>
                                        <option value="HNL">Lempiras (HNL)</option>
                                        <option value="USD">Dólares (USD)</option>
                                    </select>
                                </div>

                                <div class="col-md-12" id="standard-balance-container">
                                    <label class="form-label fw-semibold">Saldo Inicial</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0 text-muted"
                                            id="currency-symbol">L</span>
                                        <input type="number" step="0.01" name="balance"
                                            class="form-control bg-light border-start-0 ps-0" value="0.00"
                                            id="standard-balance-input">
                                    </div>
                                </div>

                                <!-- Dual balances explicitly for credit cards -->
                                <div class="col-md-12 d-none" id="credit-card-balances-container">
                                    <label class="form-label fw-semibold">Deuda Actual Inicial</label>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="input-group input-group-lg">
                                                <span class="input-group-text bg-light border-end-0 text-muted">L</span>
                                                <input type="number" step="0.01" name="balance_hnl"
                                                    class="form-control bg-light border-start-0 ps-0"
                                                    placeholder="Deuda en HNL" value="0.00">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group input-group-lg">
                                                <span class="input-group-text bg-light border-end-0 text-muted">$</span>
                                                <input type="number" step="0.01" name="usd_balance"
                                                    class="form-control bg-light border-start-0 ps-0"
                                                    placeholder="Deuda en USD" value="0.00">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 bg-light rounded-4 border border-light">
                                <h6 class="text-secondary mb-3 fw-bold"><i class="bi bi-credit-card me-2"></i>Opciones de
                                    Tarjeta de Crédito <small class="fw-normal">(Dejar en blanco si no aplica)</small></h6>

                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold">Límite de Crédito</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white border-end-0 text-muted">$</span>
                                            <input type="number" step="0.01" name="credit_limit"
                                                class="form-control bg-white border-start-0 ps-0" placeholder="0.00">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold">Día de Corte</label>
                                        <input type="number" name="cut_off_day" class="form-control bg-white"
                                            placeholder="1-31" min="1" max="31">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-semibold">Día de Pago</label>
                                        <input type="number" name="payment_due_day" class="form-control bg-white"
                                            placeholder="1-31" min="1" max="31">
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mt-5 pt-3 border-top">
                                <a href="{{ route('accounts.index') }}"
                                    class="btn btn-light fw-semibold me-3 px-4 py-2 rounded-pill">Cancelar</a>
                                <button type="submit"
                                    class="btn btn-primary fw-semibold px-5 py-2 rounded-pill shadow-sm">Guardar
                                    Cuenta</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.querySelector('select[name="type"]');
            const currencySelect = document.querySelector('select[name="currency"]');
            const currencySymbol = document.getElementById('currency-symbol');
            const standardContainer = document.getElementById('standard-balance-container');
            const creditCardContainer = document.getElementById('credit-card-balances-container');

            function updateForm() {
                const isCreditCard = typeSelect.value === 'credit';
                const currency = currencySelect.value;

                // Toggle credit card specific inputs vs standard single balance
                if (isCreditCard) {
                    standardContainer.classList.add('d-none');
                    creditCardContainer.classList.remove('d-none');
                } else {
                    standardContainer.classList.remove('d-none');
                    creditCardContainer.classList.add('d-none');
                    // Update currency symbol for standard input
                    currencySymbol.textContent = currency === 'USD' ? '$' : 'L';
                }
            }

            typeSelect.addEventListener('change', updateForm);
            currencySelect.addEventListener('change', updateForm);

            // Initialize state
            updateForm();
        });
    </script>
@endsection
