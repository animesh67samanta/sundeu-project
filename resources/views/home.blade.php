<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Calculator</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .card {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: none;
        }
        
        .card-header {
            background-color: #4a90d9;
            color: white;
            font-weight: 600;
            text-align: center;
            padding: 15px;
        }
        
        .btn-primary {
            background-color: #4a90d9;
            border-color: #4a90d9;
            padding: 12px;
            font-weight: 600;
        }
        
        .btn-primary:hover {
            background-color: #357abd;
            border-color: #357abd;
        }
        
        .result-display {
            font-size: 2rem;
            font-weight: bold;
            color: #198754;
        }
        
        .form-control:focus {
            border-color: #4a90d9;
            box-shadow: 0 0 0 0.25rem rgba(74, 144, 217, 0.25);
        }
        
        .invalid-feedback {
            display: block;
        }
        
        .helper-text {
            font-size: 0.75rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Payment Calculator</h4>
                    </div>
                    <div class="card-body p-4">
                        
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        <form id="paymentForm" action="{{ route('payment.process') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="amount" class="form-label fw-bold">Amount *</label>
                                <input 
                                    type="text" 
                                    class="form-control @error('amount') is-invalid @enderror" 
                                    id="amount" 
                                    name="amount" 
                                    placeholder="Enter amount (1-999.99)"
                                    value="{{ old('amount', '') }}"
                                    required
                                >
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="helper-text">Required. Between 1 and 999.99 (max 2 decimal places)</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="discount" class="form-label fw-bold">Discount *</label>
                                <input 
                                    type="text" 
                                    class="form-control @error('discount') is-invalid @enderror" 
                                    id="discount" 
                                    name="discount" 
                                    placeholder="Enter discount"
                                    value="{{ old('discount', '') }}"
                                    required
                                >
                                @error('discount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="helper-text">Required. Cannot exceed amount (max 2 decimal places)</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="tax" class="form-label fw-bold">Tax (%) *</label>
                                <input 
                                    type="text" 
                                    class="form-control @error('tax') is-invalid @enderror" 
                                    id="tax" 
                                    name="tax" 
                                    placeholder="Enter tax percentage"
                                    value="{{ old('tax', '') }}"
                                    required
                                >
                                @error('tax')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="helper-text">Required. Between 1 and 99.99 (max 2 decimal places)</div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Pay Now</button>
                            </div>
                        </form>
                        
                        @if(session('success') && session('result'))
                            <div class="alert alert-success mt-4">
                                <div class="text-center">
                                    <p class="mb-2">Payment Result:</p>
                                    <div class="result-display">Rs. {{ number_format(session('result'), 2) }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            let isValid = true;
            
            const amountInput = document.getElementById('amount');
            const discountInput = document.getElementById('discount');
            const taxInput = document.getElementById('tax');
            
          
            [amountInput, discountInput, taxInput].forEach(input => {
                input.classList.remove('is-invalid');
                const feedback = input.parentNode.querySelector('.invalid-feedback');
                if (feedback) feedback.remove();
            });
            
        
            const amount = parseFloat(amountInput.value);
            if (!amountInput.value.trim()) {
                showError(amountInput, 'The amount field is required.');
                isValid = false;
            } else if (isNaN(amount)) {
                showError(amountInput, 'The amount must be a number.');
                isValid = false;
            } else if (amount < 1 || amount > 999.99) {
                showError(amountInput, 'The amount must be between 1 and 999.99.');
                isValid = false;
            } else if (!/^\d+(\.\d{1,2})?$/.test(amountInput.value)) {
                showError(amountInput, 'The amount can have up to two decimal places.');
                isValid = false;
            }
            
           
            const discount = parseFloat(discountInput.value);
            if (!discountInput.value.trim()) {
                showError(discountInput, 'The discount field is required.');
                isValid = false;
            } else if (isNaN(discount)) {
                showError(discountInput, 'The discount must be a number.');
                isValid = false;
            } else if (discount < 0) {
                showError(discountInput, 'The discount cannot be negative.');
                isValid = false;
            } else if (!/^\d+(\.\d{1,2})?$/.test(discountInput.value)) {
                showError(discountInput, 'The discount can have up to two decimal places.');
                isValid = false;
            } else if (amount && discount > amount) {
                showError(discountInput, 'The discount cannot be greater than the amount.');
                isValid = false;
            }
            
          
            const tax = parseFloat(taxInput.value);
            if (!taxInput.value.trim()) {
                showError(taxInput, 'The tax field is required.');
                isValid = false;
            } else if (isNaN(tax)) {
                showError(taxInput, 'The tax must be a number.');
                isValid = false;
            } else if (tax < 1 || tax > 99.99) {
                showError(taxInput, 'The tax must be between 1 and 99.99.');
                isValid = false;
            } else if (!/^\d+(\.\d{1,2})?$/.test(taxInput.value)) {
                showError(taxInput, 'The tax can have up to two decimal places.');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
        
        function showError(input, message) {
            input.classList.add('is-invalid');
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            errorDiv.textContent = message;
            input.parentNode.appendChild(errorDiv);
        }
        
        @if(session('success'))
            document.getElementById('paymentForm').reset();
        @endif
    </script>
</body>
</html>

