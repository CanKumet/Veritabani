
<!DOCTYPE html>
<html>
<head>
    <title>Sanatçılar</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    
</head>
<body>
<div class="sidebar">
<h2>Şarkıcılar</h2>
    </div>
    <div class="content">
        <h2>Sanatçı Ekle</h2>
        <!-- Sanatçı ekleme formu -->
        <form method="post" action="artists.php">
            <label for="isim">Sanatçı İsmi:</label>
            <input type="text" id="isim" name="isim"><br><br>
            <label for="tur">Tür:</label>
            <input type="text" id="tur" name="tur"><br><br>
            <input type="submit" value="Ekle">
        </form>
    </div>
    <div class="content">
        <h2>Sanatçılar</h2>
        <!-- Sanatçılar listesi -->
        <!-- Örnek bir sanatçı listesi gösterimi -->
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
            $isim = $_POST['isim'];
            $tur = $_POST['tur'];  
            
            if (!empty($isim) && !empty($tur)) {
                // Veritabanı bağlantısı (bu bağlantıyı nasıl sağladığınıza bağlı olarak değişebilir)
                $conn = new mysqli("localhost", "root", "", "veritabanı");
        
                // Bağlantı kontrolü
                if ($conn->connect_error) {
                    die("Veritabanına bağlantı hatası: " . $conn->connect_error);
                }
        
                // Stored procedure çağrısı
                $stmt = $conn->prepare("CALL EkleSanatci(?, ?)");
                $stmt->bind_param("ss", $isim, $tur);
        
                // Stored procedure'yi çalıştır
                if ($stmt->execute()) {
                    echo "Yeni sanatçı başarıyla eklendi";
                } else {
                    echo "Sanatçı eklenirken hata oluştu: " . $stmt->error;
                }
        
                
      
            }
        }
        


        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Sanatçı silme işlemi
            if (isset($_POST['delete_artist'])) {
                $artist_id = $_POST['delete_artist'];
        
                // Veritabanı bağlantısı
                $conn = new mysqli("localhost", "root", "", "veritabanı");
        
                // Bağlantı kontrolü
                if ($conn->connect_error) {
                    die("Veritabanına bağlantı hatası: " . $conn->connect_error);
                }
        
                // Stored procedure çağrısı
                $stmt = $conn->prepare("CALL SilSanatci(?)");
                $stmt->bind_param("i", $artist_id);
        
                // Stored procedure'yi çalıştır
                if ($stmt->execute()) {
                    echo "Sanatçı başarıyla silindi";
                } else {
                    echo "Sanatçı silinirken hata oluştu: " . $stmt->error;
                }
        
                // Bağlantıyı kapat
                $stmt->close();
             
            }
        }
        



        // Stored procedure çağrısı
$sql_artists = "CALL CekVeSilSanatcilar()";
$result = $conn->query($sql_artists);

if ($result) {
    if ($result->num_rows > 0) {
        // Sanatçıları ekrana yazdırma
        while ($row = $result->fetch_assoc()) {
            echo "<li>İsim: " . $row["Isim"] . " - Tür: " . $row["Tur"] . "
                <form method='post'>
                    <input type='hidden' name='delete_artist' value='" . $row["Artist_ID"] . "'>
                    <input type='submit' value='Sil'>
                </form>
            </li>";
        }
    } else {
        echo "Henüz sanatçı bulunamadı";
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


