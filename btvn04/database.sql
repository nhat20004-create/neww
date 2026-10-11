CREATE TABLE IF NOT EXISTS sanpham (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_san_pham VARCHAR(255) NOT NULL,
    gia DECIMAL(12,2) NOT NULL,
    so_luong INT NOT NULL
);

INSERT INTO sanpham (ten_san_pham, gia, so_luong)
VALUES
('Kính cận', 250000, 10),
('Kính râm', 300000, 15),
('Kính thời trang', 350000, 8),
('Kính chống ánh sáng xanh', 450000, 12);
