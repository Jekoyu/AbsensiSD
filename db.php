<?php

$conn = new mysqli("localhost", "root", "", "nodemcu_absensi");


if ($conn->connect_errno) {
    echo "Failed to connect MYSQL $conn->connect_error";
}
