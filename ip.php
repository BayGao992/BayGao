<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🌐 IP của bạn</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #0a0e1a, #1a1a3e);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #e2e8f0;
            padding: 20px;
        }
        .card {
            background: rgba(20, 25, 45, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 40px 48px;
            max-width: 600px;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(20px);
        }
        .ip-display {
            background: rgba(0, 0, 0, 0.4);
            border: 1px solid rgba(99, 102, 241, 0.25);
            border-radius: 14px;
            padding: 30px;
            margin: 20px 0;
        }
        .ip-display .ip {
            font-family: monospace;
            font-size: 36px;
            font-weight: 700;
            color: #a5b4fc;
            letter-spacing: 2px;
        }
        .ip-display .label {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .btn {
            background: rgba(99, 102, 241, 0.15);
            border: 1px solid rgba(99, 102, 241, 0.3);
            color: #a5b4fc;
            padding: 8px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
        }
        .btn:hover {
            background: rgba(99, 102, 241, 0.25);
        }
        .info {
            color: #94a3b8;
            font-size: 14px;
            margin-top: 16px;
        }
        .loading {
            animation: pulse 1.5s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #475569;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1 style="font-size:28px;">🌐 IP của bạn</h1>
        <p style="color:#94a3b8;font-size:14px;">Địa chỉ IP công cộng hiện tại</p>

        <div class="ip-display">
            <div class="label">ĐỊA CHỈ IP</div>
            <div class="ip" id="ipDisplay">
                <span class="loading">⏳ Đang lấy...</span>
            </div>
            <button class="btn" onclick="copyIP()" style="margin-top:12px;">📋 Sao chép IP</button>
        </div>

        <div class="info" id="info">Đang lấy thông tin...</div>

        <div class="footer">
            <span id="timestamp"></span> &middot; <a href="#" onclick="fetchIP();return false;" style="color:#6366f1;text-decoration:none;">⟳ Làm mới</a>
        </div>
    </div>

    <script>
        async function fetchIP() {
            const ipDisplay = document.getElementById('ipDisplay');
            const info = document.getElementById('info');
            
            ipDisplay.innerHTML = '<span class="loading">⏳ Đang lấy...</span>';
            info.textContent = 'Đang lấy thông tin...';
            
            try {
                const res = await fetch('/api/my-ip');
                const data = await res.json();
                
                ipDisplay.textContent = data.ip || 'Không xác định';
                info.innerHTML = `
                    <b>🖥️</b> ${data.userAgent?.split(' ').slice(0, 4).join(' ') || 'Không rõ'}
                `;
                document.getElementById('timestamp').textContent = new Date().toLocaleString('vi-VN');
                
            } catch (e) {
                ipDisplay.textContent = '⚠️ Lỗi kết nối';
                info.textContent = 'Không thể lấy IP. Vui lòng thử lại.';
            }
        }

        function copyIP() {
            const ip = document.getElementById('ipDisplay').textContent;
            if (ip.includes('Đang')) return;
            
            if (navigator.clipboard) {
                navigator.clipboard.writeText(ip).then(() => {
                    showToast('✅ Đã sao chép: ' + ip);
                });
            } else {
                const input = document.createElement('input');
                input.value = ip;
                document.body.appendChild(input);
                input.select();
                document.execCommand('copy');
                input.remove();
                showToast('✅ Đã sao chép: ' + ip);
            }
        }

        function showToast(msg) {
            const el = document.createElement('div');
            el.style.cssText = 'position:fixed;bottom:20px;left:50%;transform:translateX(-50%);background:#1a1a3e;border:1px solid rgba(99,102,241,0.4);color:#e2e8f0;padding:10px 24px;border-radius:12px;font-size:14px;z-index:9999;';
            el.textContent = msg;
            document.body.appendChild(el);
            setTimeout(() => { el.style.opacity = '0'; el.style.transition = 'opacity 0.3s'; setTimeout(() => el.remove(), 300); }, 2500);
        }

        fetchIP();
    </script>
</body>
</html>
