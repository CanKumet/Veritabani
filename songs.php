<!DOCTYPE html>
<html>
<head>
    <title>Şarkılar</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="sidebar">
<h2>Şarkılar</h2>
    </div>
    <div class="content">
        <h2>Şarkı Ekle</h2>
       <!-- Parça ekleme formu -->
       <form method="post" action="songs.php">
            <label for="album_id">Albüm ID:</label>
            <input type="text" id="album_id" name="album_id"><br><br>
            <label for="isim">Parça İsmi:</label>
            <input type="text" id="isim" name="isim"><br><br>
            <label for="sure">Parça Süresi (Saat:Dk:Saniye):</label>
            <input type="text" id="sure" name="sure"><br><br>
            <input type="submit" value="Ekle">
        </form>
    </div>

    <div class="content">
        <h2>Şarkılar</h2>
        <!-- Şarkılar listesi -->
        <!-- Örnek bir şarkı listesi gösterimi -->
        <ul>


            <?php
            error_reporting(0);
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
                $album_id = $_POST['album_id'];
                $isim = $_POST['isim'];  
                $sure = $_POST['sure'];
                
                if (!empty($album_id) && !empty($isim)) {
                    // Veritabanı bağlantısı (bu bağlantıyı nasıl sağladığınıza bağlı olarak değişebilir)
                    $conn = new mysqli("localhost", "root", "", "veritabanı");
            
                    // Bağlantı kontrolü
                    if ($conn->connect_error) {
                        die("Veritabanına bağlantı hatası: " . $conn->connect_error);
                    }
            
                    // Stored procedure çağrısı
                    $stmt = $conn->prepare("CALL EkleParca(?, ?, ?)");
                    $stmt->bind_param("iss", $album_id, $isim, $sure);
            
                    // Stored procedure'yi çalıştır
                    if ($stmt->execute()) {
                        echo "Yeni parça başarıyla eklendi";
                    } else {
                        echo "Parça eklenirken hata oluştu: " . $stmt->error;
                    }
            
                    // Bağlantıyı kapat
                    $stmt->close();
                }
            }
            


            
          // Veritabanından parçaları çekme sorgusu
$sql = "CALL CekParcalar()";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Parçaları ekrana yazdırma
    while ($row = $result->fetch_assoc()) {
        echo "<li>Album ID: " . $row["Album_ID"] . " - İSİM: " . $row["Isim"] . " - SÜRE: " . $row["Sure"] . "</li>";
    }
} else {
    echo "Şarkı bulunamadı";
}

// Veritabanı bağlantısını kapatma
$conn->close();


            ?>
        </ul>
    </div>
    
</body>
</html>

