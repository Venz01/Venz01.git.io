<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Warning - Action Required</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #dc2626;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
            border-radius: 0 0 8px 8px;
        }
        .warning-box {
            background-color: #fef2f2;
            border-left: 4px solid #dc2626;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
        h1 {
            margin: 0;
            font-size: 24px;
        }
        h2 {
            color: #dc2626;
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>⚠️ Review Warning Notice</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $caterer->name }},</p>
        
        <p>We are writing to inform you that a review for <strong>{{ $caterer->business_name }}</strong> has been flagged or removed by our administrative team.</p>
        
        <div class="warning-box">
            <h2>Warning Details</h2>
            <p><strong>Review Date:</strong> {{ $review_date }}</p>
            <p><strong>Customer:</strong> {{ $customer_name }}</p>
            <p><strong>Rating:</strong> {{ $review->rating }} / 5 stars</p>
            <p><strong>Admin Reason:</strong></p>
            <p>{{ $reason }}</p>
        </div>

        @if($review->comment)
        <div class="info-box">
            <h3 style="margin-top: 0;">Review Content</h3>
            <p style="font-style: italic;">"{{ $review->comment }}"</p>
        </div>
        @endif

        @if($review->caterer_response)
        <div class="info-box">
            <h3 style="margin-top: 0;">Your Response</h3>
            <p style="font-style: italic;">"{{ $review->caterer_response }}"</p>
        </div>
        @endif

        <h3>What This Means</h3>
        <p>This warning has been issued because the review or your response may have violated our platform's guidelines. Common reasons include:</p>
        <ul>
            <li>Inappropriate or offensive language</li>
            <li>Personal attacks or harassment</li>
            <li>False or misleading information</li>
            <li>Violation of customer privacy</li>
            <li>Unprofessional conduct</li>
        </ul>

        <h3>Next Steps</h3>
        <p>Please review our <a href="{{ url('/guidelines') }}">Community Guidelines</a> and ensure all future interactions comply with our standards.</p>
        
        <p><strong>Important:</strong> Multiple warnings may result in:</p>
        <ul>
            <li>Temporary suspension of your account</li>
            <li>Permanent account termination</li>
            <li>Removal from our platform</li>
        </ul>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/caterer/reviews') }}" class="button">View Your Reviews</a>
        </div>

        <p>If you believe this warning was issued in error or have questions, please contact our support team immediately.</p>

        <div class="footer">
            <p>This is an automated message from the {{ config('app.name') }} platform.</p>
            <p>Please do not reply to this email. For assistance, contact us at {{ config('mail.from.address') }}</p>
        </div>
    </div>
</body>
</html>