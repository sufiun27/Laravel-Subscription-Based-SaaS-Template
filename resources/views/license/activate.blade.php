<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? 'Activate License' }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">{{ $pageTitle ?? 'Activate License' }}</h3>
                    </div>
                    <div class="card-body">
                        <form id="licenseForm" action="{{ route('license.activate.submit') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="license_key" class="form-label">License Key</label>
                                <input type="text" name="license_key" id="license_key" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Activate License</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('licenseForm');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.type === 'success') {
                    alert('License activated successfully!');
                    window.location.href = '{{ route('home') }}';
                } else {
                    alert(data.message || 'Activation failed. Please try again.');
                }
            })
            .catch(() => alert('An error occurred. Please try again.'));
        });
    });
    </script>
</body>
</html>
