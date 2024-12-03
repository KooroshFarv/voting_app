<?php
$password = 'kooroshkoorosh'; 
$hash = '$2y$10$d.ky9TKLtGSm1U/afXLgNOfZpeeLxLVsOozkUPxyWb9.FTcGfLs.u'; 
echo password_verify($password, $hash) ? "Match" : "No match";
?>

