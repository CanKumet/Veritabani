<!DOCTYPE html>
<html>
<head>
    <title>Kullanıcılar</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    
</head>
<body>
<div class="sidebar">
<h2>Kullanıcılar</h2>
    </div>
    <div class="content">
        <h2>Kullanıcı Ekle</h2>
        
        <form method="post" action="users.php">
            <label for="isim">İsim:</label>
            <input type="text" id="isim" name="isim"><br><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email"><br><br>
            <label for="sifre">Şifre:</label>
            <input type="password" id="sifre" name="sifre"><br><br>
            <input type="submit" value="Ekle">
        </form>
    </div>

    <div class="content">
        <h2>Kullanıcılar</h2>
        
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
                $isim = $_POST['isim'];
                $email = $_POST['email'];
                $sifre = $_POST['sifre'];  
                
                if (!empty($isim) && !empty($email) && !empty($sifre)) {
                    // Veritabanı bağlantısı (bu bağlantıyı nasıl sağladığınıza bağlı olarak değişebilir)
                    $conn = new mysqli("localhost", "root", "", "veritabanı");
            
                    // Bağlantı kontrolü
                    if ($conn->connect_error) {
                        die("Veritabanına bağlantı hatası: " . $conn->connect_error);
                    }
            
                    // Stored procedure çağrısı
                    $stmt = $conn->prepare("CALL EkleKullanici(?, ?, ?)");
                    $stmt->bind_param("sss", $isim, $email, $sifre);
            
                    // Stored procedure'yi çalıştır
                    if ($stmt->execute()) {
                        echo "Yeni kullanıcı başarıyla eklendi";
                    } else {
                        echo "Kullanıcı eklenirken hata oluştu: " . $stmt->error;
                    }
            
               
                }
            }




            // Veritabanından kullanıcıları çekme sorgusu
$sql = "CALL CekKullanicilar()";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<li>İsim: " . $row["Isim"] . " - Email: " . $row["Email"] . " - Şifre: " . $row["Şifre"] . "
        <form method='post' action='users.php'>
            <input type='hidden' name='kullanici_id' value='" . $row["Kullanıcı_ID"] . "'>
            <input type='submit' value='Sil'>
        </form>
        </li>";
    }
} else {
    echo "Kullanıcı bulunamadı";
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kullanici_id = $_POST['kullanici_id'];  
    
    if (!empty($kullanici_id)) {
        // Veritabanı bağlantısı (bu bağlantıyı nasıl sağladığınıza bağlı olarak değişebilir)
        $conn = new mysqli("localhost", "root", "", "veritabanı");

        // Bağlantı kontrolü
        if ($conn->connect_error) {
            die("Veritabanına bağlantı hatası: " . $conn->connect_error);
        }

        // Stored procedure çağrısı
        $stmt = $conn->prepare("CALL SilKullanici(?)");
        $stmt->bind_param("i", $kullanici_id);

        // Stored procedure'yi çalıştır
        if ($stmt->execute()) {
            echo "Kullanıcı başarıyla silindi";
        } else {
            echo "Kullanıcı silinirken hata oluştu: " . $stmt->error;
        }

        // Bağlantıyı kapat
        $stmt->close();
        $conn->close();
    }
}


       
            ?>
        </ul>
    </div>
</body>
</html>
