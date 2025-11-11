import tkinter as tk
from tkinter import ttk, messagebox
from ketnoidb.ketnoi_mysql import connect_mysql

# ==========================================
# CÁC HÀM LÀM VIỆC VỚI MYSQL
# ==========================================

def load_data():
    for i in tree.get_children():
        tree.delete(i)

    conn = connect_mysql()
    if conn:
        cursor = conn.cursor()
        cursor.execute("SELECT id, ten_danh_muc, mo_ta, trang_thai, ngay_tao FROM danhmuc ORDER BY id ASC")
        for row in cursor.fetchall():
            tree.insert("", tk.END, values=row)
        cursor.close()
        conn.close()

def add_danhmuc():
    ten = entry_ten.get()
    mota = entry_mota.get("1.0", tk.END).strip()
    if ten == "":
        messagebox.showwarning("Thiếu thông tin", "Vui lòng nhập tên danh mục.")
        return

    conn = connect_mysql()
    cursor = conn.cursor()
    cursor.execute("INSERT INTO danhmuc (ten_danh_muc, mo_ta) VALUES (%s, %s)", (ten, mota))
    conn.commit()
    conn.close()
    messagebox.showinfo("Thành công", "Đã thêm danh mục!")
    clear_fields()
    load_data()

def delete_danhmuc():
    selected = tree.selection()
    if not selected:
        messagebox.showwarning("Chọn dòng", "Vui lòng chọn danh mục cần xóa.")
        return

    id_val = tree.item(selected[0])['values'][0]

    conn = connect_mysql()
    cursor = conn.cursor()
    cursor.execute("DELETE FROM danhmuc WHERE id = %s", (id_val,))
    conn.commit()
    conn.close()
    messagebox.showinfo("Đã xóa", "Danh mục đã được xóa.")
    load_data()

def update_danhmuc():
    selected = tree.selection()
    if not selected:
        messagebox.showwarning("Chọn dòng", "Vui lòng chọn danh mục cần sửa.")
        return

    id_val = tree.item(selected[0])['values'][0]
    ten = entry_ten.get()
    mota = entry_mota.get("1.0", tk.END).strip()
    if ten == "":
        messagebox.showwarning("Thiếu thông tin", "Vui lòng nhập tên danh mục.")
        return

    conn = connect_mysql()
    cursor = conn.cursor()
    cursor.execute("UPDATE danhmuc SET ten_danh_muc=%s, mo_ta=%s WHERE id=%s", (ten, mota, id_val))
    conn.commit()
    conn.close()
    messagebox.showinfo("Cập nhật", "Danh mục đã được cập nhật!")
    clear_fields()
    load_data()

def clear_fields():
    entry_ten.delete(0, tk.END)
    entry_mota.delete("1.0", tk.END)
    #tree.selection_remove(tree.selection())

def select_item(event):
    selected = tree.selection()
    if not selected:
        return
    item = tree.item(selected[0])
    values = item['values']
    clear_fields()
    entry_ten.insert(0, values[1])
    entry_mota.insert(tk.END, values[2])

# ==========================================
# GIAO DIỆN TKINTER
# ==========================================

root = tk.Tk()
root.title("Quản lý Danh Mục")
root.geometry("800x600")

# ---- Khung nhập liệu ----
frame_input = tk.Frame(root)
frame_input.pack(pady=10)

tk.Label(frame_input, text="Tên danh mục:").grid(row=0, column=0, padx=5, pady=5)
entry_ten = tk.Entry(frame_input, width=40)
entry_ten.grid(row=0, column=1, padx=5, pady=5)

tk.Label(frame_input, text="Mô tả:").grid(row=1, column=0, padx=5, pady=5, sticky='n')
entry_mota = tk.Text(frame_input, width=40, height=4)
entry_mota.grid(row=1, column=1, padx=5, pady=5)

# ---- Nút chức năng ----
frame_buttons = tk.Frame(root)
frame_buttons.pack(pady=10)

tk.Button(frame_buttons, text="Thêm", width=12, command=add_danhmuc).grid(row=0, column=0, padx=5)
tk.Button(frame_buttons, text="Sửa", width=12, command=update_danhmuc).grid(row=0, column=1, padx=5)
tk.Button(frame_buttons, text="Xóa", width=12, command=delete_danhmuc).grid(row=0, column=2, padx=5)
tk.Button(frame_buttons, text="Làm mới", width=12, command=clear_fields).grid(row=0, column=3, padx=5)

# ---- Bảng Treeview ----
cols = ("ID", "Tên danh mục", "Mô tả", "Trạng thái", "Ngày tạo")
tree = ttk.Treeview(root, columns=cols, show="headings", height=15)
for col in cols:
    tree.heading(col, text=col)
    tree.column(col, width=150)

tree.pack(fill=tk.BOTH, expand=True, padx=10, pady=10)
tree.bind("<<TreeviewSelect>>", select_item)

load_data()

root.mainloop()
