<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa thông tin nhân viên</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: auto;
            padding-top: 20px;
        }
        label {
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<?php
    // Kiểm tra nếu có ID nhân viên được chọn
    if(isset($_GET['id'])) {
        $employee_id = $_GET['id'];

        // Kết nối CSDL
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "ql_nhansu";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Kiểm tra kết nối
        if ($conn->connect_error) {
            die("Kết nối không thành công: " . $conn->connect_error);
        }

        // Truy vấn SQL để lấy thông tin nhân viên
        $sql = "SELECT nv.MA_NV, nv.TEN_NV, nv.PHAI, nv.NOI_SINH, pb.Ten_Phong, nv.LUONG
                FROM NhanVien nv
                JOIN PhongBan pb ON nv.MA_PHONG = pb.Ma_Phong
                WHERE nv.MA_NV = '$employee_id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            ?>
            <div class="container">
                <h2>Chỉnh sửa thông tin nhân viên</h2>
                <form action="update_employee.php" method="POST">
                    <input type="hidden" name="employee_id" value="<?php echo $row['MA_NV']; ?>">
                    <label for="employee_name">Tên nhân viên:</label>
                    <input type="text" id="employee_name" name="employee_name" value="<?php echo $row['TEN_NV']; ?>" disabled><br>
                    <label for="employee_gender">Giới tính:</label>
                    <input type="text" id="employee_gender" name="employee_gender" value="<?php echo $row['PHAI']; ?>" disabled><br>
                    <label for="employee_birthplace">Nơi sinh:</label>
                    <input type="text" id="employee_birthplace" name="employee_birthplace" value="<?php echo $row['NOI_SINH']; ?>" disabled><br>
                    <label for="employee_department">Phòng ban:</label>
                    <input type="text" id="employee_department" name="employee_department" value="<?php echo $row['Ten_Phong']; ?>"><br>
                    <label for="employee_salary">Lương:</label>
                    <input type="text" id="employee_salary" name="employee_salary" value="<?php echo $row['LUONG']; ?>"><br>
                    <input type="submit" value="Lưu thay đổi">
                </form>
            </div>
            <?php
        } else {
            echo "Không tìm thấy thông tin nhân viên";
        }

        // Đóng kết nối
        $conn->close();
    } else {
        echo "Không có ID nhân viên được cung cấp";
    }
?>
<?php
    // Kiểm tra xem có dữ liệu được gửi từ form không
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Kiểm tra xem có dữ liệu nhân viên và mức lương được gửi không
        if(isset($_POST['employee_id']) && isset($_POST['employee_department']) && isset($_POST['employee_salary'])) {
            $employee_id = $_POST['employee_id'];
            $department = $_POST['employee_department'];
            $salary = $_POST['employee_salary'];

            // Kết nối CSDL
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "ql_nhansu";

            $conn = new mysqli($servername, $username, $password, $dbname);

            // Kiểm tra kết nối
            if ($conn->connect_error) {
                die("Kết nối không thành công: " . $conn->connect_error);
            }

            // Cập nhật thông tin nhân viên vào CSDL
            $sql_update = "UPDATE NhanVien SET MA_PHONG = '$department', LUONG = '$salary' WHERE MA_NV = '$employee_id'";
            if ($conn->query($sql_update) === TRUE) {
                echo "<script>alert('Cập nhật thông tin nhân viên thành công!');</script>";
                echo "<script>window.location.href = 'index.php';</script>"; // Chuyển hướng về trang chủ sau khi cập nhật thành công
                exit;
            } else {
                echo "Lỗi khi cập nhật thông tin nhân viên: " . $conn->error;
            }

            // Đóng kết nối
            $conn->close();
        } else {
            echo "Dữ liệu không hợp lệ";
        }
    }
?>

</body>
</html>
