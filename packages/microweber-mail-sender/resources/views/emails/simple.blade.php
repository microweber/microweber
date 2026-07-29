<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>{{ $subject ?? 'Email' }}</title>
    <style>
        body {
            background-color: #f6f6f6;
            font-family: sans-serif;
            -webkit-font-smoothing: antialiased;
            font-size: 14px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .body { background-color: #f6f6f6; width: 100%; }
        .container {
            display: block;
            margin: 0 auto !important;
            max-width: 580px;
            padding: 10px;
            width: 580px;
        }
        .content {
            box-sizing: border-box;
            display: block;
            margin: 0 auto;
            max-width: 580px;
            padding: 10px;
        }
        .main {
            background: #ffffff;
            border-radius: 3px;
            width: 100%;
        }
        .wrapper {
            box-sizing: border-box;
            padding: 20px;
        }
        .footer {
            clear: both;
            margin-top: 10px;
            text-align: center;
            width: 100%;
            color: #999999;
            font-size: 12px;
        }
    </style>
</head>
<body>
<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body" width="100%">
    <tr>
        <td>&nbsp;</td>
        <td class="container">
            <div class="content">
                @if (!empty($logo))
                    <div style="text-align: center; margin-bottom: 12px;">
                        <img src="{{ $logo }}" alt="{{ $site_name ?? '' }}" style="max-width: 200px">
                    </div>
                @endif

                <table role="presentation" class="main" width="100%">
                    <tr>
                        <td class="wrapper">
                            {!! $content !!}
                        </td>
                    </tr>
                </table>

                @if (!empty($site_name) || !empty($site_url) || !empty($site_description))
                    <div class="footer">
                        @if (!empty($site_url) && !empty($site_name))
                            <a href="{{ $site_url }}">{{ $site_name }}</a><br/>
                        @elseif (!empty($site_name))
                            {{ $site_name }}<br/>
                        @endif
                        @if (!empty($site_description))
                            {{ $site_description }}
                        @endif
                    </div>
                @endif
            </div>
        </td>
        <td>&nbsp;</td>
    </tr>
</table>
</body>
</html>
