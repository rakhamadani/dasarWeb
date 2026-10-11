<?php
session_start();
echo "<h2>Debug Isi $_SESSION:</h2>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";
echo '<p><a href="index.php">Kembali ke Beranda</a></p>';   