<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consent Status</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-light">

<div class="container py-5" x-data="consentStatus({{ $patientId }})">
    <div class="card shadow border-0 text-center">
        <div class="card-body py-5">

            <template x-if="status === 'pending'">
                <div>
                    <div class="spinner-border text-primary mb-3"></div>
                    <h5>Waiting for consent completion…</h5>
                    <p class="text-muted">You may close this page once completed.</p>
                </div>
            </template>

            <template x-if="status === 'success'">
                <div>
                    <h4 class="text-success">Consent completed successfully ✅</h4>
                    <p>Redirecting back to the app…</p>
                </div>
            </template>

            <template x-if="status === 'failed'">
                <div>
                    <h4 class="text-danger">Consent failed ❌</h4>
                    <p>Please retry from the app.</p>
                </div>
            </template>

        </div>
    </div>
</div>

<script>
function consentStatus(patientId) {
    return {
        status: 'pending',

        init() {
            this.poll();
        },

        poll() {
            fetch(`/consent/status/${patientId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.completed) {
                        this.status = 'success';

                        // 🔥 Mobile deep link
                        setTimeout(() => {
                            window.location.href = 'myapp://consent-complete?status=success';
                        }, 800);
                    } else {
                        setTimeout(() => this.poll(), 1000);
                    }
                })
                .catch(() => {
                    setTimeout(() => this.poll(), 1500);
                });
        }
    }
}
</script>

</body>
</html>
