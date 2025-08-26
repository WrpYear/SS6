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
  <title>Roulette</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
  <style>
    body {
      background: #2f8f9d;
    }
    .compsoul-history-table {
      width: 90%;
      max-width: 800px;
      margin: 3rem auto;
      background-color: #181818;
      color: #fff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 0 10px rgba(0, 255, 255, 0.2);
      font-size: 1rem;
    }

    .compsoul-history-table thead tr {
      background-color: #143f6b;
      color: #fff;
      text-align: center;
    }

    .compsoul-history-table thead th {
      padding: 0.75rem;
      border-bottom: 1px solid #2f8f9d;
    }

    .compsoul-history-table tbody td {
      padding: 0.75rem;
      text-align: center;
      border-bottom: 1px solid #333;
    }

    .compsoul-history-table tbody tr:nth-child(even) {
      background-color: #222;
    }

    .compsoul-history-table tfoot td {
      font-weight: bold;
      background-color: #2f8f9d;
      color: #fff;
    }

    .compsoul-history-table .total-row {
      background-color: #feb139;
      color: #000;
      font-weight: bold;
    }
    .hidden {
      border: 0 !important;
      height: 1px !important;
      opacity: 0;
      overflow: hidden;
      padding: 0 !important;
      pointer-events: none;
      position: absolute !important;
      width: 1px !important;
    }

    .compsoul-body {
      align-items: center;
      display: flex;
      flex-flow: column wrap;
      font-size: 1.125vw;
      padding: 6vw;
    }

    .compsoul-roulette-label {
      background: #181818;
      color: #ffffff;
      cursor: pointer;
      font-family: Helvetica, Arial, sans-serif;
      font-weight: 200;
      padding: 0.8vw 1.2vw;
      margin: 0 0 3.2vw;
      text-transform: uppercase;
    }

    .compsoul-roulette-label:before {
      content: "Try your luck";
    }

    .compsoul-roulette-checkbox:checked+.compsoul-roulette-label:before {
      content: "Stop!";
    }

    .compsoul-roulette {
      transition: transform 4s ease-out;
    }

    .compsoul-roulette {
      --size: 34em;
      --number-of-items: 12;
      --angle: calc(3.1416 / var(--number-of-items));
      --tangent-first: var(--angle);
      --tangent-second: calc((1/3) * var(--angle) * var(--angle) * var(--angle));
      --tangent-third: calc((2 / 15) * var(--angle) * var(--angle) * var(--angle) * var(--angle) * var(--angle));
      --tangent-fourth: calc((17/315) * var(--angle) * var(--angle) * var(--angle) * var(--angle) * var(--angle) * var(--angle) * var(--angle));
      --tangent: calc(var(--tangent-first) + var(--tangent-second) + var(--tangent-third) + var(--tangent-fourth));
      outline: 1.2em solid #fff;
      outline-offset: -1em;
      border-radius: 100%;
      box-shadow: 1.2em 1.2em 0 -0.8em #00000022;
      height: var(--size);
      position: relative;
      width: var(--size);
      z-index: 1;
    }

    .compsoul-roulette:before,
    .compsoul-roulette:after {
      background: #00000022;
      border-radius: 100%;
      content: "";
      height: 8em;
      left: 50%;
      position: absolute;
      top: 50%;
      transform: translate(-45%, -45%);
      width: 8em;
      z-index: 2;
    }

    .compsoul-roulette:after {
      background: #ffffff no-repeat center;
      background-size: 80%;
      transform: translate(-50%, -50%);
      z-index: 2;
    }

    .compsoul-roulette .roulette-marker {
      border-radius: 0.4em 0 0 0.4em;
      left: -2em;
      overflow: hidden;
      position: absolute;
      top: 50%;
      transform: translate(0, -50%);
      z-index: 0;
    }

    .compsoul-roulette .roulette-marker:before,
    .compsoul-roulette .roulette-marker:after {
      border-bottom: 2em solid transparent;
      border-left: 4em solid #ffa3c7;
      border-top: 2em solid transparent;
      content: "";
      display: block;
      height: 0;
      width: 0;
    }

    .compsoul-roulette .roulette-marker:after {
      border-left: 4em solid #00000022;
      position: absolute;
      top: 0.4em;
      z-index: -1;
    }

    .compsoul-roulette .roulette-list {
      transition: transform 2s ease-out;
      transform: rotate(0deg);
      /* animation: roulette 0.8s linear infinite paused; */
      border-radius: 100%;
      font-family: Helvetica, Arial, sans-serif;
      height: 100%;
      list-style-type: none;
      margin: 0;
      overflow: hidden;
      padding: 0;
      position: relative;
      width: 100%;
      z-index: -1;
    }

    .compsoul-roulette-checkbox:checked+.compsoul-roulette-label+.compsoul-roulette .roulette-list {
      animation-play-state: running;
    }

    @keyframes roulette {
      0% {
        transform: rotate(0);
      }

      100% {
        transform: rotate(360deg);
      }
    }

    .compsoul-roulette .roulette-item {
      align-items: center;
      bottom: calc(var(--size) / 2);
      color: #ffffff;
      display: flex;
      font-size: 1em;
      font-weight: 600;
      height: calc(var(--size) / 2);
      left: calc(var(--size) / 4);
      position: absolute;
      text-indent: 2em;
      text-transform: uppercase;
      transform-origin: bottom center;
      width: calc(var(--size) / 2);
      writing-mode: vertical-rl;
    }

    .compsoul-roulette .roulette-item:nth-child(1) {
      transform: rotate(calc(360deg / var(--number-of-items) * 0));
    }

    .compsoul-roulette .roulette-item:nth-child(2) {
      transform: rotate(calc(360deg / var(--number-of-items) * 1));
    }

    .compsoul-roulette .roulette-item:nth-child(3) {
      transform: rotate(calc(360deg / var(--number-of-items) * 2));
    }

    .compsoul-roulette .roulette-item:nth-child(4) {
      transform: rotate(calc(360deg / var(--number-of-items) * 3));
    }

    .compsoul-roulette .roulette-item:nth-child(5) {
      transform: rotate(calc(360deg / var(--number-of-items) * 4));
    }

    .compsoul-roulette .roulette-item:nth-child(6) {
      transform: rotate(calc(360deg / var(--number-of-items) * 5));
    }

    .compsoul-roulette .roulette-item:nth-child(7) {
      transform: rotate(calc(360deg / var(--number-of-items) * 6));
    }

    .compsoul-roulette .roulette-item:nth-child(8) {
      transform: rotate(calc(360deg / var(--number-of-items) * 7));
    }

    .compsoul-roulette .roulette-item:nth-child(9) {
      transform: rotate(calc(360deg / var(--number-of-items) * 8));
    }

    .compsoul-roulette .roulette-item:nth-child(10) {
      transform: rotate(calc(360deg / var(--number-of-items) * 9));
    }

    .compsoul-roulette .roulette-item:nth-child(11) {
      transform: rotate(calc(360deg / var(--number-of-items) * 10));
    }

    .compsoul-roulette .roulette-item:nth-child(12) {
      transform: rotate(calc(360deg / var(--number-of-items) * 11));
    }

    .compsoul-roulette .roulette-item:nth-child(13) {
      transform: rotate(calc(360deg / var(--number-of-items) * 12));
    }

    .compsoul-roulette .roulette-item:nth-child(14) {
      transform: rotate(calc(360deg / var(--number-of-items) * 13));
    }

    .compsoul-roulette .roulette-item:nth-child(15) {
      transform: rotate(calc(360deg / var(--number-of-items) * 14));
    }

    .compsoul-roulette .roulette-item:nth-child(16) {
      transform: rotate(calc(360deg / var(--number-of-items) * 15));
    }

    .compsoul-roulette .roulette-item:nth-child(17) {
      transform: rotate(calc(360deg / var(--number-of-items) * 16));
    }

    .compsoul-roulette .roulette-item:nth-child(18) {
      transform: rotate(calc(360deg / var(--number-of-items) * 17));
    }

    .compsoul-roulette .roulette-item:nth-child(19) {
      transform: rotate(calc(360deg / var(--number-of-items) * 18));
    }

    .compsoul-roulette .roulette-item:nth-child(20) {
      transform: rotate(calc(360deg / var(--number-of-items) * 19));
    }

    .compsoul-roulette .roulette-item:after {
      bottom: 0;
      border-right: calc(var(--size) / 2 * var(--tangent) + 1px) solid transparent;
      border-top: calc(var(--size) / 2) solid transparent;
      border-left: calc(var(--size) / 2 * var(--tangent) + 1px) solid transparent;
      content: "";
      display: block;
      height: 0;
      left: 50%;
      position: absolute;
      transform: translate(-50%, 0);
      width: 0;
      z-index: -1;
    }

    .compsoul-roulette .roulette-item:nth-child(4n+1):after {
      border-top-color: #143f6b;
    }

    .compsoul-roulette .roulette-item:nth-child(4n+2):after {
      border-top-color: #F1E0AC;
    }

    .compsoul-roulette .roulette-item:nth-child(4n+3):after {
      border-top-color: #F55353;
    }

    .compsoul-roulette .roulette-item:nth-child(4n+4):after {
      border-top-color: #feb139;
    }
    .spin-btn-center {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 5;

      width: 15%;
      height: 15%;
      border-radius: 50%;
      background-color: #ff4d4f;
      color: white;
      border: none;
      font-weight: bold;
      font-size: 100%;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

.spin-btn-center:hover {
  background-color: #d9363e;
}
#current_point {
  background-color: #181818;
  color: #fff;
  padding: 1rem 2rem;
  margin: 2rem auto;
  border-radius: 12px;
  font-weight: bold;
  width: fit-content;
  box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
  font-size: 1.25rem;
}

