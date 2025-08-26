<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <title>Register</title>
    <style>
        .container {
            width: 450px;
        }
    </style>
</head>

<body>
    <form action="register_process.php" method="POST" id="send_data">
        <div>
            <div class="container mt-5 bg-light p-4 rounded shadow-lg border border-primary">
                <h1>Registration Form</h1>
                <div class="row">
                    <div class="form-group">
                        <label for="fname">Firstname</label>
                        <input name="fname" type="text" class="form-control" id="fname" value="" placeholder="Firstname" required>
                    </div>
                    <div class="form-group">
                        <label for="lname">Lastname</label>
                        <input name="lname" type="text" class="form-control" id="lname" placeholder="Lastname" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input name="username" type="text" class="form-control" id="username" placeholder="Username" required>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input name="password" type="password" class="form-control" id="password" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">Confirm password</label>
                        <input name="confirm-password" type="password" class="form-control" id="confirm-password" placeholder="Confirm password" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input name="email" type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone number</label>
                    <input name="phone" type="text" class="form-control" id="phone" placeholder="Enter your phone" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea name="address" class="form-control" id="address" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="ref_code">Reference No.</label>
                    <input name="ref_code" type="password" class="form-control" id="ref_code" placeholder="******">
                </div>
                <div class="form-check">
                    <input name="check" type="checkbox" class="form-check-input" id="check">
                    <label class="form-check-label" for="exampleCheck1">Check me out</label>
                </div>
                <button type="button" class="btn btn-primary mt-2">Submit</button>
                <div class="mt-2">
                    <a href="./login.php">Click here to login page.</a>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Registration Success</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <img src="./check-mark-button-joypixels.gif" alt="">
                            </div>
                            <div class="modal-footer">
                                <a href="./login.php">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
                

                



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>

    <script>
        $('.btn').click(() => {
            $('#fname').removeClass('is-invalid');
            $('#lname').removeClass('is-invalid');
            $('#username').removeClass('is-invalid');
            $('#password').removeClass('is-invalid');
            $('#confirm-password').removeClass('is-invalid');
            $('#email').removeClass('is-invalid');
            $('#phone').removeClass('is-invalid');
            $('#address').removeClass('is-invalid');
            let fname = $('#fname').val();
            let lname = $('#lname').val();
            let username = $('#username').val();
            let password = $('#password').val();
            let confirmPass = $('#confirm-password').val();
            let email = $('#email').val();
            let phone = $('#phone').val();
            let address = $('#address').val();
            let ref_code = $('#ref_code').val();


            if (fname !== '') {
                $('#fname').addClass('is-valid');
            } else {
                $('#fname').addClass('is-invalid');
                $('#fname').focus();
                return false;
            }
            if (lname !== '') {
                $('#lname').addClass('is-valid');
            } else {
                $('#lname').addClass('is-invalid');
                $('#lname').focus();
                return false;
            }
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
            if (confirmPass !== '' && confirmPass === password) {
                $('#confirm-password').addClass('is-valid');
            } else {
                $('#confirm-password').addClass('is-invalid');
                $('#confirm-password').focus();
                return false;
            }
            if (email !== '') {
                $('#email').addClass('is-valid');
            } else {
                $('#email').addClass('is-invalid');
                $('#email').focus();
                return false;
            }
            if (phone !== '') {
                $('#phone').addClass('is-valid');
            } else {
                $('#phone').addClass('is-invalid');
                $('#phone').focus();
                return false;
            }
            if (address !== '') {
                $('#address').addClass('is-valid');
            } else {
                $('#address').addClass('is-invalid');
                $('#address').focus();
                return false;
            }
            if (fname !== '' && lname !== '' && username !== '' && password !== '' && confirmPass !== '' && email !== '' && phone !== '' && address !== '') {
                // $('#send_data').submit();
                var dataString = 'fname=' + fname + "&lname=" + lname + "&username=" + username + '&password=' + password + '&confirm-password=' + confirmPass + '&email=' + email + '&phone=' + phone + '&address=' + address + '&ref_code=' + ref_code; //ค่าที่จะส่งไปพร้อมตัวแปร
                $.ajax({
                    type: "POST", //METHOD "GET","POST"
                    url: "register_process.php", //File ที่ส่งค่าไปหา
                    data: dataString,
                    //cache: false,
                    success: function(data) {
                        let obj = JSON.parse(data);
                        if (obj.ret === '200') {
                            $('#exampleModal').modal('show');
                        } 
                        if (obj.ret !== '200') {
                            alert(obj.message);
                        }
                    }
                });
            }
        })
    </script>

</body>

</html>