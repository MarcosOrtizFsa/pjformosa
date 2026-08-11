<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: public, max-age=60, stale-while-revalidate=300');
header('X-Content-Type-Options: nosniff');

const API_URL = 'https://equipoebersolis.com.ar/notif/php/json_api.php';

$limit = filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT, [
    'options' => ['default' => 6, 'min_range' => 3, 'max_range' => 60],
]);

if ($limit === false || $limit === null) {
    $limit = 6;
}

$payload = http_build_query(['mas' => $limit]);
$response = null;
$status = 502;

if (function_exists('curl_init')) {
    $curl = curl_init(API_URL);
    curl_setopt_array($curl, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => 12,
        CURLOPT_HTTPHEADER => ['Accept: application/json', 'Content-Type: application/x-www-form-urlencoded'],
        CURLOPT_USERAGENT => 'PJFormosa-Portal/1.0',
    ]);
    $response = curl_exec($curl);
    $status = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    curl_close($curl);
} else {
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => "Accept: application/json\r\nContent-Type: application/x-www-form-urlencoded\r\n",
            'content' => $payload,
            'timeout' => 12,
            'ignore_errors' => true,
        ],
    ]);
    $response = @file_get_contents(API_URL, false, $context);
    if (is_string($response)) {
        $status = 200;
    }
}

if (!is_string($response) || $status < 200 || $status >= 300) {
    http_response_code(502);
    echo json_encode(['error' => 'No fue posible consultar las noticias.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$decoded = json_decode($response, true);
if (!is_array($decoded) || !isset($decoded['data']) || !is_array($decoded['data'])) {
    http_response_code(502);
    echo json_encode(['error' => 'La API devolvió una respuesta inesperada.'], JSON_UNESCAPED_UNICODE);
    exit;
}

$news = [];
foreach ($decoded['data'] as $item) {
    if (!is_array($item)) {
        continue;
    }

    $gallery = (string) ($item['galeria_fotos'] ?? '');
    $image = '';
    if (preg_match('/url\([\'\"]?(https:\/\/[^\'\")]+)[\'\"]?\)/i', $gallery, $match)) {
        $candidate = filter_var(html_entity_decode($match[1], ENT_QUOTES | ENT_HTML5, 'UTF-8'), FILTER_VALIDATE_URL);
        if (is_string($candidate) && str_starts_with($candidate, 'https://equipoebersolis.com.ar/')) {
            $image = $candidate;
        }
    }

    $news[] = [
        'id' => (int) ($item['id_system_10'] ?? 0),
        'title' => trim(strip_tags((string) ($item['system_10_titulo'] ?? ''))),
        'summary' => trim(strip_tags((string) ($item['system_10_bajada'] ?? ''))),
        'text' => trim(strip_tags((string) ($item['system_10_texto'] ?? ''))),
        'category' => trim(strip_tags((string) ($item['system_10_volanta'] ?? ''))),
        'image' => $image,
    ];
}

echo json_encode(['data' => $news], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
