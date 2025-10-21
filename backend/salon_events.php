<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Access-Control-Allow-Origin: *');

$code = isset($_GET['code']) ? $_GET['code'] : null;
if (!$code) {
    echo ": no code provided\n\n";
    exit;
}

$filename = __DIR__ . '/../tmp/salon_event_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $code) . '.json';

$start = time();
while (true) {
    if (file_exists($filename)) {
        $data = file_get_contents($filename);
        echo "data: $data\n\n";
        unlink($filename);
        break;
    }
    if (time() - $start > 25) {
        echo ": keep-alive\n\n";
        break;
    }
    usleep(300000); // 0.3s
}
flush();
