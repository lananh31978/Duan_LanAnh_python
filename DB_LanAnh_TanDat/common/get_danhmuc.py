from ketnoidb.ketnoi_mysql import connect_mysql

def get_all_danhmuc():
    """Lấy danh sách tất cả danh mục"""
    conn = connect_mysql()
    if conn is None:
        print("❌ Không thể kết nối tới MySQL!")
        return []

    try:
        cursor = conn.cursor(dictionary=True)  # Trả về dict thay vì tuple
        sql = "SELECT * FROM danhmuc ORDER BY id ASC"
        cursor.execute(sql)

        result = cursor.fetchall()

        if len(result) == 0:
            print("⚠️ Chưa có danh mục nào trong CSDL.")
        else:
            print(f"✅ Có {len(result)} danh mục được tìm thấy:\n")
            for row in result:
                print(f"ID: {row['id']} | Tên: {row['ten_danh_muc']} | "
                      f"Mô tả: {row['mo_ta']} | Trạng thái: {row['trang_thai']} | Ngày tạo: {row['ngay_tao']}")
        return result

    except Exception as e:
        print("❌ Lỗi khi lấy danh sách danh mục:", e)
        return []

    finally:
        if conn.is_connected():
            cursor.close()
            conn.close()
            print("🔒 Đã đóng kết nối MySQL.")
