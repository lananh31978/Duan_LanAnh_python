from ketnoidb.ketnoi_mysql import connect_mysql

def insert_danhmuc(ten_danh_muc, mo_ta):
    """Hàm thêm một danh mục mới vào bảng danhmuc"""
    conn = connect_mysql()
    if conn is None:
        print("❌ Không thể kết nối tới MySQL!")
        return

    try:
        cursor = conn.cursor()
        sql = "INSERT INTO danhmuc (ten_danh_muc, mo_ta) VALUES (%s, %s)"
        val = (ten_danh_muc, mo_ta)
        cursor.execute(sql, val)
        conn.commit()  # lưu thay đổi vào DB

        print(f"✅ Đã thêm danh mục mới: {ten_danh_muc}")

    except Exception as e:
        print("❌ Lỗi khi thêm danh mục:", e)

    finally:
        if conn.is_connected():
            cursor.close()
            conn.close()
            print("🔒 Đã đóng kết nối MySQL.")
