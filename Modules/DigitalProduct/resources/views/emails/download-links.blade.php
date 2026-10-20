<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $siteName }} — your downloads</title>
</head>
<body style="margin:0;padding:0;background:#f4f5f7;font-family:-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;color:#182433;">
    <div style="max-width:560px;margin:0 auto;padding:24px 16px;">
        <div style="background:#ffffff;border-radius:12px;padding:28px 24px;box-shadow:0 1px 3px rgba(0,0,0,.08);">
            <h1 style="margin:0 0 8px;font-size:20px;">{{ _e('Thanks for your purchase!', true) }}</h1>
            <p style="margin:0 0 20px;font-size:15px;line-height:1.5;color:#44515f;">
                {{ _e('Your download is ready. Use the secure link below to get your file.', true) }}
                @if ($orderId)
                    <br><span style="font-size:13px;color:#8a97a3;">{{ _e('Order', true) }} #{{ $orderId }}</span>
                @endif
            </p>

            @foreach ($links as $link)
                <div style="border:1px solid #e6e9ed;border-radius:10px;padding:16px;margin-bottom:12px;">
                    <div style="font-weight:600;font-size:15px;margin-bottom:10px;">{{ $link['title'] }}</div>
                    <a href="{{ $link['url'] }}"
                       style="display:inline-block;background:#0d6efd;color:#ffffff;text-decoration:none;font-weight:600;font-size:14px;padding:10px 18px;border-radius:8px;">
                        {{ _e('Download', true) }} {{ $link['original_name'] }}
                    </a>
                </div>
            @endforeach

            <p style="margin:18px 0 0;font-size:12px;line-height:1.5;color:#8a97a3;">
                {{ _e('This link is unique to your order. Please do not share it.', true) }}
            </p>
        </div>
    </div>
</body>
</html>
