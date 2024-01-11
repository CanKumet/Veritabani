<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Veritabanı";

// Veritabanı bağlantısı
$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantı kontrolü
if ($conn->connect_error) {
    die("Veritabanı bağlantısında hata: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $playlist_id = $_POST['playlist_id'];
    $song_id = $_POST['song_id'];

    // Veritabanına eklemek için SQL sorgusu
    $sql = "INSERT INTO PlayListParcaları (Playlist_ID, Parca_ID) VALUES ('$playlist_id', '$song_id')";

    if ($conn->query($sql) === TRUE) {
        echo "Şarkı başarıyla çalma listesine eklendi";
    } else {
        echo "Şarkı eklenirken hata oluştu: " . $conn->error;
    }
}

$conn->close(); // Veritabanı bağlantısını kapatma
?>
