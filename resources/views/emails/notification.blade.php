<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Thông báo từ BDS NKS' }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
            font-family: 'Outfit', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: #334155;
            -webkit-font-smoothing: antialiased;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        .email-wrapper {
            background-color: #f1f5f9;
            padding: 40px 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(15, 23, 42, 0.08), 0 10px 10px -5px rgba(15, 23, 42, 0.04);
            border-top: 6px solid #0077bb;
        }
        .header-gradient {
            background: #0f172a;
            background: linear-gradient(135deg, #0f172a 0%, #0077bb 100%);
            padding: 45px 40px;
            text-align: center;
            border-bottom: 3px solid #0077bb;
            position: relative;
        }
        .logo-container {
            display: inline-block;
            margin-bottom: 8px;
        }
        .logo-icon {
            font-size: 32px;
            line-height: 1;
            margin-bottom: 5px;
        }
        .logo-text {
            color: #ffffff;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: 2px;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .logo-sub {
            color: #33a1e2;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-top: 6px;
            margin-bottom: 0;
        }
        .content-body {
            padding: 45px 40px 35px 40px;
        }
        .email-title {
            color: #0f172a;
            font-size: 22px;
            font-weight: 700;
            margin-top: 0;
            margin-bottom: 25px;
            line-height: 1.4;
        }
        .email-greeting {
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 18px;
        }
        .email-text {
            font-size: 15px;
            color: #475569;
            line-height: 1.7;
            margin-bottom: 25px;
        }
        .details-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #0077bb;
            border-radius: 16px;
            padding: 24px;
            margin-top: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        }
        .details-title {
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            color: #0f172a;
            letter-spacing: 1px;
            margin-top: 0;
            margin-bottom: 18px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 10px;
        }
        .detail-row {
            margin-bottom: 12px;
            font-size: 14px;
            line-height: 1.6;
            display: table;
            width: 100%;
        }
        .detail-row:last-child {
            margin-bottom: 0;
        }
        .detail-label {
            font-weight: 600;
            color: #64748b;
            display: table-cell;
            width: 150px;
            vertical-align: top;
            padding-right: 10px;
        }
        .detail-value {
            color: #1e293b;
            font-weight: 600;
            display: table-cell;
            vertical-align: top;
        }
        .btn-container {
            text-align: center;
            margin-top: 35px;
            margin-bottom: 35px;
        }
        .btn-cta {
            display: inline-block;
            background: #0077bb;
            background: linear-gradient(135deg, #0077bb 0%, #005a90 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 15px 36px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 8px 16px -4px rgba(0, 119, 187, 0.4);
            border: 1px solid rgba(0, 119, 187, 0.2);
        }
        .footer {
            background-color: #f8fafc;
            padding: 40px;
            text-align: center;
            border-top: 1px solid #f1f5f9;
        }
        .footer-logo {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        .footer-logo-blue {
            color: #0077bb;
            margin-left: 5px;
        }
        .footer-text {
            font-size: 12px;
            color: #64748b;
            line-height: 1.7;
            margin: 0;
        }
        .footer-highlight {
            color: #475569;
            font-weight: 600;
        }
        .footer-links {
            margin-top: 20px;
            font-size: 12px;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
        }
        .footer-links a {
            color: #0077bb;
            text-decoration: none;
            margin: 0 12px;
            font-weight: 600;
        }
        .auto-email-note {
            margin-top: 20px;
            font-size: 10px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header -->
            <div class="header-gradient">
                <div class="logo-container">
                    <div class="logo-icon">🏠</div>
                    <h1 class="logo-text">BDS NKS</h1>
                    <p class="logo-sub">Địa ốc chính chủ cao cấp</p>
                </div>
            </div>
            
            <!-- Content -->
            <div class="content-body">
                @if(!empty($title))
                    <h2 class="email-title">{{ $title }}</h2>
                @endif

                @if(!empty($greeting))
                    <div class="email-greeting">
                        <span style="font-size: 20px; margin-right: 8px;">👋</span>
                        <span>{{ $greeting }}</span>
                    </div>
                @endif

                <div class="email-text">
                    {!! nl2br(e($messageBody ?? '')) !!}
                </div>

                <!-- Optional Details List -->
                @if(!empty($details) && is_array($details))
                    <div class="details-card">
                        <div class="details-title">
                            <span style="margin-right: 8px;">📋</span>
                            <span>Thông tin chi tiết</span>
                        </div>
                        @foreach($details as $label => $value)
                            <div class="detail-row">
                                <span class="detail-label">{{ $label }}:</span>
                                <span class="detail-value">{!! $value !!}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Call to Action -->
                @if(!empty($ctaText) && !empty($ctaUrl))
                    <div class="btn-container">
                        <a href="{{ $ctaUrl }}" class="btn-cta" target="_blank">{{ $ctaText }}</a>
                    </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="footer">
                <div class="footer-logo">
                    <span>BẤT ĐỘNG SẢN NKS</span>
                    <span class="footer-logo-blue">★ ★ ★ ★ ★</span>
                </div>
                <p class="footer-text">Hệ thống thông tin và dịch vụ nhà đất chính chủ hàng đầu Việt Nam.</p>
                <p class="footer-text" style="margin-top: 5px;">
                    <span class="footer-highlight">Hotline:</span> 0932.030.958 | 
                    <span class="footer-highlight">Email:</span> huuluc04@gmail.com
                </p>
                <div class="footer-links">
                    <a href="{{ url('/') }}" target="_blank">Trang chủ NKS</a>
                    <a href="{{ url('/profile') }}" target="_blank">Hồ sơ & Liên hệ</a>
                </div>
                <p class="auto-email-note">Đây là hòm thư tự động của hệ thống, quý khách vui lòng không trả lời trực tiếp email này.</p>
            </div>
        </div>
    </div>
</body>
</html>
