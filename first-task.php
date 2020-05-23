<?php
$token = $_GET['token'] ?? "";
$apiUrl = "https://api.vk.com/method/";
$version = "5.103";
$clientId = 7479476;
$client_secret = "k45S19YPkmeIWl4xctPO";
$userId = 146381789;
$userFavoriteActivity = "Программирование";

$standardsQuires = [
    "access_token" => $token,
    "v" => $version
];

if (isset($_GET['code'])) {
    $result = file_get_contents("https://oauth.vk.com/access_token?client_id=" . $clientId . "&client_secret=" .
        $client_secret . "&redirect_uri=http://pavlovq5.beget.tech/test.php&code=" . $_GET['code']);
    $result = json_decode($result, true);
    if (isset($result['access_token'])) {
        header("Location: test.php?token=" . $result['access_token']);
    }
}

function makeQuery($method, $queries) {
    global $apiUrl, $standardsQuires;

    $queries += $standardsQuires;
    $quires = http_build_query($queries);

    $result = file_get_contents($apiUrl . $method . "?" . $quires);
    $resultArray = json_decode($result, true);

    if (isset($resultArray['error']))
        return false;
    else
        return $resultArray;
}

function isUserHasGroup($userId) {
    global $userFavoriteActivity;

    $groups = makeQuery("groups.get", ["user_id" => $userId, "extended" => 1, "fields" => "activity"]);
    if ($groups !== false) {
        foreach ($groups["response"]["items"] as $group) {
            if ($group['activity'] == $userFavoriteActivity)
                return true;
        }
    }

    return false;
}

if ($token != "") {
    $friends = makeQuery("friends.get", []);
    foreach ($friends["response"]["items"] as $friendId){
        if (isUserHasGroup($friendId))
            echo $friendId . "<br>";
    }
}
?>
<a href="https://oauth.vk.com/authorize?client_id=<?=$clientId?>&display=page&redirect_uri=http://pavlovq5.beget.tech/test.php&scope=friends,offline,groups&response_type=code&v=5.103">
    Login VK
</a>
