{{-- // resources/views/license/activate.blade.php
@extends($activeTemplate . 'layouts.master')

@section('content') --}}
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>{{ $pageTitle }}</h3>
                    </div>
                    <div class="card-body">
                        <form id="licenseForm" action="{{ route('license.activate.submit') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="license_key">License Key</label>
                                <input type="text" name="license_key" id="license_key" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Activate License</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('licenseForm').addEventListener('submit', function(e) {
            e.preventDefault();
            fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.type === 'success') {
                    alert('License activated successfully!');
                    window.location.href = '{{ route('home') }}';
                } else {
                    alert(data.message);
                }
            })
            .catch(error => alert('An error occurred. Please try again.'));
        });
    </script>
{{-- @endsection --}}
