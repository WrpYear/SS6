<?php
session_start();
include_once('include/WebConfig.php');

if (!isset($_SESSION['uid'])) {
    echo "Unauthorized";
    exit;
}

$web = new MySQLClass();
$web->Connect2Web();
if (empty($web->Connect)) {
    echo "Cannot connect database";
    exit;
}
$web->dbname(WebDB);

$uid = $_SESSION['uid'];

$sql = "SELECT p.id,  p.spin_reward, p.create_time, bl.point, bl.point_free, bl.current_point, bl.current_point_free FROM play_log p LEFT JOIN user_balancelog bl ON bl.id = p.log_id WHERE p.user_id = '{$uid}' ORDER BY p.id DESC LIMIT 10;";

$rs = $web->select($sql);

$sqlCost = "SELECT * FROM user_balancelog WHERE user_id = '{$uid}' AND COMMENT = 'Spin Cost' ORDER BY id DESC LIMIT 10;";
$rsCost = $web->select($sqlCost);

if (count($rs) > 0) {
    echo '
    <h2 class="w-auto text-center text-white mt-5">History 10 Spins</h2>
    <table class="compsoul-history-table">
        <thead>
            <tr>
                <tr>
                    <th rowspan="2">No.</th>
                    <th rowspan="2">Point Reward</th>
                    <th colspan="2">Point</th>
                    <th colspan="2">Point Free</th>
                    <th rowspan="2">Spin Time</th>
                </tr>
                <tr>
                    <th>Before</th>
                    <th>After</th>
                    <th>Before</th>
                    <th>After</th>
                </tr>
            </thead>
            </tr>
        </thead>
        <tbody>';

    foreach ($rs as $i => $row) {
        $beforePoint = $row->current_point + $row->point;
        $beforePointFree = $row->current_point_free + $row->point_free;
        $created = date("d/m/Y H:i:s", strtotime($row->create_time));

        echo "<tr>
            <td>".($i+1)."</td>
            <td>".number_format($row->spin_reward)."</td>
            <td>".number_format($beforePoint)."</td>
            <td>".number_format($row->current_point)."</td>
            <td>".number_format($beforePointFree)."</td>
            <td>".number_format($row->current_point_free)."</td>
            <td>".$created."</td>
        </tr>";
    }

    echo '</tbody></table>';
} else {
    echo "No history found in play_log.";
}


$web->closedb();
?>
