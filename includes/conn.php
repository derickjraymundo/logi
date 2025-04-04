<?php
    $dev_team = "";
    $dev_projectname = "LOGISTICS";
    $dev_projectacroname = "LOGI";
    // $dev_web_version = "0.0.1";
    $dev_project_start = "2025";
    $dev_image = "../assets/images/mw_img_0.jpg";
    $dev_web_version = "0.0.1";
    $dev_root_ip = "localhost";


    date_default_timezone_set('Asia/Manila');

    Class Database{
    
        private $server = "mysql:host=localhost:3306;dbname=logi;charset=utf8";
        // private $server = "mysql:host=192.168.88.35:3306;dbname=ess_test;charset=utf8";
        private $username = "root";
        private $password = "";
        private $options  = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
        protected $conn;
        
        
        public function open(){
            try{
                $this->conn = new PDO($this->server, $this->username, $this->password, $this->options);
                return $this->conn;
            }
            catch (PDOException $e) {
        
                header( "location: maintenance.php" );
            }
    
        } 
        public function close() {
            $this->conn = null;
        }
    }

    $pdo = new Database();

    function time_ago($timestamp)  {  
        $time_ago = strtotime($timestamp);  
        $current_time = time();  
        $time_difference = $current_time - $time_ago;  
        $seconds = $time_difference;  
        $minutes      = round($seconds / 60 );           // value 60 is seconds  
        $hours           = round($seconds / 3600);           //value 3600 is 60 minutes * 60 sec  
        $days          = round($seconds / 86400);          //86400 = 24 * 60 * 60;  
        $weeks          = round($seconds / 604800);          // 7*24*60*60;  
        $months          = round($seconds / 2629440);     //((365+365+365+365+366)/5/12)*24*60*60  
        $years          = round($seconds / 31553280);     //(365+365+365+365+366)/5 * 24 * 60 * 60  

        if($seconds <= 60)  {  
            return "Just Now";  
        }else if($minutes <=60){  
            if($minutes==1) {  
                return "one minute ago";  
            }else {  
                return "$minutes minutes ago";  
            }  
        }else if($hours <=24) {  
            if($hours==1) {  
                return "an hour ago";  
            }else{  
                return "$hours hrs ago";  
            }  
        }else if($days <= 7) {  
            if($days==1){  
                return "yesterday";  
            }else{  
                return "$days days ago";  
            }  
        }else if($weeks <= 4.3) {  
            if($weeks==1) {  
                return "a week ago";  
            }else {  
                return "$weeks weeks ago";  
            }  
        }else if($months <=12){  
            if($months==1) {  
                return "a month ago";  
            }else {  
                return "$months months ago";  
            }  
        }else {  
            if($years==1) {  
                return "one year ago";  
            }else {  
                return "$years years ago";  
            }  
        }  
    }  


function textRandomizer($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}
function noText($text) {

    if($text == "") {

        return "<label class='text-danger'>-</label>";
    }else {

        return $text;
    }
    

}

function isDeleted($status, $customeStatus) {

    if($status == 0) {

        return "<span class='badge bg-success'>Active</span>";
    }else if($status == 1) {
        return "<span class='badge bg-danger'>Deleted</span>";
    }else {

        return "<span class='badge bg-warning'>$customeStatus</span>";
    }

}

function getClockType($clockid, $conne) {


    try {
        $stmtgetClockType = $conne->prepare("SELECT bundy_type FROM tbl_setup_web_bundy WHERE id=:id LIMIT 1");
        $stmtgetClockType->execute(['id'=>$clockid]);
        $ftcgetClockType = $stmtgetClockType->fetch();
    
        return $ftcgetClockType['bundy_type'];

    }catch(PDOException $e) {
        return $e->getMessage();
    }

}
function getEmployeephotos($photoloc) {
    $imagePath = "../assets/images/emp/".$photoloc;

    if($photoloc == "") {
        return "<div class='avatar-md me-4'>
            <img src='../assets/images/emp/no_image_available.jpg' class='img-fluid rounded-circle' alt=''>
        </div>";

    }else {
        if (file_exists($imagePath)) {
            return "<div class='avatar-md me-4'>
                        <img src='$imagePath' class='img-fluid rounded-circle' alt=''>
                    </div>";
        } else {
            return "<div class='avatar-md me-4'>
                        <img src='../assets/images/emp/no_image_available.jpg' class='img-fluid rounded-circle' alt=''>
                    </div>";
        }
    }
}

function number_formatMoney($amount) {

    return "₱ ".number_format($amount, 2, '.', ',');

}

function convertTo12HourFormat($time) {
    $timestamp = strtotime($time);
    return date('g:i A', $timestamp); // 'g:i A' for 12-hour format with AM/PM
}

function showWithSchedule($scheduleid, $conne) {


    $getScheduleDetails = $conne->prepare("SELECT * FROM vw_schedules WHERE id =:id");
    $getScheduleDetails->execute(['id'=>$scheduleid]);
    $ftcgetScheduleDetails = $getScheduleDetails->fetch();

    return $ftcgetScheduleDetails['alternative_id'] . " " .$ftcgetScheduleDetails['facultyname'] . "<br>"
    ."Laboratory: " .$ftcgetScheduleDetails['laboratory_name'] . "<br>"
    ."Week: " .$ftcgetScheduleDetails['weekname'] . "<br>"
    ."Course: " .$ftcgetScheduleDetails['course_name'] . "<br>"
    ."Subject: " .$ftcgetScheduleDetails['subject_name'] . "<br>"
    ."Time: " .convertTo12HourFormat($ftcgetScheduleDetails['start_time']). " - ".convertTo12HourFormat($ftcgetScheduleDetails['end_time']) . "<br>"
    ."Year: " .$ftcgetScheduleDetails['schedule_year'] . "<br>"
    
    ;


}


?>