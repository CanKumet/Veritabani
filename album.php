
<!DOCTYPE html>
<html>
<head>
    <title>Albümler</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="sidebar">
<h2>Albümler</h2>
    </div>
    <div class="content">
        <h2>Albüm Ekle</h2>
        <!-- Albüm ekleme formu -->
        <form method="post" action="album.php">
            <label for="artist_id">Sanatçı ID:</label>
            <input type="text" id="artist_id" name="artist_id"><br><br>
            <label for="isim">Albüm İsmi:</label>
            <input type="text" id="isim" name="isim"><br><br>
            <label for="yayin_tarihi">Yayın Tarihi:</label>
            <input type="date" id="yayin_tarihi" name="yayin_tarihi"><br><br>
            <input type="submit" value="Ekle">
        </form>
    </div>
    <div class="content">
        <h2>Albümler</h2>

        <ul>
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
    $artist_id = $_POST['artist_id'];
    $isim = $_POST['isim'];
    $yayin_tarihi = $_POST['yayin_tarihi'];  
    
    if (!empty($artist_id) && !empty($isim) && !empty($yayin_tarihi)) {
        // Veritabanı bağlantısı (bu bağlantıyı nasıl sağladığınıza bağlı olarak değişebilir)
        $conn = new mysqli("localhost", "root", "", "veritabanı");

        // Bağlantı kontrolü
        if ($conn->connect_error) {
            die("Veritabanına bağlantı hatası: " . $conn->connect_error);
        }

        // Stored procedure çağrısı
        $stmt = $conn->prepare("CALL EkleAlbum(?, ?, ?)");
        $stmt->bind_param("iss", $artist_id, $isim, $yayin_tarihi);

        // Stored procedure'yi çalıştır
        if ($stmt->execute()) {
            echo "Yeni albüm başarıyla eklendi";
        } else {
            echo "Albüm eklenirken hata oluştu: " . $stmt->error;
        }

        // Bağlantıyı kapat
        $stmt->close();
    
    }
}



// Stored procedure çağrısı
$sql_albums = "CALL CekAlbumler()";
$result = $conn->query($sql_albums);

if ($result) {
    if ($result->num_rows > 0) {
        // Albümleri ekrana yazdırma
        while ($row = $result->fetch_assoc()) {
            echo "<li>ID: " . $row["Artist_ID"] . " - İsim: " . $row["Isim"] . " - Yayın Tarihi: " . $row["Yayın_Tarihi"] . "</li>";
        }
    } else {
        echo "Henüz albüm bulunamadı";
    }

    // Stored procedure çağrısından sonra bağlantıyı kapat
    $conn->close();
} else {
    echo "Stored procedure çağrısında hata: " . $conn->error;
}

?>

        </ul>
    </div>
</body>
</html>

