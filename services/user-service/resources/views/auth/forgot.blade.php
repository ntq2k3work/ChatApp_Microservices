<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Đặt lại mật khẩu</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, sans-serif;">
    <table role="table" style="width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden;" align="center" cellpadding="0" cellspacing="0">
        <!-- Header -->
        <tr>
            <td style="background-color: #4f46e5; padding: 20px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Đặt lại mật khẩu</h1>
            </td>
        </tr>

        <!-- Nội dung -->
        <tr>
            <td style="padding: 20px;">
                <p style="font-size: 16px; color: #333333; margin-bottom: 20px;">
                    Xin chào <strong>{{ $user->name ?? 'Người dùng' }}</strong>,
                </p>
                <p style="font-size: 16px; color: #333333; margin-bottom: 20px;">
                    Chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn. Vui lòng nhấn vào nút dưới đây để tiến hành đặt lại mật khẩu:
                </p>

                <!-- Nút đặt lại mật khẩu -->
                <table role="table" style="margin: 100%; auto; text-align: center;" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="padding: 20px;">
                            <a href="{{ $resetLink }}" style="background-color: #4f46e5; color: #ffffff; padding: 12px 24px; text-decoration: none; font-size: 16px; font-weight: bold; border-radius: 4px; display: inline-block;">
                                Đặt lại mật khẩu
                            </a>

                        </td>
                    </tr>
                </table>

                <p style="font-size: 16px; color: #333; margin-bottom: 20px;">
                    Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này hoặc liên hệ với chúng tôi ngay lập tức.
                </p>

                <p style="font-size: 16px; color: #333333; margin-bottom: 20px;">
                    Liên kết đặt lại mật khẩu sẽ hết hạn sau <strong>60 phút</strong>.
                </p>

                <p style="font-size: 16px; color: #333333;">
                    Trân trọng,<br>
                    Đội ngũ <strong>{{ $appName ?? 'Ứng dụng của bạn' }}</strong>
                </p>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background-color: #f4f4f4; padding: 20px; text-align: center;">
                <p style="font-size: 14px; color: #666666; margin: 0;">
                    Nếu bạn gặp sự cố với nút trên, sao chép và dán liên kết sau vào trình duyệt của bạn:<br>
                    <a href="{{ $resetLink }}" style="color: #4f46e5; text-decoration: underline;">{{ $resetLink }}</a>
                </p>
                <p style="font-size: 14px; color: #666666; margin-top: 10px;">
                    © {{ date('Y') }} {{ $appName ?? 'Ứng dụng của bạn' }}. All rights reserved.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>