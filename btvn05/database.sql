CREATE DATABASE IF NOT EXISTS tiemtrasua
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE tiemtrasua;

CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    ho_ten VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS mon (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_mon VARCHAR(255) NOT NULL,
    gia DECIMAL(12,2) NOT NULL,
    mo_ta VARCHAR(255) DEFAULT '',
    danh_muc VARCHAR(100) NOT NULL DEFAULT 'Trà sữa',
    trang_thai TINYINT NOT NULL DEFAULT 1,
    hinh_anh VARCHAR(255) NOT NULL DEFAULT ''
);

CREATE TABLE IF NOT EXISTS don_hang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ten_khach VARCHAR(100) NOT NULL,
    so_dien_thoai VARCHAR(20) NOT NULL,
    dia_chi VARCHAR(255) NOT NULL,
    ghi_chu VARCHAR(255) DEFAULT '',
    tong_tien DECIMAL(12,2) NOT NULL DEFAULT 0,
    trang_thai VARCHAR(50) NOT NULL DEFAULT 'Chờ xác nhận',
    ngay_dat DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS chi_tiet_don (
    id INT AUTO_INCREMENT PRIMARY KEY,
    don_hang_id INT NOT NULL,
    ten_mon VARCHAR(255) NOT NULL,
    don_gia DECIMAL(12,2) NOT NULL,
    so_luong INT NOT NULL,
    thanh_tien DECIMAL(12,2) NOT NULL,
    size VARCHAR(10) NOT NULL DEFAULT 'M',
    topping VARCHAR(255) NOT NULL DEFAULT '',
    FOREIGN KEY (don_hang_id) REFERENCES don_hang(id) ON DELETE CASCADE
);

INSERT INTO `admin` (username, password, ho_ten)
VALUES (
    'admin',
    'admin123',
    'Chủ tiệm'
);

INSERT INTO mon (ten_mon, gia, mo_ta, danh_muc, trang_thai, hinh_anh)
VALUES
    ('Trà sữa trân châu đường đen', 35000, 'Trà sữa đặc, trân châu dai, đường đen thơm', 'Trà sữa', 1, 'trasuachanchauduongden.png'),
    ('Trà sữa matcha', 38000, 'Matcha Nhật, sữa tươi, ít đường', 'Trà sữa', 1, 'matcha.png'),
    ('Trà sữa khoai môn', 37000, 'Khoai môn béo, topping trân châu', 'Trà sữa', 1, 'trasuakhoaimon.png'),
    ('Trà đào cam sả', 39000, 'Đào miếng, cam tươi, sả thơm', 'Trà trái cây', 1, 'tradaocamsa.png'),
    ('Trà vải lài', 36000, 'Vải tươi, trà lài thanh mát', 'Trà trái cây', 1, 'travailai.png'),
    ('Hồng trà sữa kem cheese', 42000, 'Kem cheese mặn ngọt, hồng trà đậm', 'Trà sữa', 1, 'hongtrakemcheese.png'),
    ('Sữa tươi trân châu đường đen', 40000, 'Sữa tươi không trà, đường đen', 'Sữa tươi', 1, 'suatuoichanchauduongden.png'),
    ('Trà sữa oolong', 34000, 'Oolong rang nhẹ, vị thanh', 'Trà sữa', 1, 'trasuaolong.png'),
    ('Trà chanh mật ong', 32000, 'Chanh tươi, mật ong, mát lạnh', 'Trà trái cây', 1, 'trachanhmatong.png'),
    ('Trà dâu tây', 38000, 'Dâu tươi, trà thanh, vị chua ngọt', 'Trà trái cây', 1, 'tradautay.png'),
    ('Trà xoài nhiệt đới', 39000, 'Xoài chín, trà xanh, hương nhiệt đới', 'Trà trái cây', 1, 'traxoainhietdoi.png');

INSERT INTO don_hang (ten_khach, so_dien_thoai, dia_chi, ghi_chu, tong_tien, trang_thai, ngay_dat)
VALUES
    ('Nguyễn An', '0901234567', '12 Nguyễn Huệ', 'Ít đá, 50% đường', 80000, 'Hoàn thành', DATE_SUB(NOW(), INTERVAL 10 DAY)),
    ('Trần Bình', '0912345678', '45 Lê Lợi', '', 90000, 'Hoàn thành', DATE_SUB(NOW(), INTERVAL 3 DAY)),
    ('Lê Chi', '0901234567', '12 Nguyễn Huệ', 'Mang về', 44000, 'Đang làm', NOW());

INSERT INTO chi_tiet_don (don_hang_id, ten_mon, don_gia, so_luong, thanh_tien, size, topping)
VALUES
    (1, 'Trà sữa trân châu đường đen', 40000, 2, 80000, 'M', 'Trân châu đen'),
    (2, 'Sữa tươi trân châu đường đen', 45000, 2, 90000, 'M', 'Trân châu đen'),
    (3, 'Trà đào cam sả', 44000, 1, 44000, 'S', 'Thạch dừa');
