<?php
require '../../global.php';
$conn = getDatabaseConnection();

class Action {
    private $db;
    private $s3;
    private $s3_url;

    public function __construct() {
        global $conn, $s3_url;
        $this->db = $conn;
        $this->s3_url = $s3_url;

        $this->s3 = new Aws\S3\S3Client([
            'version' => 'latest',
            'region'  => 'us-east-1', 
        ]);
    }

    function __destruct() {
        $this->db->close();
    }

    function login(){
        extract($_POST);
        $qry = $this->db->query("SELECT * FROM admins where username = '".$username."' and email = '".$email."'  and password = '".$password."' ");
        if($qry->num_rows > 0){
            foreach ($qry->fetch_array() as $key => $value) {
                if($key != 'password' && !is_numeric($key))
                    $_SESSION['login_'.$key] = $value;
            }
            return 1;
        } else {
            return 2;
        }
    }

    function logout(){
        session_destroy();
        foreach ($_SESSION as $key => $value) {
            unset($_SESSION[$key]);
        }
        header("location:login.php");
    }

    function save_movie(){
        extract($_POST);
        $data = " title = '".$title."' ";
        $data .= ", description = '".$description."' ";
        $data .= ", date_showing = '".$date_showing."' ";
        $data .= ", end_date = '".$end_date."' ";
        $data .= ", director = '".$director."' ";
        $data .= ", genre = '".$genre."' ";
        $data .= ", language = '".$language."' ";
        $data .= ", classification = '".$classification."' ";
        $data .= ", release_date = '".$release_date."' ";
        $data .= ", cast = '".$cast."' ";
        
        // Handle duration
        $duration = $duration_hour . 'h ' . $duration_min . 'm';
        $data .= ", duration = '".$duration."' ";
        
        // Handle trailer YouTube link
        $data .= ", trailer_yt_link = '".$trailer_yt_link."' ";

        // Handle cover image upload to S3
        if ($_FILES['cover']['tmp_name'] != '') {
            $fname = 'images/' . $_FILES['cover']['name'];
            $this->uploadToS3($_FILES['cover']['tmp_name'], $fname);
            $data .= ", cover_img = '" . $_FILES['cover']['name'] . "' ";
        }

        // Handle background image upload to S3
        if ($_FILES['background']['tmp_name'] != '') {
            $fname_bg = 'images/' . $_FILES['background']['name'];
            $this->uploadToS3($_FILES['background']['tmp_name'], $fname_bg);
            $data .= ", movie_background_image = '" . $_FILES['background']['name'] . "' ";
        }

        if(empty($id)){
            $save = $this->db->query("INSERT INTO movies set ". $data);
            if($save)
                return 1;
        } else {
            $save = $this->db->query("UPDATE movies set ". $data." where id =".$id);
            if($save)
                return 1;
        }
    }

    private function uploadToS3($filePath, $fileName) {
        try {
            $parser = new Aws\S3\S3UriParser();
            $bucketName = $parser->parse($this->s3_url)["bucket"];
            $result = $this->s3->putObject([
                'Bucket' => $bucketName,
                'Key'    => $fileName,
                'SourceFile' => $filePath
            ]);
            return $result['ObjectURL'];
        } catch (AwsException $e) {
            echo $e->getMessage();
            echo "\n";
        }
    }

    function delete_movie(){
        extract($_POST);
        $delete  = $this->db->query("DELETE FROM movies where id =".$id);
        if($delete)
            return 1;
    }

    function delete_theater(){
        extract($_POST);
        $delete  = $this->db->query("DELETE FROM theater where id =".$id);
        if($delete)
            return 1;
    }

    function save_theater(){
        extract($_POST);
        if(empty($id))
            $save = $this->db->query("INSERT INTO theater set name = '".$name."' ");
        else
            $save = $this->db->query("UPDATE theater set name = '".$name."' where id = ".$id);
        if($save)
            return 1;
    }

    function save_seat(){
        extract($_POST);
        $data = " theater_id = '".$theater_id."' ";
        $data .= ", seat_group = '".$seat_group."' ";
        $data .= ", seat_count = '".$seat_count."' ";
        if(empty($id))
            $save = $this->db->query("INSERT INTO theater_settings set ".$data." ");
        else
            $save = $this->db->query("UPDATE theater_settings set ".$data." where id = ".$id);
        if($save)
            return 1;
    }

    function delete_seat(){
        extract($_POST);
        $delete  = $this->db->query("DELETE FROM theater_settings where id =".$id);
        if($delete)
            return 1;
    }

    function save_reserve(){
        extract($_POST);
        $data = " movie_id = '".$movie_id."' ";
        $data .= ", ts_id = '".$seat_group."' ";
        $data .= ", lastname = '".$lastname."' ";
        $data .= ", firstname = '".$firstname."' ";
        $data .= ", contact_no = '".$contact_no."' ";
        $data .= ", qty = '".$qty."' ";
        $data .= ", `date` = '".$date."' ";
        $data .= ", `time` = '".$time."' ";

        $save = $this->db->query("INSERT INTO books set ".$data);
        if($save)
            return 1;
    }
}
?>

