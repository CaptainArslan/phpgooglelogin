<?php
session_start();
include('config.php');
unset($_SESSION['token']);

if (isset($_GET['logout'])) { // logout: destroy token
    unset($_SESSION['token']);
    die('Logged out.');
}

if (isset($_GET['code'])) { // get auth code, get the token and store it in session
    $client->authenticate($_GET['code']);
    $_SESSION['token'] = $client->getAccessToken();
    // echo "<script>
    // window.close();
    // window.opener.location.reload();
    // </script>";
}

if (isset($_SESSION['token'])) { // get token and configure client
    $token = $_SESSION['token'];
    echo '<pre>';
    print_r($token);
    echo $token['access_token'];
    $client->setAccessToken($token['access_token']);

    if (isset($token['expires_in']) && $token['expires_in'] < time()) {
        $account = getAccount($token['access_token']);
        $refreshToken = $token['refresh_token'];
        echo '<pre>';
        echo $refreshToken;
        $accessToken = $client->fetchAccessTokenWithRefreshToken($refreshToken);
    }

    // if ($client->isAccessTokenExpired($token['access_token'])) { // Check if access token has expired
    // }
}

function getAccount($accessToken)
{
    // Step 5: Make API Request using Access Token
    $apiEndpoint = 'https://mybusinessaccountmanagement.googleapis.com/v1/accounts';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiEndpoint);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $accessToken,
        'Accept: application/json',
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $err = curl_error($ch);
    if ($err) {
        return null;
    } else {
        return $response;
    }
}

if (isset($accessToken)) {
    echo '<br>';
    echo "Refresh token";
    echo '<pre>';
    print_r($accessToken);
}

// if (!$client->getAccessToken()) { // auth call 
// $authUrl = $client->createAuthUrl();
// header("Location: " . $authUrl);
// die;
// }

// Retrieve access token from the database
// Assuming you have a function to fetch the token from the database
// $accessToken = fetchAccessTokenFromDatabase();
// if (empty($accessToken)) { // No token found, initiate the OAuth flow
//     $authUrl = $client->createAuthUrl();
//     header("Location: " . $authUrl);
//     die;
// } else { // Token found, set it in the client object
//     $client->setAccessToken($accessToken);

//     if ($client->isAccessTokenExpired()) { // Check if access token has expired
//         $refreshToken = $client->getRefreshToken();

//         if (!empty($refreshToken)) { // Check if refresh token is available
//             $client->fetchAccessTokenWithRefreshToken($refreshToken);
//             $newAccessToken = $client->getAccessToken();

//             // Update the access token in the database
//             // updateAccessTokenInDatabase($newAccessToken);

//             $client->setAccessToken($newAccessToken);
//         } else {
//             // Redirect to the authorization URL to obtain a new token
//             $authUrl = $client->createAuthUrl();
//             header("Location: " . $authUrl);
//             die;
//         }
//     }
// }


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Login</title>
</head>

<body>
    <h1>Google Login</h1>
    <button onclick="openWin()">Google Conect</button>

    <script>
        function openWin() {
            const width = 500; // Set the desired width of the new window
            const height = 600; // Set the desired height of the new window
            const left = (window.innerWidth - width) / 2; // Calculate the left position for centering
            const top = (window.innerHeight - height) / 2;
            window.open("<?php echo $client->createAuthUrl() ?>", "", `width=${width},height=${height},left=${left},top=${top}`);
        }
    </script>
</body>

</html>