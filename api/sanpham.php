<?php
include '../db.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $stmt = $conn->prepare("SELECT sp.*, dm.tendm, dv.tendv FROM sanpham sp JOIN danhmuc dm ON sp.madm = dm.madm JOIN donvitinh dv ON sp.madv = dv.madv WHERE masp = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();
            echo json_encode($product);
        } else {
            $sql = "SELECT sp.masp, sp.tensp, sp.giaban, sp.hinhsp, dm.tendm FROM sanpham sp JOIN danhmuc dm ON sp.madm = dm.madm ORDER BY sp.masp DESC";
            $result = $conn->query($sql);
            $products = array();
            while($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
            echo json_encode($products);
        }
        break;
    
    case 'DELETE':
        parse_str(file_get_contents("php://input"), $_DELETE);
        $id = $_GET['id'];
        
        $stmt = $conn->prepare("DELETE FROM sanpham WHERE masp = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Sản phẩm đã được xóa.']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Lỗi khi xóa sản phẩm.']);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}

$conn->close();
?>