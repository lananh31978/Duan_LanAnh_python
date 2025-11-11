import mysql.connector
from mysql.connector import Error

def connect_mysql():
    """Hàm kết nối MySQL và trả về đối tượng connection"""
    try:
        connection = mysql.connector.connect(
            host='localhost',
            database='qlthuocankhang',
            user='root',
            password=''
        )

        if connection.is_connected():
            print("✅ Kết nối MySQL thành công!")
            db_info = connection.get_server_info()
            print("👉 Phiên bản MySQL:", db_info)
            return connection

    except Error as e:
        print("❌ Lỗi khi kết nối MySQL:", e)
        return None
