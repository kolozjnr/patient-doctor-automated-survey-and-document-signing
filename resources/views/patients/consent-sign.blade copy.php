<form action="{{ route('admin.consent.sign', $user->id) }}" method="POST">
    @csrf
    <div id="pdf-container">
        <iframe src="{{ asset('assets/pdf/Consent/Gemini-3-Pro-Model-Card.pdf') }}" width="100%" height="500px"></iframe>
    </div>

    <canvas id="signature-pad" style="border: 1px solid #000;"></canvas>
    <input type="hidden" name="signature" id="signature-input">

    <button type="submit" onclick="prepareSignature()">I Agree and Sign</button>
</form>

<script>
    // Logic to convert canvas drawing to base64 and put it in signature-input
</script>