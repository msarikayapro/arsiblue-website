<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Yeni Lead — {{ $lead->name }}</title>
</head>
<body style="font-family: 'Plus Jakarta Sans', system-ui, sans-serif; background:#f7f9ff; padding:24px;">
    <div style="max-width:600px;margin:0 auto;background:#fff;border-radius:16px;padding:32px;border:1px solid #bfc7d1;">
        <h1 style="color:#005d90;margin:0 0 8px 0;font-size:24px;">🎯 Yeni Lead Geldi</h1>
        <p style="color:#404850;margin:0 0 24px 0;">{{ now()->format('d.m.Y H:i') }} · {{ $lead->landing_page ?: 'doğrudan' }}</p>

        <table style="width:100%;border-collapse:collapse;">
            <tr><td style="padding:8px 0;color:#404850;width:120px;"><strong>Ad Soyad:</strong></td><td>{{ $lead->name }}</td></tr>
            <tr><td style="padding:8px 0;color:#404850;"><strong>Telefon:</strong></td><td><a href="tel:{{ $lead->phone }}" style="color:#005d90;">{{ $lead->phone }}</a></td></tr>
            @if ($lead->message)
                <tr><td style="padding:8px 0;color:#404850;vertical-align:top;"><strong>Mesaj:</strong></td><td style="white-space:pre-wrap;">{{ $lead->message }}</td></tr>
            @endif
            @if ($lead->utm_source)
                <tr><td style="padding:8px 0;color:#404850;"><strong>UTM:</strong></td><td>{{ $lead->utm_source }} / {{ $lead->utm_campaign }}</td></tr>
            @endif
        </table>

        <div style="margin-top:24px;display:flex;gap:8px;">
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $lead->phone) }}"
               style="background:#10B981;color:#fff;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:600;">
                📱 WhatsApp'tan Yaz
            </a>
            <a href="tel:{{ $lead->phone }}"
               style="background:#005d90;color:#fff;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:600;">
                📞 Hemen Ara
            </a>
        </div>

        <p style="color:#707881;font-size:13px;margin-top:32px;border-top:1px solid #bfc7d1;padding-top:16px;">
            Admin paneli: <a href="{{ url('/admin/leads/'.$lead->id) }}">Lead detayını gör →</a>
        </p>
    </div>
</body>
</html>
