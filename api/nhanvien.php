<?php
include '../db.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'POST' && isset($_POST['_method'])) {
    $method = strtoupper($_POST['_method']);
}

// Hàm kiểm tra ID hợp lệ
function validate_id($id) {
    return isset($id) && is_numeric($id);
}

// Lấy dữ liệu từ PUT/DELETE
parse_str(file_get_contents("php://input"), $input);

switch ($method) {
    case 'GET':
        if (isset($_GET['id']) && validate_id($_GET['id'])) {
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
            $result = $conn->query("SELECT manv, hoten, gt, ngaysinh, ngayvl FROM nhanvien ORDER BY manv DESC");
            $staff_list = [];
            while($row = $result->fetch_assoc()) {
                $staff_list[] = $row;
            }
            echo json_encode($staff_list);
        }
        break;

    case 'POST':
        $hoten = trim($_POST['hoten'] ?? '');
        $gt = trim($_POST['gt'] ?? '');
        $ngaysinh = trim($_POST['ngaysinh'] ?? '');
        $ngayvl = trim($_POST['ngayvl'] ?? '');

        // Kiểm tra dữ liệu bắt buộc
        if (empty($hoten) || empty($ngaysinh) || empty($ngayvl)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Tên, ngày sinh và ngày vào làm không được để trống']);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO nhanvien (hoten, gt, ngaysinh, ngayvl) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $hoten, $gt, $ngaysinh, $ngayvl);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Thêm nhân viên thành công.', 'manv' => $stmt->insert_id]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Lỗi khi thêm nhân viên.']);
        }
        break;

    case 'PUT':
        if (!validate_id($_GET['id'] ?? null)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ID không hợp lệ']);
            exit;
        }

        $id = $_GET['id'];
        $hoten = trim($input['hoten'] ?? '');
        $gt = trim($input['gt'] ?? '');
        $ngaysinh = trim($input['ngaysinh'] ?? '');
        $ngayvl = trim($input['ngayvl'] ?? '');

        if (empty($hoten) || empty($ngaysinh) || empty($ngayvl)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Tên, ngày sinh và ngày vào làm không được để trống']);
            exit;
        }

        $stmt = $conn->prepare("UPDATE nhanvien SET hoten = ?, gt = ?, ngaysinh = ?, ngayvl = ? WHERE manv = ?");
        $stmt->bind_param("ssssi", $hoten, $gt, $ngaysinh, $ngayvl, $id);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Cập nhật thông tin nhân viên thành công.']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Lỗi khi cập nhật thông tin.']);
        }
        break;

    case 'DELETE':
        if (!validate_id($_GET['id'] ?? null)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'ID không hợp lệ']);
            exit;
        }

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
        echo json_encode(['status' => 'error', 'message' => 'Phương thức không được hỗ trợ']);
        break;
}

$conn->close();
?>
