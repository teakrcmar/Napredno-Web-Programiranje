<?php
include("simple_html_dom.php");

interface IRadovi {
    public function create($data);
    public function save($data);
    public function read();
}

class DiplomskiRadovi implements iRadovi {
    private $naziv_rada = NULL;
    private $tekst_rada = NULL;
    private $link_rada = NULL;
    private $oib_tvrtke = NULL;

    function __construct() {    }
    public function create($data){
        $this->naziv_rada = $data['naziv_rada'];
        $this->tekst_rada = $data['tekst_rada'];
        $this->link_rada = $data['link_rada'];
        $this->oib_tvrtke = $data['oib_tvrtke'];
    }
    public function save($data){        
        $this->create($data);

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "radovi";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
        }

        $naziv = $this->naziv_rada;
        $tekst = $this->tekst_rada;
        $link = $this->link_rada;
        $oib = $this->oib_tvrtke;

        $sql = "INSERT INTO diplomski_radovi (naziv_rada, tekst_rada, link_rada, oib_tvrtke) VALUES ('$naziv', '$tekst', '$link', '$oib')";
        $conn->query($sql);
        
        $conn->close();
    }
    public function read(){
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "radovi";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
        }

        $radovi = array();

        $sql = "SELECT * FROM diplomski_radovi";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {

                array_push($radovi, $row); 
            }
        }         

        $conn->close();

        return $radovi;
    }
}

for($i=2; $i<=6; $i++){
    $url = "https://stup.ferit.hr/index.php/zavrsni-radovi/page/".$i."/";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_BINARYTRANSFER, 1);

    $content = curl_exec($curl);
    
    $fp  = fopen($url, 'r');
    $read = fgetcsv($fp);
    $read = file_get_html($url);
    foreach($read->find('article') as $article) {

        foreach($article->find('ul.slides img') as $img) {}
        foreach($article->find('h2.entry-title a') as $link) {
                $html = file_get_html($link->href);
                foreach($html->find('.post-content') as $text) {
            }
        }
        $data = array(
            'naziv_rada' => $link->plaintext,
            'tekst_rada' => $text->plaintext,
            'link_rada' => $link->href,
            'oib_tvrtke' => preg_replace('/[^0-9]/', '', $img->src)
        );
        //echo implode($rad) ."<br><br>";
        $newRad = new DiplomskiRadovi();
        $newRad->save($data);            
    }
    fclose($fp);
    curl_close($curl);
}

$diplomskiRadovi = new DiplomskiRadovi();
$radovi = $diplomskiRadovi->read();
foreach($radovi as $key=>$value) {
    echo "<b>Id:</b> {$value['ID']}<br><b> Naziv rada:</b> {$value['naziv_rada']}<br><b>  Tekst rada:</b> {$value['tekst_rada']}<br><b> Link rada:</b> {$value['link_rada']}<br><b> OIB tvrtke:</b> {$value['oib_tvrtke']}<br><br>";
}
?>