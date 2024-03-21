<?php
//Load file
$xml = simplexml_load_file('LV2.xml');

foreach ($xml->record as $record) {

    $gender = substr($record->spol, 0, 1);
    $image = @getimagesize ($record->slika);

    echo '<div class="w-50 d-inline-flex bd-highlight align-items-center">';
        echo "<div class='p-2 bd-highlight '><img src=$record->slika $image[3]></div>";
        echo "<div class='p-2 bd-highlight'>";
            echo "<div class='d-flex flex-column bd-highlight mb-3'>";
                echo "<div class='p-2 bd-highlight'><b>#$record->id $record->ime $record->prezime [$gender]</b></div>";
                echo "<div class='p-2 bd-highlight'>$record->email</div>";
                echo "<div class='p-2 bd-highlight'>$record->zivotopis</div>";
            echo '</div>';
        echo '</div>';
    echo '</div>';

}

?>