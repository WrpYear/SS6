<?php
    session_start();

    if (!isset($_SESSION['username'])) {
        header("Location: logout.php");
        exit;
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Point</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <style>
        .container {
            width: 450px;
        }
    </style>
</head>

<body>
    <form action="add_point_api.php" method="POST" id="add_point_form">
        <div>
            <div class="container bg-light p-4 rounded shadow-lg border border-primary mt-5">
                <h1>Add Point</h1>
                <div class="p-2 ps-0 form-group">
                    <label for="user_id">User ID</label>
                    <input name="user_id" type="number" class="form-control" id="user_id" placeholder="User ID" required>
                </div>
                <div class="p-2 ps-0 form-group">
                    <label for="username">Username</label>
                    <input name="username" type="text" class="form-control" id="username" placeholder="Username" required>
                </div>
                <div class="p-2 ps-0 form-group">
                    <label for="add_point">Number of Points</label>
                    <input name="add_point" type="number" class="form-control" id="add_point" placeholder="Number of Points" required>
                </div>
                <label for="point_type">Choose point type:</label>
                <select name="point_type" id="point_type">
                <option value="1">Point</option>
                <option value="2">Point Free</option>
                </select><br>
                <button type="button" class="mt-2 btn btn-primary" id="add-btn">Add</button>
                <a href="roulette_wheel.php">
                    <button type="button" class="mt-2 btn btn-secondary" id="add-btn">Back to Spin</button>
                </a>
            </div>
        </div>
    </form>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#add-btn').click(() => {

                $('#user_id').removeClass('is-invalid');
                $('#username').removeClass('is-invalid');
                $('#add_point').removeClass('is-invalid');
                let user_id = $('#user_id').val();
                let username = $('#username').val();
                let add_point = $('#add_point').val();
                let point_type = $('#point_type').val();
                if (user_id !== '' || username !== '') {
                    $('#user_id').addClass('is-valid');
                    $('#username').addClass('is-valid');
                } else {
                    $('#user_id').focus();
                    $('#user_id').addClass('is-invalid');
                    $('#username').addClass('is-invalid');
                    return false;
                }
                if (add_point !== '') {
                    $('#add_point').addClass('is-valid');
                } else {
                    $('#add_point').addClass('is-invalid');
                    $('#add_point').focus();
                    return false;
                }
                $.ajax({
                    type: "POST", //METHOD "GET","POST"
                    url: "add_point_api.php", //File ที่ส่งค่าไปหา
                    data: {
                        user_id: user_id,
                        username: username,
                        add_point: add_point,
                        point_type: point_type,
                    },
                    //cache: false,
                    success: function(data) {
                        let obj = JSON.parse(data);
                        if (obj.ret == '200') {
                            alert(obj.message);
                        } else {
                            alert(obj.message);
                        }
                    }
                });
            })
        })
    </script>
    

</body>

</html>