<?php
session_start();
if(isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0){
    header("Location:./");
    exit;
}
require_once('DBConnection.php');
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN | Simple Attendance System</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
    <style>
        html, body{
            height:100%;
        }
    </style>
</head>
<body>
   <div class="h-100 d-flex jsutify-content-center align-items-center">
       <div class='w-100'>
        <h3 class="py-5 text-center">NorAt System</h3>
        <div class="card my-3 col-md-4 offset-md-4">
            <div class="card-body">
                <form action="" id="login-form">
                    <center><small>Please enter your credentials.</small></center>
                    <div class="form-group">
                        <label for="username" class="control-label">Username</label>
                        <input type="text" id="username" autofocus name="username" class="form-control form-control-sm rounded-0" required>
                    </div>
                    <div class="form-group">
                        <label for="password" class="control-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control form-control-sm rounded-0" required>
                    </div>
                    <div class="form-group">
                        <label for="fingerprint">Scan Face</label>
                        <div id="fingerprint-box" class="border rounded p-1" style="width: 150px; height: 150px;">
                            <!-- Place for fingerprint sensor UI -->
                            <video id="video" width="100%" height="100%" style="display: block;"></video>
                        </div>
                        <button id="scan-fingerprint" class="btn btn-sm btn-primary rounded-0 my-1">Scan Fingerprint</button>
                    </div>
                    <div class="form-group d-flex w-100 justify-content-between">
                        <a href="./attendance.php">Go to Attendance Portal</a>
                        <button class="btn btn-sm btn-primary rounded-0 my-1">Login</button>
                    </div>
                </form>
            </div>
        </div>
       </div>
   </div>
</body>
<script>
  navigator.mediaDevices.getUserMedia({ video: true })
        .then(function (stream) {
            var video = document.getElementById('video');
            video.srcObject = stream;
            video.onloadedmetadata = function (e) {
                video.play();
            };
        })
        .catch(function (err) {
            console.log("An error occurred: " + err);
        });

      // Use face-api.js for face detection
      function detectFace() {
          const video = document.getElementById('video');
          faceapi.detectSingleFace(video).then(face => {
              if (face) {
                  alert('Face detected!');
                  // Add logic for face detection success
              } else {
                  alert('No face detected. Please try again.');
                  // Add logic for face detection failure
              }
          });
      }

     




    $(function(){
        $('#login-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            _this.find('button').attr('disabled',true)
            _this.find('button[type="submit"]').text('Loging in...')
            $.ajax({
                url:'Actions.php?a=login',
                method:'POST',
                data:$(this).serialize(),
                dataType:'JSON',
                error:err=>{
                    console.log(err)
                    _el.addClass('alert alert-danger')
                    _el.text("An error occurred.")
                    _this.prepend(_el)
                    _el.show('slow')
                    _this.find('button').attr('disabled',false)
                    _this.find('button[type="submit"]').text('Save')
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        _el.addClass('alert alert-success')
                        setTimeout(() => {
                            location.replace('./');
                        }, 2000);
                    }else{
                        _el.addClass('alert alert-danger')
                    }
                    _el.text(resp.msg)

                    _el.hide()
                    _this.prepend(_el)
                    _el.show('slow')
                    _this.find('button').attr('disabled',false)
                    _this.find('button[type="submit"]').text('Save')
                }
            })
        })
    })
</script>
</html>