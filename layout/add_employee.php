<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm nhân viên</title>
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
        form {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
        }
        input[type=text], input[type=number], select {
            width: 100%;
            padding: 8px 12px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type=submit] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type=submit]:hover {
            background-color: #45a049;
        }
        .alert {
            padding: 20px;
            background-color: #f44336;
            color: white;
            margin-bottom: 15px;
            display: none;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Thêm nhân viên</h2>
    <div class="alert" id="alertMessage"></div>
    <form id="employeeForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="ma_nv">Mã nhân viên:</label>
        <input type="text" id="ma_nv" name="ma_nv" required><br>

        <label for="ten_nv">Tên nhân viên:</label>
        <input type="text" id="ten_nv" name="ten_nv" required><br>

        <label for="phai">Giới tính:</label>
        <select id="phai" name="phai" required>
            <option value="NAM">Nam</option>
            <option value="NU">Nữ</option>
        </select><br>

        <label for="noi_sinh">Nơi sinh:</label>
        <input type="text" id="noi_sinh" name="noi_sinh" required><br>

        <label for="ma_phong">Mã phòng:</label>
        <input type="text" id="ma_phong" name="ma_phong" required><br>

        <label for="luong">Lương:</label>
        <input type="number" id="luong" name="luong" required><br>

        <input type="submit" value="Thêm">
    </form>
</div>

<script>
document.getElementById("employeeForm").addEventListener("submit", function(event) {
    event.preventDefault();
    var form = this;
    var maPhong = document.getElementById("ma_phong").value;

    // Kiểm tra xem mã phòng có tồn tại không
    // Trong trường hợp này, giả sử mã phòng tồn tại

    // Nếu mã phòng không tồn tại, hiển thị thông báo
    if (maPhong !== "TC" && maPhong !== "QT" && maPhong !== "KT") {
        var alertMessage = document.getElementById("alertMessage");
        alertMessage.innerHTML = "Mã phòng không tồn tại!";
        alertMessage.style.display = "block";
        return;
    }

    // Nếu mã phòng tồn tại, tiến hành submit form
    form.submit();
});
</script>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ql_nhansu";

    // Tạo kết nối
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Kết nối không thành công: " . $conn->connect_error);
    }

    // Lấy dữ liệu từ form
    $ma_nv = $_POST['ma_nv'];
    $ten_nv = $_POST['ten_nv'];
    $phai = $_POST['phai'];
    $noi_sinh = $_POST['noi_sinh'];
    $ma_phong = $_POST['ma_phong'];
    $luong = $_POST['luong'];

    // Kiểm tra xem mã phòng có tồn tại không
    if ($ma_phong !== "TC" && $ma_phong !== "QT" && $ma_phong !== "KT") {
        echo "<script>alert('Mã phòng không tồn tại!');</script>";
    } else {
        $sql = "INSERT INTO NhanVien (MA_NV, TEN_NV, PHAI, NOI_SINH, MA_PHONG, LUONG) 
                VALUES ('$ma_nv', '$ten_nv', '$phai', '$noi_sinh', '$ma_phong', '$luong')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Thêm nhân viên thành công!');</script>";
            // Đợi 3 giây rồi quay lại trang index.php
            echo "<script>setTimeout(function(){window.location.href='http://localhost/hphStudy/kiemtra/';}, 3000);</script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Đóng kết nối
    $conn->close();
}
?>
</body>
</html>
