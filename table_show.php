<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <title>Table</title>
</head>
<body>

    <?php

include_once('include/WebConfig.php');

$web = new MySQLClass();
$web->Connect2Web();
if (empty($web->Connect)) {
    echo "Cannot connect database";
    exit;
}

$web->dbname(WebDB);

$sql = "SELECT * FROM user_account ORDER BY update_time DESC";
$rs = $web->select($sql);

if (count($rs) > 0) {
    echo '<h1>List User</h1>';
    echo '<div class="d-flex justify-content-center">';
    echo '<table class="w-auto table table-striped table-bordered border-dark">';
    echo '<tr class="table-dark">';
    echo "<th>NO.</th>";
    echo "<th>Name</th>";
    echo "<th>Lastname</th>";
    echo "<th>Username</th>";
    echo "<th>Email</th>";
    echo "<th>Phone No.</th>";
    echo "<th>Address</th>";
    echo "<th>Reference NO.</th>";
    echo "<th>Created Time</th>";
    echo "<th>Updated Time</th>";
    echo "<th>Status</th>";
    echo "<th>Action</th>";
    echo "</tr>";
    
    
    for ($i = 0; $i < count($rs); $i++) {
        $created = date("d/m/Y H:i:s", strtotime($rs[$i]->create_time));
        $updated = date("d/m/Y H:i:s", strtotime($rs[$i]->update_time));
        $checked = $rs[$i]->status == 1 ? 'checked' : '';
        
        echo "<td>" . $i+1 . "</td>";
        echo "<td><input type='text' class='form-control-plaintext' data-id='{$rs[$i]->id}' data-field='name' data-original='{$rs[$i]->name}' value='{$rs[$i]->name}' readonly></td>";
        echo "<td><input type='text' class='form-control-plaintext' data-id='{$rs[$i]->id}' data-field='lastname' data-original='{$rs[$i]->lastname}' value='{$rs[$i]->lastname}' readonly></td>";
        echo "<td><input type='text' class='form-control-plaintext' data-id='{$rs[$i]->id}' data-field='username' data-original='{$rs[$i]->username}' name='username' value='{$rs[$i]->username}' readonly disabled></td>";
        echo "<td><input type='text' class='form-control-plaintext' data-id='{$rs[$i]->id}' data-field='email' data-original='{$rs[$i]->email}' name='email' value='{$rs[$i]->email}' readonly></td>";
        echo "<td><input type='text' class='form-control-plaintext' data-id='{$rs[$i]->id}' data-field='phone' data-original='{$rs[$i]->phone}' name='phone' value='{$rs[$i]->phone}' readonly></td>";
        echo "<td><input type='text' class='form-control-plaintext' data-id='{$rs[$i]->id}' data-field='address' data-original='{$rs[$i]->address}' name='address' value='{$rs[$i]->address}' readonly></td>";
        echo "<td><input type='text' class='form-control-plaintext' data-id='{$rs[$i]->id}' data-field='ref_code' data-original='{$rs[$i]->ref_code}' name='ref_code' value='{$rs[$i]->ref_code}' readonly disabled></td>";
        echo "<td>" . $created . "</td>";
        echo "<td>" . $updated . "</td>";
        echo "<td>";
        echo '<div class="form-check form-switch">';
        echo '<input class="form-check-input status-switch" type="checkbox" role="switch"';
        echo 'data-id="' . $rs[$i]->id . '" ' . $checked . '>';
        echo '</div>';
        echo "</td>";
        echo "<td>
    <button class='btn btn-sm btn-primary edit-btn' data-id='{$rs[$i]->id}'>EDIT</button>
    <button class='btn btn-sm btn-success save-btn d-none' data-id='{$rs[$i]->id}'>SAVE</button>
    <button class='btn btn-sm btn-secondary cancel-btn d-none' data-id='{$rs[$i]->id}'>CANCEL</button>
</td>";
        echo "</tr>";
        echo '</div>';
 
    }

} else {
    echo "No data found.";
}

