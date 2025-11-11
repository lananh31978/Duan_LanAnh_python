$(document).ready(() => {
  const sidebar = $(".sidebar");
  const header = $(".header");
  const main = $(".main-content");

  $(".menu-toggle").click(() => {
    sidebar.toggleClass("show");
    header.toggleClass("full");
    main.toggleClass("full");
  });

  const page = window.location.pathname.split("/").pop();
  switch (page) {
    case "sanpham.html": loadSanPham(); break;
    case "danhmuc.html": loadDanhMuc(); break;
    case "donhang.html": loadDonHang(); break;
    case "nhanvien.html": loadNhanVien(); break;
  }
});

const API = "http://localhost/qlithuocankhangca1/api/";

function loadSanPham() {
  $.getJSON(API + "sanpham.php", data => {
    let rows = "";
    data.forEach(sp => {
      rows += `
        <tr>
          <td>${sp.id}</td>
          <td>${sp.ten_sp}</td>
          <td>${Number(sp.gia).toLocaleString()} ₫</td>
          <td>${sp.so_luong}</td>
          <td>${sp.ten_danh_muc}</td>
        </tr>`;
    });
    $("#tbodySanPham").html(rows);
  });
}

function loadDanhMuc() {
  $.getJSON(API + "danhmuc.php", data => {
    let rows = "";
    data.forEach(dm => {
      rows += `<tr><td>${dm.id}</td><td>${dm.ten_danh_muc}</td></tr>`;
    });
    $("#tbodyDanhMuc").html(rows);
  });
}

function loadDonHang() {
  $.getJSON(API + "donhang.php", data => {
    let rows = "";
    data.forEach(dh => {
      rows += `
        <tr>
          <td>${dh.id}</td>
          <td>${dh.ten_khach}</td>
          <td>${dh.tong_tien.toLocaleString()} ₫</td>
          <td>${dh.trang_thai}</td>
        </tr>`;
    });
    $("#tbodyDonHang").html(rows);
  });
}

function loadNhanVien() {
  $.getJSON(API + "nhanvien.php", data => {
    let rows = "";
    data.forEach(nv => {
      rows += `
        <tr>
          <td>${nv.id}</td>
          <td>${nv.ten_nv}</td>
          <td>${nv.chuc_vu}</td>
          <td>${nv.email}</td>
        </tr>`;
    });
    $("#tbodyNhanVien").html(rows);
  });
}
