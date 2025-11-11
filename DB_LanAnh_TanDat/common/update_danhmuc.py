from ketnoidb.ketnoi_mysql import connect_mysql

def update_danhmuc(id_danhmuc, ten_danh_muc=None, mo_ta=None, trang_thai=None):
    """Cập nhật thông tin danh mục theo ID"""
    conn = connect_mysql()
    if conn is None:
        print("❌ Không thể kết nối tới MySQL!")
        return

    try:
        cursor = conn.cursor()

        # Kiểm tra danh mục có tồn tại không
        cursor.execute("SELECT * FROM danhmuc WHERE id = %s", (id_danhmuc,))
        record = cursor.fetchone()
        if record is None:
            print(f"⚠️ Không tìm thấy danh mục có id = {id_danhmuc}")
            return

        # Xây dựng câu lệnh UPDATE linh hoạt
        fields = []
        values = []

        if ten_danh_muc is not None:
            fields.append("ten_danh_muc = %s")
            values.append(ten_danh_muc)
        if mo_ta is not None:
            fields.append("mo_ta = %s")
            values.append(mo_ta)
        if trang_thai is not None:
            fields.append("trang_thai = %s")
            values.append(trang_thai)

        # Nếu không có gì để cập nhật
        if not fields:
            print("⚠️ Không có trường nào để cập nhật.")
            return

        # Tạo câu SQL
        sql = f"UPDATE danhmuc SET {', '.join(fields)} WHERE id = %s"
        values.append(id_danhmuc)

        # Thực thi
        cursor.execute(sql, tuple(values))
        conn.commit()

        print(f"✅ Đã cập nhật danh mục ID = {id_danhmuc}")

    except Exception as e:
        print("❌ Lỗi khi cập nhật danh mục:", e)

    finally:
        if conn.is_connected():
            cursor.close()
            conn.close()
            print("🔒 Đã đóng kết nối MySQL.")
