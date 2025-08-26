<?php
    session_start();

    if (isset($_SESSION['username'])) {
        header("Location: roulette_wheel.php");
        exit;
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <style>
        .container {
            width: 450px;
        }
    </style>
</head>

<body>
    <form action="login_process.php" method="POST" id="reg_form">
        <div>
            <div class="container bg-light p-4 rounded shadow-lg border border-primary mt-5">
                <h1>Log In</h1>
                <div class="p-2 ps-0 form-group">
                    <label for="username">Username</label>
                    <input name="username" type="text" class="form-control" id="username" placeholder="Username" required>
                </div>
                <div class="p-2 ps-0 form-group">
                    <label for="password">Password</label>
                    <input name="password" type="password" class="form-control" id="password" placeholder="Password" required>
                </div>
                <button type="button" class="mt-2 btn btn-primary" id="login-btn">Login</button>
                <a href="./registerform.php">
                    <button type="button" class="mt-2 btn btn-secondary" id="sign-up-btn">Sign up</button>
                </a>
            </div>
        </div>
    </form>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#login-btn').click(() => {

                $('#username').removeClass('is-invalid');
                $('#password').removeClass('is-invalid');
                let username = $('#username').val();
                let password = $('#password').val();
                if (username !== '') {
                    $('#username').addClass('is-valid');
                } else {
                    $('#username').addClass('is-invalid');
                    $('#username').focus();
                    return false;
                }
                if (password !== '') {
                    $('#password').addClass('is-valid');
                } else {
                    $('#password').addClass('is-invalid');
                    $('#password').focus();
                    return false;
                }
                $.ajax({
                    type: "POST", //METHOD "GET","POST"
                    url: "login_process.php", //File ที่ส่งค่าไปหา
                    data: {
                        username: username,
                        password: password
                    },
                    //cache: false,
                    success: function(data) {
                        let obj = JSON.parse(data);
                        if (obj.ret !== '200') {
                            alert(obj.message);
                        } else {
                            window.location.href = "roulette_wheel.php";
                            location.reload();
                        }
                    }
                });
            })
        })
    </script>
    

</body>

</html>