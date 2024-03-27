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
        input[type="text"], select {
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

<div class="container">
    <h2>Chỉnh sửa thông tin nhân viên</h2>
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

    // Truy vấn SQL để lấy thông tin nhân viên và danh sách phòng ban
    $sql_employee = "SELECT nv.MA_NV, nv.TEN_NV, nv.PHAI, nv.NOI_SINH, nv.MA_PHONG, pb.Ten_Phong, nv.LUONG
            FROM NhanVien nv
            JOIN PhongBan pb ON nv.MA_PHONG = pb.Ma_Phong
            WHERE nv.MA_NV = '$employee_id'";
    $result_employee = $conn->query($sql_employee);

    $sql_departments = "SELECT * FROM PhongBan";
    $result_departments = $conn->query($sql_departments);

    if ($result_employee->num_rows > 0 && $result_departments->num_rows > 0) {
        $row_employee = $result_employee->fetch_assoc();
?>
        <form action="" method="POST">
            <input type="hidden" name="employee_id" value="<?php echo $row_employee['MA_NV']; ?>">
            <label for="employee_name">Tên nhân viên:</label>
            <input type="text" id="employee_name" name="employee_name" value="<?php echo $row_employee['TEN_NV']; ?>" disabled><br>
            <label for="employee_gender">Giới tính:</label>
            <input type="text" id="employee_gender" name="employee_gender" value="<?php echo $row_employee['PHAI']; ?>" disabled><br>
            <label for="employee_birthplace">Nơi sinh:</label>
            <input type="text" id="employee_birthplace" name="employee_birthplace" value="<?php echo $row_employee['NOI_SINH']; ?>" disabled><br>
            <label for="employee_department">Phòng ban:</label>
            <select id="employee_department" name="employee_department">
                <?php
                    while($row_department = $result_departments->fetch_assoc()) {
                        $selected = ($row_department['Ma_Phong'] == $row_employee['MA_PHONG']) ? 'selected' : '';
                        echo "<option value='{$row_department['Ma_Phong']}' $selected>{$row_department['Ten_Phong']}</option>";
                    }
                ?>
            </select><br>
            <label for="employee_salary">Lương:</label>
            <input type="text" id="employee_salary" name="employee_salary" value="<?php echo $row_employee['LUONG']; ?>"><br>
            <input type="submit" value="Lưu thay đổi">
        </form>
<?php
        // Xử lý cập nhật thông tin nhân viên
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if(isset($_POST['employee_id']) && isset($_POST['employee_department']) && isset($_POST['employee_salary'])) {
                $employee_id = $_POST['employee_id'];
                $department = $_POST['employee_department'];
                $salary = $_POST['employee_salary'];

                // Cập nhật thông tin nhân viên vào CSDL
                $sql_update = "UPDATE NhanVien SET MA_PHONG = '$department', LUONG = '$salary' WHERE MA_NV = '$employee_id'";
                if ($conn->query($sql_update) === TRUE) {
                    echo "<p style='color: green;'>Cập nhật thông tin nhân viên thành công!</p>";
                    echo "<script>setTimeout(function(){ window.location.href = 'http://localhost/hphStudy/kiemtra/'; }, 3000);</script>";
                } else {
                    echo "<p style='color: red;'>Lỗi khi cập nhật thông tin nhân viên: " . $conn->error . "</p>";
                }
            } else {
                echo "<p style='color: red;'>Dữ liệu không hợp lệ</p>";
            }
        }
    } else {
        echo "<p style='color: red;'>Không tìm thấy thông tin nhân viên hoặc danh sách phòng ban</p>";
    }

    // Đóng kết nối
    $conn->close();
} else {
    echo "<p style='color: red;'>Không có ID nhân viên được cung cấp</p>";
}
?>

</div>

</body>
</html>
