<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900;1000&display=swap" rel="stylesheet">
    <title>Market-Card</title>
    <style>
        *{
            font-family: Cairo;
        }
    </style>
</head>
<body>
<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <h1 class="alert alert-danger text-center">{{ $exception->getMessage()}}</h1>
            <!-- Display the countdown timer in an element -->

        </div>
        <div class="col my-4" >
            <div class="row justify-content-center">
<div class=" col-md-1 col-3 my-1 bg-white shadow  text-center font-bold font-weight-bold" id="day"></div>
<div class=" col-md-1 col-3 my-1 bg-white shadow  text-center font-bold font-weight-bold" id="hour"></div>
<div class=" col-md-1 col-3 my-1 bg-white shadow  text-center font-bold font-weight-bold" id="min"></div>
<div class=" col-md-1 col-3 my-1 bg-white shadow  text-center font-bold font-weight-bold" id="sec"></div>
            </div>
        </div>
    </div>
</div>

<script>
    // Set the date we're counting down to
    var countDownDate = new Date("October 13, 2023 13:00:00").getTime();

    // Update the count down every 1 second
    var x = setInterval(function() {

        // Get today's date and time
        var now = new Date().getTime();

        // Find the distance between now and the count down date
        var distance = countDownDate - now;

        // Time calculations for days, hours, minutes and seconds
        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Display the result in the element with id="demo"

        document.getElementById("day").innerHTML = "<span class='border rounded p-3  border-danger'>"+days + "</span><br><span class='d-inline-block mx-2 my-4'>يوم</span> ";
        document.getElementById("hour").innerHTML = "<span class='border rounded p-3  border-danger'>"+hours + "</span><br><span class='d-inline-block mx-2 my-4'>ساعة</span> ";
        document.getElementById("min").innerHTML = "<span class='border rounded  p-3 border-danger'>"+minutes + "</span><br><span class='d-inline-block mx-2 my-4'>دقيقة</span> ";
        document.getElementById("sec").innerHTML = "<span class='border rounded p-3  border-danger'>"+seconds + "</span><br><span class='d-inline-block mx-2 my-4'>ثانية</span> ";


        // If the count down is finished, write some text
        if (distance < 0) {
            clearInterval(x);
            document.getElementById("demo").innerHTML = "EXPIRED";
        }
    }, 1000);
</script>
</body>
</html>
