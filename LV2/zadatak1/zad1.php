<?php
    function get_column_names($dbc, $table){
        $sql = 'DESCRIBE ' .$table;
        $result = mysqli_query($dbc, $sql);

        $rows = array();
        while($row = mysqli_fetch_assoc($result)){
            $rows[] = $row['Field'];
        }
        return $rows;
    }

    function backupDatabaseTables($dbConnectionData){
        $db_name = $dbConnectionData["dbname"];
        $dir = "backup"; 
        $time = time();

        $dbc = @mysqli_connect(
            $dbConnectionData["servername"];
            $dbConnectionData["username"];
            $dbConnectionData["password"];
            $dbConnectionData["dbname"];
        ) OR die("<p>Connection to the database '$db_name' unsuccessful.</p></body></html>");

        $r = mysqli_query($dbc, 'SHOW TABLES');
        
        //if there isn't a single table, backup needs to stop
        if(mysqli_num_rows($r<= 0){
            echo "<p>Database '$db_name' doesn't have any tables at all.</p>";
            return;
        }

        echo "<p>Backup for databse '$db_name'</p>"

        while (list($table) = mysqli_fetch_array($r,MYSQLI_NUM)) {
            // array_push($tables, );
            $q = "SELECT * FROM $table";
            $r2 = mysqli_query($dbc, $q);

            //if no data exists, break
            if (mysqli_num_rows($r2) <= 0) {
                //ako ne možemo stvoriti datoteku
                echo "<p>File $dir/{$table}_{$time}.sql.gz cannot be opened.</p>";
                break; //stop while loop
            }
            $colNamesTXT ="";
            $rowValues = "";

            $col_names = get_column_names($dbc, $table);
            for($i = 0; $i<count($col_names); $i++){
                $colNamesTXT = $colNamesTXT."'".$col_names[$i]."'";
                if($i + 1 < count($col_names)){
                    $colNamesTXT = $colNamesTXT.", ";
                }
            }

            if (!is_dir($dir)) {
                if (!@mkdir($dir)) {
                    die("<p>Cannot create 'backup' directory.</p></body></html>");
                }
            }
            if (!is_dir($dir)) {
                if (!@mkdir($dir)) {
                    die("<p>Cannot create 'backup' directory.</p></body></html>");
                }
            }

            //open the file
            if ($fp = gzopen ("$dir/{$table}_{$time}.sql.gz", 'w9')) {  

                //fetch data from the table
                while ($row = mysqli_fetch_array($r2, MYSQLI_NUM)) {
                    $rowValues = "";
                    for($i = 0; $i < count($col_names); $i++){
                        $rowValues = $rowValues."'".$row[$i]."'";
                        if($i + 1 < count($col_names)){
                            $rowValues = $rowValues.", ";
                        }
                    }
                    $txt = "";
                    $txt = $txt."INSERT INTO ".$table." (".$colNamesTXT.")\n";
                    $txt = $txt."VALUES (".$rowValues.");\n";
                    // echo "<br>".$txt."<br>"; 
                    gzwrite ($fp, $txt);
                }
                gzclose ($fp);
                echo "<p>Table '$table' is stored.</p>";
            }
        }
        $dbc->close();
    }
    $dbConnectionData = array(
        'servername' => "localhost",
        'username' => "root",
        'password' => "",
        'dbname' => "radovi"
        );

    backupDatabaseTables($dbConnectionData);
?>