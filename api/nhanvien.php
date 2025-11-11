<?php
include '../db.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'POST' && isset($_POST['_method'])) {
    $method = strtoupper($_POST['_method']);
}

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $stmt = $conn->prepare("SELECT * FROM nhanvien WHERE manv = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $staff = $result->fetch_assoc();
            if ($staff) {
                echo json_encode($staff);
            } else {
                http_response_code(404);
                echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy nhân viên.']);
            }
        } else {
            $result = $conn->query("SELECT manv, hoten, gt, ngayvl FROM nhanvien ORDER BY manv DESC");
            $staff_list = [];
            while($row = $result->fetch_assoc()) {
                $staff_list[] = $row;
            }
            echo json_encode($staff_list);
        }
        break;

    case 'POST':
        $hoten = $_POST['hoten'];
        $gt = $_POST['gt'];
        $ns = $_POST['ns'];
        $ngayvl = $_POST['ngayvl'];
        
        $stmt = $conn->prepare("INSERT INTO nhanvien (hoten, gt, ns, ngayvl) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $hoten, $gt, $ns, $ngayvl);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Thêm nhân viên thành công.', 'manv' => $stmt->insert_id]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Lỗi khi thêm nhân viên.']);
        }
        break;

    case 'PUT':
        $id = $_GET['id'];
        parse_str(file_get_contents("php://input"), $put_vars);
        
        $hoten = $put_vars['hoten'];
        $gt = $put_vars['gt'];
        $ns = $put_vars['ns'];
        $ngayvl = $put_vars['ngayvl'];

        $stmt = $conn->prepare("UPDATE nhanvien SET hoten = ?, gt = ?, ns = ?, ngayvl = ? WHERE manv = ?");
        $stmt->bind_param("ssssi", $hoten, $gt, $ns, $ngayvl, $id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Cập nhật thông tin nhân viên thành công.']);
        } else {
             http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Lỗi khi cập nhật thông tin.']);
        }
        break;

    case 'DELETE':
        $id = $_GET['id'];
        
        $stmt = $conn->prepare("DELETE FROM nhanvien WHERE manv = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                 echo json_encode(['status' => 'success', 'message' => 'Nhân viên đã được xóa.']);
            } else {
                http_response_code(404);
                echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy nhân viên để xóa.']);
            }
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Không thể xóa nhân viên này. Có thể nhân viên đã có dữ liệu liên quan.']);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['message' => 'Phương thức không được hỗ trợ']);
        break;
}

$conn->close();
?>