#current_point span {
  color: #feb139;
  margin: 0 0.5rem;
}

#current_point .label {
  color: #7fe7dc;
}
.compsoul-roulette .roulette-list {
  transition: transform 2s ease-out;
  transform: rotate(0deg);
}

  </style>
</head>

<body>

<nav class="navbar navbar-expand-lg bg-secondary-subtle">
      <div class="container-fluid">
          <div class="collapse navbar-collapse" id="navbarNav">
              <ul class="navbar-nav">
                  <li class="nav-item me-2" id="add-point-btn">
                      <button class="btn btn-primary">
                          <a style="text-decoration: none; color: white" href="./add_point.php">Add Point</a>
                      </button>
                  </li>
                  <li class="nav-item" id="logout-btn">
                      <button class="btn btn-secondary">
                          <a style="text-decoration: none; color: white" href="./logout.php">Logout</a>
                      </button>
                  </li>
              </ul>
          </div>
      </div>
  </nav>
  <div class="compsoul-body">
    <div>
      </div>
      <div class="compsoul-roulette">
        <ul class="roulette-list">
          <li class="roulette-item">50 Points</li>
          <li class="roulette-item">100 Points</li>
          <li class="roulette-item">200 Points</li>
          <li class="roulette-item">300 Points</li>
          <li class="roulette-item">400 Points</li>
          <li class="roulette-item">500 Points</li>
          <li class="roulette-item">600 Points</li>
          <li class="roulette-item">1,000 Points</li>
          <li class="roulette-item">1,500 Points</li>
          <li class="roulette-item">2,000 Points</li>
          <li class="roulette-item">3,000 Points</li>
          <li class="roulette-item">5,000 Points</li>
        </ul>
        <div class="roulette-marker"></div>
        <button class="spin-btn-center spin-btn-center:hover" id="spin-btn">SPIN</button>
    </div>
  </div>
  <div class="text-center fs-5" id="current_point">
            <?php
            include_once('manage_point.php');
            $current_points = checkPoint($_SESSION['uid']);
            echo '
                <div class="label">Your point: <span>'.number_format($current_points['point']).'</span> Points</div>
                <div class="label">Free point: <span>'.number_format($current_points['point_free']).'</span> Points</div>';
          
            ?>
  </div>
  <div id="spin-history"></div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
  <script>
  $(document).ready(function () {
    const spinBtn = document.getElementById("spin-btn");
    const rouletteList = document.querySelector(".roulette-list");
    let currentRotation = 0;

    function loadSpinHistory() {
        $.ajax({
            url: 'spin_history_table.php',
            method: 'GET',
            success: function(response) {
                $('#spin-history').html(response);
            }
        });
    }
    loadSpinHistory();
    $('#spin-btn').on('click', function(){
      $.ajax({
        type: "POST", //METHOD "GET","POST"
        url: "roulette_api.php", //File ที่ส่งค่าไปหา
        data: {
          spin_type: 1
        },
        //cache: false,
        success: function(data) {
          console.log(data);
          let obj = JSON.parse(data);
          if (obj.ret === '200') {
            loadSpinHistory();
            $('#current_point').html(
                '<div class="label">Your point: <span>' + parseInt(obj.current_point.point).toLocaleString() + '</span> Points</div>' +
                '<div class="label">Free point: <span>' + parseInt(obj.current_point.point_free).toLocaleString() + '</span> Points</div>'
            );

            const extraSpins = 5;
            const degreesPerSlot = 360 / 12;
            const targetSlotIndex = obj.more_data.index; 
            const targetRotation = targetSlotIndex * degreesPerSlot;
            const overshoot = 15; // องศาที่หมุนเกินไปก่อนจะดีดกลับ
            const totalRotation = extraSpins * 360 - targetRotation;

            // ปรับให้ค่าหมุนเริ่มต้นเป็นรอบเต็มๆ
            currentRotation = Math.round(currentRotation / 360) * 360;

            // หมุนเกินเป้าหมายเล็กน้อย
            const overshootRotation = currentRotation + totalRotation + 265 + overshoot;
            rouletteList.style.transition = 'transform 2s ease-out';
            rouletteList.style.transform = `rotate(${overshootRotation}deg)`;

            // หลังจากหมุนเสร็จ ให้ดีดกลับเล็กน้อย
            setTimeout(() => {
              const finalRotation = overshootRotation - overshoot;
              rouletteList.style.transition = 'transform 0.5s ease-in-out';
              rouletteList.style.transform = `rotate(${finalRotation}deg)`;
              currentRotation = finalRotation; // เก็บค่าหมุนล่าสุด
            }, 2000); // ตรงกับเวลา transition: 2s

          } else {
            alert(obj.message);
          }
        }
      });
      
    })
});


  </script>
</body>

</html>