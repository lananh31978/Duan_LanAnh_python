from common.delete_danhmuc import delete_danhmuc

while True:
    ma=input("Nhập vào mã danh mục cần xóa")
    delete_danhmuc(ma)
    con=input("TIẾP TỤC y, THOÁT THÌ NHẤN KÝ TỰ BẤT KỲ")
    if con!="y":
        break
