<!DOCTYPE html>
<html>

<head>
    <title>RFID Reader</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
    <h1>RFID Reader</h1>
    <p id="uid">Waiting for RFID...</p>

    <script>
        $(document).ready(function() {
            setInterval(function() {
                $.ajax({
                    type: "GET",
                    url: "rfid.php",
                    data: {
                        get_uid: 1,
                    },
                    success: function(data) {
                        $("#uid").html(data);
                    }
                });
            }, 1000);
        });
    </script>
</body>

</html>