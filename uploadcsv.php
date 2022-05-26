<?php
function kama_parse_csv_file( $file_path, $file_encodings = ['cp1251','UTF-8'], $col_delimiter = '', $row_delimiter = '' ){

    if( ! file_exists( $file_path ) ){
        return false;
    }

    $cont = trim( file_get_contents( $file_path ) );

    $encoded_cont = mb_convert_encoding( $cont, 'UTF-8', mb_detect_encoding( $cont, $file_encodings ) );

    unset( $cont );

    // определим разделитель
    if( ! $row_delimiter ){
        $row_delimiter = "\r\n";
        if( false === strpos($encoded_cont, "\r\n") )
            $row_delimiter = "\n";
    }

    $lines = explode( $row_delimiter, trim($encoded_cont) );
    $lines = array_filter( $lines );
    $lines = array_map( 'trim', $lines );

    // авто-определим разделитель из двух возможных: ';' или ','.
    // для расчета берем не больше 30 строк
    if( ! $col_delimiter ){
        $lines10 = array_slice( $lines, 0, 30 );

        // если в строке нет одного из разделителей, то значит другой точно он...
        foreach( $lines10 as $line ){
            if( ! strpos( $line, ',') ) $col_delimiter = ';';
            if( ! strpos( $line, ';') ) $col_delimiter = ',';

            if( $col_delimiter ) break;
        }

        // если первый способ не дал результатов, то погружаемся в задачу и считаем кол разделителей в каждой строке.
        // где больше одинаковых количеств найденного разделителя, тот и разделитель...
        if( ! $col_delimiter ){
            $delim_counts = array( ';'=>array(), ','=>array() );
            foreach( $lines10 as $line ){
                $delim_counts[','][] = substr_count( $line, ',' );
                $delim_counts[';'][] = substr_count( $line, ';' );
            }

            $delim_counts = array_map( 'array_filter', $delim_counts ); // уберем нули

            // кол-во одинаковых значений массива - это потенциальный разделитель
            $delim_counts = array_map( 'array_count_values', $delim_counts );

            $delim_counts = array_map( 'max', $delim_counts ); // берем только макс. значения вхождений

            if( $delim_counts[';'] === $delim_counts[','] )
                return array('Не удалось определить разделитель колонок.');

            $col_delimiter = array_search( max($delim_counts), $delim_counts );
        }

    }

    $data = [];
    foreach( $lines as $key => $line ){
        $data[] = str_getcsv( $line, $col_delimiter ); // linedata
        unset( $lines[$key] );
    }

    return $data;
}


// print_r($data);


if ( isset($_POST["submit"]) ) {

   if ( isset($_FILES["file"])) {
        if (substr($_FILES["file"]["name"], -3, 3)=='csv'){

                //if there was an error uploading the file
            if ($_FILES["file"]["error"] > 0) {
                echo "Return Code: " . $_FILES["file"]["error"] . "<br />";

            }
            else {
                include 'config.php';
                $translate = $dbh->query("SELECT * FROM `translate`");
                $name = $dbh->query("SELECT * FROM `name_fjalor`");

                if ($translate->rowCount()!=0){
                    $deltranslate = $dbh->query("DELETE FROM `translate`");
                    // $dellike->execute(['user_id' => $chat_id]);
                }

                if ($name->rowCount()!=0){
                    $delname = $dbh->query("DELETE FROM `name_fjalor`");
                    // $dellike->execute(['user_id' => $chat_id]);
                }

                     //Print file details
                 // echo "Upload: " . $_FILES["file"]["name"] . "<br />";
                 // echo "Type: " . $_FILES["file"]["type"] . "<br />";
                 // echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
                 // echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

                     //if file already exists
                 if (file_exists("csv/" . $_FILES["file"]["name"])) {
                // echo $_FILES["file"]["name"] . " already exists. ";
                 }
                 else {
                        //Store file in directory "upload" with the name of "uploaded_file.txt"
                $storagename = $_POST['name'].".txt";
                move_uploaded_file($_FILES["file"]["tmp_name"], "csv/" . $storagename);
                //echo "Stored in: " . "csv/" . $_FILES["file"]["name"] . "<br />";
                }
                $_SESSION['showAlert'] = 'block';
                $_SESSION['message'] = 'Item removed from the cart!';

                
                $data = kama_parse_csv_file( "csv/" . $storagename );
                foreach ($data as $Row) {
                    if (($Row[0] != 'ENG')&&($Row[1] != 'RUS')){
                     $set = $dbh->prepare("INSERT INTO `translate` SET ENG = :eng, RUS = :rus");
                     $set->execute(['eng' => $Row[0], 'rus' => $Row[1]]);

                    }
                }
                $setName = $dbh->prepare("INSERT INTO `name_fjalor` SET name_fjalor = :name_fjalor");
                $setName->execute(['name_fjalor' => $_POST['name']]);
                header('location: index.php');
            }
        }
     } else {
             echo "No file selected <br />";
     }
}
?>