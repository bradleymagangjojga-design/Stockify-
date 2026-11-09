<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $status === 'success' ? __('Signing you in...') : __('Sign-in failed') }}</title>
    @vite(['resources/css/app.css'])
    <style>
        html, body { height: 100%; margin: 0; }
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif;
            background: #fff;
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 24px;
            -webkit-font-smoothing: antialiased;
        }
        .box { max-width: 320px; }
        .spinner {
            width: 36px; height: 36px;
            border: 3px solid #e2e8f0;
            border-top-color: #2563eb;
            border-radius: 50%;
            margin: 0 auto 16px;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        h1 { font-size: 16px; font-weight: 700; margin: 0 0 6px; }
        p  { font-size: 13px; color: #64748b; margin: 0; line-height: 1.5; }
        .error-icon {
            width: 48px; height: 48px; margin: 0 auto 14px;
            border-radius: 50%; background: #fee2e2;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
        }
    </style>
</head>
<body>

<div class="box">
    @if ($status === 'success')
        <div class="spinner"></div>
        <h1>{{ __('Signed in!') }}</h1>
        <p>{{ __('Closing this window...') }}</p>
    @else
        <div class="error-icon">⚠️</div>
        <h1>{{ __('Sign-in failed') }}</h1>
        <p>{{ $message ?? __('Something went wrong. Please try again.') }}</p>
    @endif
</div>

<script>
(function () {
    var payload = {
        type:     'google-auth',
        status:   @json($status),
        message:  @json($message),
        redirect: @json($redirect),
    };

    // Notify the opener (parent login page)
    try {
        if (window.opener && !window.opener.closed) {
            window.opener.postMessage(payload, window.location.origin);
        }
    } catch (e) { /* cross-origin guard — ignore */ }

    // Close the popup shortly after so the user sees the confirmation flash
    setTimeout(function () {
        window.close();
    }, {{ $status === 'success' ? 250 : 1200 }});
})();
</script>

</body>
</html>