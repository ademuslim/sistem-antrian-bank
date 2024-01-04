<?php
require_once '../_config.php';

// Fungsi untuk melakukan pencadangan tabel dan mengembalikan skrip SQL
function performBackup($conn, $database_name, $backupFileName) {
    $tables = array();
    $sql = "SHOW TABLES";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_row($result)) {
        $tables[] = $row[0];
    }

    $sqlScript = "";
    foreach ($tables as $table) {
        $query = "SHOW CREATE TABLE $table";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_row($result);

        $sqlScript .= "\n\n" . $row[1] . ";\n\n";

        $query = "SELECT * FROM $table";
        $result = mysqli_query($conn, $query);

        $columnCount = mysqli_num_fields($result);
        for ($i = 0; $i < $columnCount; $i++) {
            while ($row = mysqli_fetch_row($result)) {
                $sqlScript .= "INSERT INTO $table VALUES(";
                for ($j = 0; $j < $columnCount; $j++) {
                    $row[$j] = $row[$j];

                    if (isset($row[$j])) {
                        $sqlScript .= '"' . $row[$j] . '"';
                    } else {
                        $sqlScript .= '""';
                    }
                    if ($j < ($columnCount - 1)) {
                        $sqlScript .= ',';
                    }
                }
                $sqlScript .= ");\n";
            }
        }
        $sqlScript .= "\n";
    }

    return $sqlScript;
}

// Eksekusi pencadangan jika permintaan adalah POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $backupFileName = $_POST['backupFileName'] . '_' . date('Y-m-d');
    $sqlScript = performBackup($conn, $database_name, $backupFileName);

    if (!empty($sqlScript)) {
        $backup_file_name = $backupFileName . '.sql';
        $fileHandler = fopen($backup_file_name, 'w+');
        $number_of_lines = fwrite($fileHandler, $sqlScript);
        fclose($fileHandler);

        // Output untuk mengunduh file cadangan
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($backup_file_name));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($backup_file_name));
        ob_clean();
        flush();
        readfile($backup_file_name);

        // Hapus file cadangan setelah diunduh
        unlink($backup_file_name);
        exit();
    }
}
?>