$web->closedb();
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
    $('.status-switch').change(function () {
        let userId = $(this).attr('data-id');
        let status = $(this).is(':checked') ? 1 : 0;
        // ส่งข้อมูลไปยัง PHP ผ่าน AJAX
        $.ajax({
            url: 'api_ajax_user_account.php', // ไฟล์ PHP ที่จะอัปเดตฐานข้อมูล
            method: 'POST',
            data: {
                id: userId,
                status: status
            },
            success: function (response) {
            },
            error: function () {
                alert('Unsuccess');
            }
        });
    });
})
$(document).ready(function () {
    // กด EDIT
    $(document).on('click', '.edit-btn', function () {
        let id = $(this).data('id');
        
        // เปลี่ยน input ให้ editable
        $(`input[data-id='${id}']`).each(function () {
            if (!$(this).prop('disabled')) {
                $(this).css('background-color', '');
                $(this).removeAttr('readonly')
                       .removeClass('form-control-plaintext')
                       .addClass('form-control');
            }
        });

        // แสดงปุ่ม SAVE / CANCEL ซ่อนปุ่ม EDIT
        $(`.edit-btn[data-id='${id}']`).addClass('d-none');
        $(`.save-btn[data-id='${id}']`).removeClass('d-none');
        $(`.cancel-btn[data-id='${id}']`).removeClass('d-none');
    });

    // กด CANCEL
    $(document).on('click', '.cancel-btn', function () {
        let id = $(this).data('id');

        // ย้อนกลับ input เป็น readonly
        $(`input[data-id='${id}']`).each(function () {
            if (!$(this).prop('disabled')) {
                let original = $(this).data('original'); // ← ดึงค่าดั้งเดิม
                $(this).val(original)                     // ← ย้อนค่า
                $(this).prop('readonly', true)
                       .removeClass('form-control')
                       .addClass('form-control-plaintext');
            }
        });

        // ซ่อนปุ่ม SAVE / CANCEL และแสดง EDIT
        $(`.edit-btn[data-id='${id}']`).removeClass('d-none');
        $(`.save-btn[data-id='${id}']`).addClass('d-none');
        $(`.cancel-btn[data-id='${id}']`).addClass('d-none');
    });

    // กด SAVE
    $(document).on('click', '.save-btn', function () {
    let id = $(this).data('id');
    let data = { id: id };

    // เก็บค่าจาก input ที่เกี่ยวข้อง
    $(`input[data-id='${id}']`).each(function () {
        let field = $(this).data('field');
        if (field) {
            let current = $(this).val();
            data[field] = current;
        }
    });

    // ส่ง AJAX ไปอัปเดต
    $.ajax({
        url: 'update_data.php',
        method: 'POST',
        data: data,
        success: function (res) {
            let obj;
            try {
                obj = JSON.parse(res);
            } catch (e) {
                alert('Invalid response from server');
                return;
            }

            if (obj.ret === '101') {
                const updated = obj.update_data[0] || {};

                // วน loop input ที่มี data-id นี้
                $(`input[data-id='${id}']`).each(function () {
                    let field = $(this).data('field');
                    if (!field || $(this).prop('disabled')) return;

                    // เปลี่ยนกลับเป็น readonly
                    let original = $(this).data('original'); // ← ดึงค่าดั้งเดิม
                    $(this).val(original)                     // ← ย้อนค่า
                    $(this).prop('readonly', true)
                           .removeClass('form-control')
                           .addClass('form-control-plaintext');

                    // ถ้าฟิลด์นี้มีอยู่ใน response => แปลว่าถูกอัปเดต => ไฮไลท์
                    if (updated.hasOwnProperty(field)) {
                        $(this).css('background-color', 'orange');

                        // อัปเดต value เผื่อฝั่ง server เปลี่ยนค่าจริง ๆ
                        $(this).val(updated[field]);

                        // อัปเดตค่า original
                        $(this).data('original', updated[field]);

                        // ลบไฮไลท์ภายหลัง
                        setTimeout(() => {
                            $(this).css('background-color', '');
                        }, 5000);
                    }
                });

                // toggle ปุ่ม
                $(`.edit-btn[data-id='${id}']`).removeClass('d-none');
                $(`.save-btn[data-id='${id}']`).addClass('d-none');
                $(`.cancel-btn[data-id='${id}']`).addClass('d-none');

            } else if (obj.ret === '201') {
                alert('No update');
            } else {
                alert(obj.message || 'Update failed');
            }
        },
        error: function () {
            alert('AJAX error');
        }
    });
});
});
</script>

</body>
</html>