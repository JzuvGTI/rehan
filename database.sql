CREATE DATABASE IF NOT EXISTS perpus;
USE perpus;

-- Tabel USER
CREATE TABLE user (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(255) NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Email VARCHAR(255) NOT NULL,
    NamaLengkap VARCHAR(255) NOT NULL,
    Alamat TEXT,
    role INT(11) DEFAULT 1
);

-- Tabel BUKU don
CREATE TABLE buku (
    BukuID INT AUTO_INCREMENT PRIMARY KEY,
    Judul VARCHAR(255) NOT NULL,
    Penulis VARCHAR(255) NOT NULL,
    Penerbit VARCHAR(255),
    TahunTerbit INT
);

-- Tabel KATEGORI don
CREATE TABLE kategoribuku (
    KategoriID INT AUTO_INCREMENT PRIMARY KEY,
    NamaKategori VARCHAR(255) NOT NULL
);

-- Tabel RELASI KATEGORI-BUKU (Many-to-Many) don
CREATE TABLE kategoribuku_relasi (
    KategoriBukuID INT AUTO_INCREMENT PRIMARY KEY,
    BukuID INT NOT NULL,
    KategoriID INT NOT NULL,
    FOREIGN KEY (BukuID) REFERENCES buku(BukuID) ON DELETE CASCADE,
    FOREIGN KEY (KategoriID) REFERENCES kategoribuku(KategoriID) ON DELETE CASCADE
);

-- Tabel KOLEKSI PRIBADI (buku favorit) don
CREATE TABLE koleksipribadi (
    KoleksiID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    BukuID INT NOT NULL,
    FOREIGN KEY (UserID) REFERENCES user(UserID) ON DELETE CASCADE,
    FOREIGN KEY (BukuID) REFERENCES buku(BukuID) ON DELETE CASCADE
);

-- Tabel PEMINJAMAN
CREATE TABLE peminjaman (
    PeminjamanID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    BukuID INT NOT NULL,
    TanggalPeminjaman DATE NOT NULL,
    TanggalPengembalian DATE,
    StatusPeminjaman VARCHAR(50),
    FOREIGN KEY (UserID) REFERENCES user(UserID) ON DELETE CASCADE,
    FOREIGN KEY (BukuID) REFERENCES buku(BukuID) ON DELETE CASCADE
);

-- Tabel ULASAN BUKU don
CREATE TABLE ulasanbuku (
    UlasanID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    BukuID INT NOT NULL,
    Ulasan TEXT,
    Rating INT,
    FOREIGN KEY (UserID) REFERENCES user(UserID) ON DELETE CASCADE,
    FOREIGN KEY (BukuID) REFERENCES buku(BukuID) ON DELETE CASCADE
);
INSERT INTO kategoribuku (NamaKategori) VALUES 
('Fiksi'),
('Non-Fiksi'),
('Biografi'),
('Teknologi'),
('Sains'),
('Sejarah'),
('Pendidikan'),
('Komik'),
('Agama'),
('Novel Remaja'),
('Psikologi'),
('Self Improvement'),
('Motivasi'),
('Bisnis'),
('Ekonomi'),
('Hukum'),
('Politik'),
('Sastra Indonesia'),
('Sastra Dunia'),
('Anak-anak'),
('Remaja'),
('Ensiklopedia'),
('Filsafat'),
('Petualangan'),
('Thriller'),
('Misteri'),
('Romantis'),
('Drama'),
('Fantasi'),
('Science Fiction'),
('Horor'),
('Kesehatan'),
('Kedokteran'),
('Pertanian'),
('Perikanan'),
('Teknik Sipil'),
('Arsitektur'),
('Desain Grafis'),
('Fotografi'),
('Bahasa Indonesia'),
('Bahasa Inggris'),
('Bahasa Asing Lain'),
('Kamus'),
('Majalah'),
('Jurnal Ilmiah'),
('Buku Sekolah Dasar'),
('Buku Sekolah Menengah'),
('Buku Kuliah'),
('Tutorial Programming'),
('Agama Islam'),
('Agama Kristen'),
('Agama Hindu'),
('Agama Buddha'),
('Agama Konghucu'),
('Cerita Rakyat'),
('Dongeng'),
('Puisi'),
('Drama Teater'),
('Buku Resep'),
('Buku Traveling'),
('Buku Otomotif'),
('Buku Musik'),
('Buku Seni'),
('Buku Kerajinan'),
('Buku Parenting'),
('Buku Digital'),
('Buku Audio'),
('Biologi'),
('Kimia'),
('Fisika'),
('Matematika'),
('Geografi'),
('Geologi'),
('Astronomi'),
('Lingkungan'),
('Ilmu Sosial'),
('Ilmu Komputer'),
('AI dan Machine Learning'),
('Cyber Security'),
('Cloud Computing'),
('Data Science'),
('Statistika'),
('Kewirausahaan'),
('Manajemen'),
('Perbankan'),
('Pajak'),
('Asuransi'),
('Budaya'),
('Antropologi'),
('Sosiologi'),
('Edukasi Seksualitas'),
('Gender dan Feminisme'),
('Disabilitas'),
('Kritik Sastra'),
('Sejarah Islam'),
('Teknologi Informasi'),
('Robotika'),
('Internet of Things (IoT)');
