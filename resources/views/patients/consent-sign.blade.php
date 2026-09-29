<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Signing</title>
    
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.docuseal.com/js/form.js"></script>
    <style>
        body {
            padding-top: env(safe-area-inset-top, 20px);
        }
        .container {
    padding-top: max(env(safe-area-inset-top), 16px);
}
        #pdf-viewer::-webkit-scrollbar { width: 8px; }
        #pdf-viewer::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }

        .logo-container{
            display: flex;
            justify-content: center; /* horizontal center */
            align-items: center;
            padding: 10px 0;
        }
    </style>
</head>
<body class="bg-light" data-patient-id="{{ $patient->id }}">

<div class="container py-2" style="">
    <div class="card shadow border-0">
            <!-- Top Controls -->
    <div class="d-flex justify-content-between align-items-center p-3 border-bottom" style="margin-top: 20px">
        <h6 class="mb-0">{{ucfirst($document->title)}}</h6>
        {{-- <button id="skipConsentBtn" class="btn btn-outline-secondary btn-sm">
            Skip
        </button> --}}

        <!-- Zoom Controls -->
        <div class="d-flex justify-content-end align-items-center gap-2 p-2 border-bottom">
            <button class="btn btn-light btn-sm px-3" id="zoomOut" title="Zoom Out">−</button>
            <span id="zoomLabel" class="text-muted small fw-semibold" style="min-width: 42px; text-align: center;">100%</span>
            <button class="btn btn-light btn-sm px-3" id="zoomIn" title="Zoom In">+</button>
            <button class="btn btn-outline-secondary btn-sm px-2" id="zoomReset" title="Reset Zoom">↺</button>
        </div>
    </div>

                    {{-- data-with-title="false" --}}
       <div id="pdf-viewer" style="height: ; overflow: auto;">
         <div class="logo-container">
            <img src="{{ asset('assets/images/logo/main-logo.png') }}" alt="Logo" style="height: 40px; width: auto;">
        </div>
            <div id="zoom-wrapper" style="transition: width 0.2s ease;">
                <docuseal-form
                    id="docusealForm"
                    data-src="{{ $submissionUrl }}"
                    data-with-send-copy-button="false"
                    data-with-title="false"
                    data-send-copy-email="false">
                </docuseal-form>
            </div>
        </div>
    </div>
</div>
<script>

document.addEventListener("DOMContentLoaded", function() {
    const docusealForm = document.getElementById('docusealForm');

    if (docusealForm) {
      

        // Your existing 'completed' logic...
        docusealForm.addEventListener('completed', (event) => {
            const patientId = document.body.getAttribute('data-patient-id');
            window.location.href = `/consent/status/${patientId}/success`;
        });
    }


    const patientId = document.body.getAttribute('data-patient-id');
    // const skipConsentBtn = document.getElementById('skipConsentBtn');

    // skipConsentBtn.addEventListener('click', () => {
    //     skipConsent(patientId);
    // });

    // function skipConsent(patientId) {
    //     confirm('Are you sure you want to skip the consent?') || true;
    //     //window.location.href = 'myapp://consent-complete?status=success';
    //     window.location.href = `/consent/status/${patientId}/skipped`;
    // }

    if (docusealForm) {
        docusealForm.addEventListener('completed', (event) => {
            console.log('Form Completed Event:', event.detail);
            document.body.innerHTML = '<div class="container py-5 text-center"><h3>Finalizing consent...</h3></div>';
            
            setTimeout(() => {
                //window.location.href = 'myapp://consent-complete?status=success';
                 window.location.href = `/consent/status/${patientId}/success`;
            }, 500);
        });
    }


    (function () {
        const STEP    = 15;   // percent per click
        const MIN     = 75;   // minimum width %
        const MAX     = 250;  // maximum width %

        let currentPct = 100;
        const wrapper  = document.getElementById('zoom-wrapper');
        const label    = document.getElementById('zoomLabel');

        function applyZoom() {
            // Resize the wrapper — DocuSeal re-renders inside the new width (crisp, not scaled)
            wrapper.style.width = `${currentPct}%`;
            label.textContent   = `${currentPct}%`;
        }

        // Set initial width
        applyZoom();

        document.getElementById('zoomIn').addEventListener('click', () => {
            if (currentPct < MAX) {
                currentPct = Math.min(MAX, currentPct + STEP);
                applyZoom();
            }
        });

        document.getElementById('zoomOut').addEventListener('click', () => {
            if (currentPct > MIN) {
                currentPct = Math.max(MIN, currentPct - STEP);
                applyZoom();
            }
        });

        document.getElementById('zoomReset').addEventListener('click', () => {
            currentPct = 100;
            applyZoom();
        });
    }());
});

</script>

</body>
</html>