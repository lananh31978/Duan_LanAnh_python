-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 04, 2025 lúc 03:09 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `qlthuocankhang`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhmuc`
--

CREATE TABLE `danhmuc` (
  `id` int(11) NOT NULL,
  `ten_danh_muc` varchar(255) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `trang_thai` tinyint(4) DEFAULT 1,
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `danhmuc`
--

INSERT INTO `danhmuc` (`id`, `ten_danh_muc`, `mo_ta`, `trang_thai`, `ngay_tao`) VALUES
(1, 'Sản phẩm nổi bật', 'Các sản phẩm nổi bật', 1, '2025-11-04 07:36:12'),
(2, ' Thuốc', 'Thuốc đau đầu', 1, '2025-11-04 07:36:12'),
(3, 'Thực phẩm chức năng', 'Thực phẩm hỗ trợ sức khỏe', 1, '2025-11-04 07:36:12'),
(4, 'Thiết bị y tế', 'Dụng cụ, máy móc y tế', 1, '2025-11-04 07:36:12'),
(5, 'Dược mỹ phẩm', 'Mỹ phẩm có thành phần dược', 1, '2025-11-04 07:36:12'),
(6, 'Chăm sóc cá nhân', 'Sản phẩm vệ sinh cá nhân', 1, '2025-11-04 07:36:12'),
(7, 'Chăm sóc trẻ em', 'Sản phẩm dành cho trẻ nhỏ', 1, '2025-11-04 07:36:12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sanpham`
--

CREATE TABLE `sanpham` (
  `id` int(11) NOT NULL,
  `ten_san_pham` varchar(255) NOT NULL,
  `gia_goc` decimal(15,0) DEFAULT NULL,
  `gia_ban` decimal(15,0) NOT NULL,
  `dung_tich_hoac_quy_cach` varchar(50) DEFAULT NULL,
  `hinh_anh` varchar(255) DEFAULT NULL,
  `mo_ta` text DEFAULT NULL,
  `id_danh_muc` int(11) DEFAULT NULL,
  `trang_thai` tinyint(4) DEFAULT 1,
  `ngay_tao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sanpham`
--

INSERT INTO `sanpham` (`id`, `ten_san_pham`, `gia_goc`, `gia_ban`, `dung_tich_hoac_quy_cach`, `hinh_anh`, `mo_ta`, `id_danh_muc`, `trang_thai`, `ngay_tao`) VALUES
(1, 'Nước muối Vietrue sát khuẩn, súc miệng', 7000, 4900, '500ml', 'nuocmuoi.jpg', NULL, 6, 1, '2025-11-04 07:36:13'),
(2, 'Ensure Gold 800g', 932000, 845000, '800g', 'ensuregold.jpg', NULL, 3, 1, '2025-11-04 07:36:13'),
(3, 'Sữa bột Anlene Gold hương Vani', 555000, 480000, '800g', 'anlenegold.jpg', NULL, 3, 1, '2025-11-04 07:36:13'),
(4, 'Costar Omega 3', 972000, 729000, 'Lọ 365 viên', 'omega3.jpg', NULL, 3, 1, '2025-11-04 07:36:13'),
(5, 'Sắc Ngọc Khang', 666000, 532800, 'Hộp 180 viên', 'sacngockhang.jpg', NULL, 5, 1, '2025-11-04 07:36:13');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `danhmuc`
--
ALTER TABLE `danhmuc`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_danh_muc` (`id_danh_muc`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `danhmuc`
--
ALTER TABLE `danhmuc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `sanpham_ibfk_1` FOREIGN KEY (`id_danh_muc`) REFERENCES `danhmuc` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
