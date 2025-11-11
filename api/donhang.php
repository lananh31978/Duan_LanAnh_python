<?php
include '../db.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (empty($data->items) || !isset($data->manv)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Dữ liệu không hợp lệ.']);
        return;
    }

    $manv = $data->manv;
    $items = $data->items;
    $total_price = 0;

    foreach ($items as $item) {
        $total_price += $item->gia * $item->sl;
    }

    $conn->begin_transaction();
    try {
        $stmt_donhang = $conn->prepare("INSERT INTO donhang (ngaytao, giamgia, manv) VALUES (NOW(), ?, ?)");
        $stmt_donhang->bind_param("di", $total_price, $manv);
        $stmt_donhang->execute();
        $order_id = $stmt_donhang->insert_id;

        $stmt_chitiet = $conn->prepare("INSERT INTO chitietdh (sodh, masp, sl, gia) VALUES (?, ?, ?, ?)");
        foreach ($items as $item) {
            $stmt_chitiet->bind_param("iiid", $order_id, $item->masp, $item->sl, $item->gia);
            $stmt_chitiet->execute();
        }

        $conn->commit();
        echo json_encode(['status' => 'success', 'message' => 'Tạo đơn hàng thành công!', 'order_id' => $order_id]);
    } catch (mysqli_sql_exception $exception) {
        $conn->rollback();
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Lỗi máy chủ: ' . $exception->getMessage()]);
    }
} else {
    $sql = "SELECT dh.sodh, dh.ngaytao, dh.giamgia as total, nv.hoten FROM donhang dh JOIN nhanvien nv ON dh.manv = nv.manv ORDER BY dh.ngaytao DESC";
    $result = $conn->query($sql);
    $orders = [];
    while($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    echo json_encode($orders);
}

$conn->close();
?>