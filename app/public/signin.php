<?php
session_start();

include '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(paths: "../");
$dotenv->load();

include '../functions.php';

$_SESSION["oauth_state"] = bin2hex(string: random_bytes(length: 32));

$data = [
    "client_id" => $_ENV['GOOGLE_CLIENT_ID'],
    "redirect_uri" => $_ENV['GOOGLE_REDIRECT_URI'],
    "response_type" => "code",
    "scope" => "https://www.googleapis.com/auth/userinfo.profile+https://www.googleapis.com/auth/userinfo.email",
    "access_type" => "offline",
    "state" => $_SESSION["oauth_state"],
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signin with Google</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="d-flex w-100 justify-content-center">
                    <a href="https://accounts.google.com/o/oauth2/auth?<?= join_signin_parameters(data: $data) ?>" class="btn btn-primary btn-block">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-google" viewBox="0 0 16 16">
                        <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z"/>
                    </svg> Sign in with Google
                    </a>
                </div>
            <!-- Google Sign-in Button -->
                
            </div>
        </div>
    </div>
</body>
</html>