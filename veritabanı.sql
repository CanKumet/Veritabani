
CREATE TABLE `Kullanıcı` (
  `Kullanıcı_ID` int(11) NOT NULL,
  `Isim` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Şifre` varchar(255) NOT NULL
);


CREATE TABLE `Artists` (
  `Artist_ID` int(11) NOT NULL,
  `Isim` varchar(255) NOT NULL,
  `Tur` varchar(255) NOT NULL
) ;


CREATE TABLE `Albums` (
  `Album_ID` int(11) NOT NULL,
  `Artist_ID` int(11) NOT NULL,
  `Isim` varchar(255) NOT NULL,
  `Yayın_Tarihi` DATE NOT NULL CHECK (`Yayın_Tarihi` <= CURRENT_DATE)
);


CREATE TABLE `Parcalar` (
  `Parca_ID` int(11) NOT NULL,
  `Album_ID` int(11) NOT NULL,
  `Isim` varchar(255) NOT NULL,
  `Sure` TIME NOT NULL CHECK (`Sure` <= '00:10:00')
);



CREATE TABLE `Playlists` (
  `Playlist_ID` int(11) NOT NULL,
  `Kullanıcı_ID` int(11) NOT NULL,
  `Isim` varchar(255) NOT NULL
);



CREATE TABLE `PlayListParcaları` (
  `Playlist_ID` int(11) NOT NULL,
  `Parca_ID` int(11) NOT NULL
);


DELIMITER //
CREATE PROCEDURE EkleKullanici(IN pIsim VARCHAR(255), IN pEmail VARCHAR(255), IN pSifre VARCHAR(255))
BEGIN
    INSERT INTO Kullanıcı (Isim, Email, Şifre) VALUES (pIsim, pEmail, pSifre);
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE EkleParca(IN pAlbumID INT, IN pIsim VARCHAR(255), IN pSure TIME)
BEGIN
    INSERT INTO Parcalar (Album_ID, Isim, Sure) VALUES (pAlbumID, pIsim, pSure);
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE EklePlaylist(IN pKullaniciID INT, IN pIsim VARCHAR(255))
BEGIN
    INSERT INTO Playlists (Kullanıcı_ID, Isim) VALUES (pKullaniciID, pIsim);
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE EklePlaylistParcasi(IN pPlaylistID INT, IN pParcaID INT)
BEGIN
    INSERT INTO PlayListParcaları (Playlist_ID, Parca_ID) VALUES (pPlaylistID, pParcaID);
END //
DELIMITER ;


DELIMITER //
CREATE PROCEDURE EkleSanatci(IN pIsim VARCHAR(255), IN pTur VARCHAR(255))
BEGIN
    INSERT INTO Artists (Isim, Tur) VALUES (pIsim, pTur);
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE EkleAlbum(IN pArtistID INT, IN pIsim VARCHAR(255), IN pYayinTarihi DATE)
BEGIN
    INSERT INTO Albums (Artist_ID, Isim, Yayın_Tarihi) VALUES (pArtistID, pIsim, pYayinTarihi);
END //
DELIMITER ;


DELIMITER //
CREATE PROCEDURE SilKullanici(IN pKullaniciID INT)
BEGIN
    DELETE FROM Kullanıcı WHERE Kullanıcı_ID = pKullaniciID;
END //
DELIMITER ;


DELIMITER //
CREATE PROCEDURE CekKullanicilar()
BEGIN
    SELECT Kullanıcı_ID, Isim, Email,Şifre FROM kullanıcı;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE CekParcalar()
BEGIN
    SELECT Album_ID, Isim, Sure FROM parcalar;
END //
DELIMITER ;


DELIMITER //
CREATE PROCEDURE SilSanatci(IN pArtistID INT)
BEGIN
    DELETE FROM Artists WHERE Artist_ID = pArtistID;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE CekVeSilSanatcilar()
BEGIN
    
    SELECT Artist_ID, Isim, Tur FROM Artists;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE CekAlbumler()
BEGIN
    
    SELECT Artist_ID, Isim, Yayın_Tarihi FROM albums;
END //
DELIMITER ;


DELIMITER //
CREATE PROCEDURE CekPlaylistVeSarkilar()
BEGIN
    
    SELECT Playlist_ID, Isim FROM playlists;

   
    SELECT Playlist_ID, Parca_ID FROM PlayListParcaları;
END //
DELIMITER ;


DELIMITER //
CREATE PROCEDURE CekKullanicilarVeSilPlaylist()
BEGIN
 
    SELECT Kullanıcı_ID, Isim FROM kullanıcı;


    IF EXISTS (SELECT 1 FROM playlists WHERE Playlist_ID = @delete_playlist_id) THEN
        DELETE FROM PlayListParcaları WHERE Playlist_ID = @delete_playlist_id;
        DELETE FROM playlists WHERE Playlist_ID = @delete_playlist_id;
    END IF;
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER kullanici_eklendi_trigger
AFTER INSERT ON Kullanıcı
FOR EACH ROW
BEGIN
    DECLARE default_playlist_name VARCHAR(255);
    SET default_playlist_name = CONCAT('Varsayılan Playlist - ', NEW.Isim);
    INSERT INTO Playlists (Kullanıcı_ID, Isim) VALUES (NEW.Kullanıcı_ID, default_playlist_name);
END;
//
DELIMITER ;

DELIMITER //
CREATE TRIGGER sanatci_silindi_trigger
AFTER DELETE ON Artists
FOR EACH ROW
BEGIN
    DELETE FROM Albums WHERE Artist_ID = OLD.Artist_ID;
END;
//
DELIMITER ;

DELIMITER //
CREATE TRIGGER playlist_silindi_trigger
AFTER DELETE ON Playlists
FOR EACH ROW
BEGIN
    DELETE FROM PlayListParcaları WHERE Playlist_ID = OLD.Playlist_ID;
END;
//
DELIMITER ;



ALTER TABLE `Kullanıcı`
  ADD PRIMARY KEY (`Kullanıcı_ID`);


ALTER TABLE `Artists`
  ADD PRIMARY KEY (`Artist_ID`);

ALTER TABLE `Albums`
  ADD PRIMARY KEY (`Album_ID`);

ALTER TABLE `Parcalar`
  ADD PRIMARY KEY (`Parca_ID`);

  ALTER TABLE `Playlists`
  ADD PRIMARY KEY (`Playlist_ID`);

ALTER TABLE `PlayListParcaları`
  ADD PRIMARY KEY (`Playlist_ID`,`Parca_ID`);


ALTER TABLE `Kullanıcı`
  MODIFY `Kullanıcı_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

ALTER TABLE `Artists`
  MODIFY `Artist_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `Albums`
  MODIFY `Album_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `Parcalar`
  MODIFY `Parca_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `Playlists`
    MODIFY `Playlist_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;




ALTER TABLE `Albums`
   ADD CONSTRAINT FK_Artist_ID FOREIGN KEY (`Artist_ID`) REFERENCES `Artists` (`Artist_ID`);
COMMIT;

ALTER TABLE `Parcalar`
 ADD CONSTRAINT FK_Parca_ID  FOREIGN KEY (`Album_ID`) REFERENCES `Albums` (`Album_ID`);
COMMIT;

ALTER TABLE `PlayListParcaları`
  ADD CONSTRAINT FK_PlayListParcaları_ID FOREIGN KEY (`Playlist_ID`) REFERENCES `Playlists` (`Playlist_ID`);
COMMIT;

ALTER TABLE `PlayListParcaları`
  ADD CONSTRAINT FK_Parca_ID FOREIGN KEY (`Parca_ID`) REFERENCES `Parcalar` (`Parca_ID`);
COMMIT;


ALTER TABLE `Playlists`
 ADD CONSTRAINT FK_Kullancı_ID FOREIGN KEY (`Kullanıcı_ID`) REFERENCES `Kullanıcı` (`Kullanıcı_ID`);
COMMIT;

