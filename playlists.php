
<!DOCTYPE html>
<html>
<head>
    <title>Playlistler</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="sidebar">
<h2>Playlists</h2>
    </div>
    <div class="content">
        <h2>Çalma Listesi Ekle</h2>
        <!-- Çalma listesi ekleme formu -->
        <form method="post" action="playlists.php">
            <label for="kullanici_id">Kullanıcı ID:</label>
            <input type="text" id="kullanici_id" name="kullanici_id"><br><br>
            <label for="isim">Çalma Listesi İsmi:</label>
            <input type="text" id="isim" name="isim"><br><br>
            <input type="submit" value="Ekle">
        </form>
    </div>
    <div class="content">
    <h2>Playlist Şarkıları Ekle</h2>
    <!-- Şarkıları çalma listesine eklemek için form -->
    <form method="post" action="playlistparca.php">
        <label for="playlist_id">Çalma Listesi ID:</label>
        <input type="text" id="playlist_id" name="playlist_id"><br><br>
        <label for="song_id">Şarkı ID:</label>
        <input type="text" id="song_id" name="song_id"><br><br>
        <input type="submit" value="Şarkı Ekle">
    </form>
</div>
    <div class="content">
        <h2>Playlistler</h2>
        <!-- Playlistler listesi -->
        <!-- Örnek bir playlist listesi gösterimi -->
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
    $kullanici_id = $_POST['kullanici_id'];
    $isim = $_POST['isim'];
    
        
        if (!empty($kullanici_id) && !empty($isim)) {
            $sql = "INSERT INTO Playlists (Kullanıcı_ID, Isim) VALUES ('$kullanici_id', '$isim')";

           
    if ($conn->query($sql) === TRUE) {
        echo "Yeni playlist başarıyla eklendi";
    } else {
        echo "Playlist eklenirken hata oluştu: " . $conn->error;
    }
    
        }
    }

    $sql = "SELECT Playlist_ID, Isim FROM playlists"; // Tablo adı ve sütunlarına göre düzenlenmeli
    $result = $conn->query($sql);


    if ($result->num_rows > 0) {
      
        while ($row = $result->fetch_assoc()) {
            echo "<li>Playlist ID: " . $row["Playlist_ID"] . " - İsim: " . $row["Isim"] . "</li>";
    
            // Şarkıları çekme sorgusu
            $playlist_id = $row["Playlist_ID"];
            $songs_sql = "SELECT Parca_ID FROM PlayListParcaları WHERE Playlist_ID = '$playlist_id'";
            $songs_result = $conn->query($songs_sql);
    
            if ($songs_result->num_rows > 0) {
                echo "<ul>";
                // Playlistin şarkılarını ekrana yazdırma
                while ($song_row = $songs_result->fetch_assoc()) {
                    echo "<li>Şarkı ID: " . $song_row["Parca_ID"] . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>Bu playlistte şarkı bulunmuyor</p>";
            }
        }
    } else {
        echo "Playlist bulunamadı";
    }

   // Kullanıcıları çekme sorgusu
   $sql = "SELECT Kullanıcı_ID, Isim FROM playlists"; // Tablo adı ve sütunlarına göre düzenlenmeli
   $result = $conn->query($sql);





   if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Playlist silme işlemi
    if (isset($_POST['delete_playlist'])) {
        $playlist_id = $_POST['delete_playlist'];

        $delete_songs_sql = "DELETE FROM PlayListParcaları WHERE Playlist_ID = '$playlist_id'";
        $conn->query($delete_songs_sql); // Şarkıları silme

        $delete_playlist_sql = "DELETE FROM playlists WHERE Playlist_ID = '$playlist_id'";
        if ($conn->query($delete_playlist_sql) === TRUE) {
            echo "Playlist başarıyla silindi";
        } else {
            echo "Playlist silinirken hata oluştu: " . $conn->error;
        }
    }
}





   if ($result->num_rows > 0) {
       // Kullanıcıları ekrana yazdırma
       while ($row = $result->fetch_assoc()) {
          echo "<li>Kullanıcı ID: " . $row["Kullanıcı_ID"] . " - İsim: " . $row["Isim"] . "</li>";
       }
   } else {
       echo "Playlist bulunamadı";
   }
   $conn->close(); // Veritabanı bağlantısını kapatma

?>

        </ul>
    </div>
</body>
</html>

