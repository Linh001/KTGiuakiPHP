<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin nhân viên</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            padding-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .add-button {
            float: right;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .add-button:hover {
            background-color: #45a049;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination a {
            text-decoration: none;
            display: inline-block;
            padding: 8px 16px;
            border: 1px solid #ddd;
            margin: 0 4px;
            background-color: #f2f2f2;
            color: black;
            border-radius: 5px;
        }
        .pagination a.active {
            background-color: #4CAF50;
            color: white;
            border: 1px solid #4CAF50;
        }
        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Thông tin nhân viên</h2>

    <button class="add-button" onclick="window.location.href='http://localhost/hphStudy/kiemtra/layout/add_employee.php'">Thêm nhân viên</button>

    <table>
        <tr>
            <th>Mã nhân viên</th>
            <th>Tên nhân viên</th>
            <th>Giới tính</th>
            <th>Nơi sinh</th>
            <th>Tên phòng</th>
            <th>Lương</th>
            <th>Action</th>
        </tr>

        <?php
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

        // Phân trang
        $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $records_per_page = 5;
        $offset = ($current_page - 1) * $records_per_page;

        // Truy vấn SQL để lấy thông tin nhân viên và phòng ban
        $sql = "SELECT nv.MA_NV, nv.TEN_NV, nv.PHAI, nv.NOI_SINH, pb.Ten_Phong, nv.LUONG
                FROM NhanVien nv
                JOIN PhongBan pb ON nv.MA_PHONG = pb.Ma_Phong
                LIMIT $offset, $records_per_page";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Hiển thị thông tin của mỗi nhân viên trong bảng
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["MA_NV"] . "</td>";
                echo "<td>" . $row["TEN_NV"] . "</td>";
                if ($row["PHAI"] == "NAM") {
                    echo "<td><img src='http://localhost/hphStudy/kiemtra/image/male.jpg' alt='Male' width='70'></td>";
                } else if ($row["PHAI"] == "NU") {
                    echo "<td><img src='http://localhost/hphStudy/kiemtra/image/female.jpg' alt='Female' width='70'></td>";
                }
                echo "<td>" . $row["NOI_SINH"] . "</td>";
                echo "<td>" . $row["Ten_Phong"] . "</td>";
                echo "<td>" . $row["LUONG"] . "</td>";
                echo "<td>";
                echo "<a href='http://localhost/hphStudy/kiemtra/layout/edit_employee.php?id=" . $row["MA_NV"] . "'><img src='http://localhost/hphStudy/kiemtra/image/edit_icon.png' alt='Edit' width='20'></a>";
                echo "<a href='http://localhost/hphStudy/kiemtra/layout/delete_employee.php?id=" . $row["MA_NV"] . "'><img src='http://localhost/hphStudy/kiemtra/image/delete_icon.png' alt='Delete' width='20'></a>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>Không có nhân viên nào</td></tr>";
        }

        // Tính toán số lượng trang và tạo liên kết phân trang
        $sql_total_records = "SELECT COUNT(*) AS total FROM NhanVien";
        $result_total_records = $conn->query($sql_total_records);
        $row_total_records = $result_total_records->fetch_assoc();
        $total_records = $row_total_records['total'];
        $total_pages = ceil($total_records / $records_per_page);

        echo "</table>";

        // Phân trang
        echo "<div class='pagination'>";
        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $current_page) {
                echo "<a class='active' href='?page=$i'>$i</a>";
            } else {
                echo "<a href='?page=$i'>$i</a>";
            }
        }
        echo "</div>";

        // Đóng kết nối
        $conn->close();
        ?>
</div>

</body>
</html>
