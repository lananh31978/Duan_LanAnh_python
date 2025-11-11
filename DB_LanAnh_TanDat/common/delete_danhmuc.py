from ketnoidb.ketnoi_mysql import connect_mysql

def delete_danhmuc(id_danhmuc):
    """Xóa 1 danh mục theo ID"""
    conn = connect_mysql()
    if conn is None:
        print("❌ Không thể kết nối tới MySQL!")
        return

    try:
        cursor = conn.cursor()

        # Trước khi xóa, kiểm tra ID có tồn tại hay không
        check_sql = "SELECT * FROM danhmuc WHERE id = %s"
        cursor.execute(check_sql, (id_danhmuc,))
        record = cursor.fetchone()

        if record is None:
            print(f"⚠️ Không tìm thấy danh mục có id = {id_danhmuc}")
            return

        # Xóa danh mục
        sql = "DELETE FROM danhmuc WHERE id = %s"
        cursor.execute(sql, (id_danhmuc,))
        conn.commit()

        print(f"✅ Đã xóa danh mục có id = {id_danhmuc}")

    except Exception as e:
        print("❌ Lỗi khi xóa danh mục:", e)

    finally:
        if conn.is_connected():
            cursor.close()
            conn.close()
            print("🔒 Đã đóng kết nối MySQL.")
