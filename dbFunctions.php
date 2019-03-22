<?php

class dbFunctions
{
    protected $db_host = NULL;
    protected $db_user = NULL;
    protected $db_pass = NULL;
    protected $db_name = NULL;
    protected $db_conn = NULL;

    function __construct()
    {
        include 'config.php';

        $this->db_host = $CFG_hostname;
        $this->db_user = $CFG_username;
        $this->db_pass = $CFG_password;
        $this->db_name = $CFG_dbname;

        $this->db_conn = mysqli_connect($this->db_host, $this->db_user, $this->db_pass, $this->db_name);
    }

    function employees($pageNum = 1, $pageSize = 10){

        $result = [];
        $sql = "SELECT id_zaposleni AS id, ime AS firstName, prezime AS lastName, srednje_ime AS midName, jmbg, datum_rodjenja AS dateBirth, pol AS sex, napomena AS note, status 
                FROM zaposleni 
                LIMIT " . ($pageNum - 1)*$pageSize . ", " . $pageSize;
        $sqlResult = mysqli_query($this->db_conn, $sql);
//        var_export($sql);
        
        if (mysqli_num_rows($sqlResult) > 0){
            while($row = mysqli_fetch_assoc($sqlResult)) {
                $row = array_map('utf8_encode', $row);
                $result[] = $row; 
            }
        }

        return $result;
    }
    // adress function
    function employee_address($employee_id){

        $result = [];
        $sql = "SELECT id_adresa AS id, vrsta AS type, opstina AS area, mesto AS city, CONCAT(ulica, ' ', broj) AS address, napomena AS note, status FROM zaposleni_adresa WHERE id_zaposleni = " . $employee_id;

        $sqlResult = mysqli_query($this->db_conn, $sql);
        
        if (mysqli_num_rows($sqlResult) > 0){
            while($row = mysqli_fetch_assoc($sqlResult)) {
                $row = array_map('utf8_encode', $row);
                $result[] = $row; 
            }
        }

        return $result;
    }
    // education function
    function employee_education($employee_id){

        $result = [];
        $sql = "SELECT zaposleni_obrazovanje.id_obrazovanje AS id, zaposleni_obrazovanje.vrsta AS type, zaposleni_obrazovanje.godina AS year, ustanova.naziv AS istitution, zvanje.naziv AS vocation "
                . "FROM zaposleni_obrazovanje  "
                . "INNER JOIN ustanova ON zaposleni_obrazovanje.ustanova_id=ustanova.id_ustanova "
                . "INNER JOIN zvanje ON zaposleni_obrazovanje.zvanje_id=zvanje.id_zvanje "
                . "WHERE id_zaposleni = " . $employee_id;

        $sqlResult = mysqli_query($this->db_conn, $sql);

        if (mysqli_num_rows($sqlResult) > 0) {
            while($row = mysqli_fetch_assoc($sqlResult)) {
                $row = array_map('utf8_encode', $row);
                $result[] = $row;
            }
        }

        return $result;
    }

    // phone function
    function employee_phone($employee_id){

        $result = [];
        $sql = "SELECT IF(area IS NOT NULL, CONCAT(area, SUBSTRING(pozivni,2), broj), CONCAT(pozivni, broj)) AS number FROM zaposleni_telefon WHERE id_zaposleni = " . $employee_id;

        $sqlResult = mysqli_query($this->db_conn, $sql);

        if (mysqli_num_rows($sqlResult) > 0) {
            while($row = mysqli_fetch_assoc($sqlResult)) {
                $row = array_map('utf8_encode', $row);
                $result[] = $row;
            }
        }

        return $result;
    }
    // adress function
    function addresses($pageNum = 1, $pageSize = 10){

        $result = [];
        $sql = "SELECT id_adresa AS id, vrsta AS type, opstina AS area, mesto AS city, CONCAT(ulica, ' ', broj) AS address, napomena AS note, status 
                FROM zaposleni_adresa 
                LIMIT " . ($pageNum - 1)*$pageSize . ", " . $pageSize;
        
        $sqlResult = mysqli_query($this->db_conn, $sql);
       
        if (mysqli_num_rows($sqlResult) > 0){
            while($row = mysqli_fetch_assoc($sqlResult)) {
                $row = array_map('utf8_encode', $row);
                $result[] = $row; 
            }
        }
        return $result;
    }
    
    function educations($pageNum = 1, $pageSize = 10){

        $result = [];
        $sql = "SELECT id_obrazovanje AS id, vrsta AS type, "
               ."(SELECT naziv  FROM ustanova WHERE ustanova.id_ustanova=zaposleni_obrazovanje.ustanova_id) AS institution, "
               ."(SELECT naziv FROM zvanje WHERE zvanje.id_zvanje=zaposleni_obrazovanje.zvanje_id) AS vocation "
               ."FROM agencija.zaposleni_obrazovanje "
               ." LIMIT " . ($pageNum - 1)*$pageSize . ", " . $pageSize;

        $sqlResult = mysqli_query($this->db_conn, $sql);
        
        if (mysqli_num_rows($sqlResult) > 0){
            while($row = mysqli_fetch_assoc($sqlResult)) {
                $row = array_map('utf8_encode', $row);
                $result[] = $row; 
            }
        }

        return $result;
    }
    function phones($pageNum = 1, $pageSize = 10){

        $result = [];
        $sql = "SELECT id_telefon AS id, tip AS type, "
                ."IF(area IS NOT NULL, CONCAT(area, SUBSTRING(pozivni,2), broj), CONCAT(pozivni, broj)) AS number "
                ." FROM agencija.zaposleni_telefon "
               ." LIMIT " . ($pageNum - 1)*$pageSize . ", " . $pageSize;

        $sqlResult = mysqli_query($this->db_conn, $sql);
        
        if (mysqli_num_rows($sqlResult) > 0){
            while($row = mysqli_fetch_assoc($sqlResult)) {
                $row = array_map('utf8_encode', $row);
                $result[] = $row; 
            }
        }

        return $result;
    }
    
    function empl_with_adr($pageNum = 1, $pageSize = 10){

        $result = [];
        $sql = "SELECT zaposleni.id_zaposleni, zaposleni_adresa.id_adresa, zaposleni.ime AS firstName, zaposleni.prezime AS lastName, "
                ."zaposleni_adresa.opstina AS area, zaposleni_adresa.mesto AS city, zaposleni_adresa.ulica AS address "
                ."FROM zaposleni INNER JOIN zaposleni_adresa ON zaposleni.id_zaposleni=zaposleni_adresa.id_zaposleni "
                ."ORDER BY id_zaposleni " 
                ."LIMIT " . ($pageNum - 1)*$pageSize . ", " . $pageSize;

        $sqlResult = mysqli_query($this->db_conn, $sql);
        
        if (mysqli_num_rows($sqlResult) > 0){
            while($row = mysqli_fetch_assoc($sqlResult)) {
                $row = array_map('utf8_encode', $row);
                $result[] = $row; 
            }
        }

        return $result;
    }
    
     
    
    
    function empl_with_phones($pageNum = 1, $pageSize = 10){

        $result = [];
        $sql = "SELECT zaposleni.id_zaposleni AS id_zaposleni, zaposleni_telefon.id_telefon, zaposleni.ime AS firstName, zaposleni.prezime AS lastName, "
                ."IF(zaposleni_telefon.area IS NOT NULL, CONCAT(zaposleni_telefon.area, SUBSTRING(zaposleni_telefon.pozivni,2), zaposleni_telefon.broj), CONCAT(zaposleni_telefon.pozivni, broj)) AS number  "
                ."FROM zaposleni LEFT JOIN zaposleni_telefon ON zaposleni.id_zaposleni=zaposleni_telefon.id_zaposleni "
                ."UNION "
                ."SELECT zaposleni.id_zaposleni AS id_zaposleni,zaposleni_telefon.id_telefon ,zaposleni.ime AS firstName, zaposleni.prezime AS lastName, "
                ."IF(zaposleni_telefon.area IS NOT NULL, CONCAT(zaposleni_telefon.area, SUBSTRING(zaposleni_telefon.pozivni,2), zaposleni_telefon.broj), CONCAT(zaposleni_telefon.pozivni, broj)) AS number  "
                ."FROM zaposleni RIGHT JOIN zaposleni_telefon ON zaposleni.id_zaposleni=zaposleni_telefon.id_zaposleni "
                ."ORDER BY id_zaposleni " 
                ."LIMIT " . ($pageNum - 1)*$pageSize . ", " . $pageSize;

        $sqlResult = mysqli_query($this->db_conn, $sql);
        
        if (mysqli_num_rows($sqlResult) > 0){
            while($row = mysqli_fetch_assoc($sqlResult)) {
                $row = array_map('utf8_encode', $row);
                if($row['number']!=''){
                    $result[] = $row;
                }
            }
        }

        return $result;
    }
    
    function empl_with_edu($pageNum = 1, $pageSize = 10){

        $result = [];
        $sql = "SELECT zaposleni.id_zaposleni AS id_zaposleni, zaposleni_obrazovanje.id_obrazovanje,  zaposleni.ime AS firstName, zaposleni.prezime AS lastName, "
               ."(SELECT ustanova.naziv  FROM ustanova WHERE ustanova.id_ustanova=zaposleni_obrazovanje.ustanova_id) AS institution, "
               ."(SELECT zvanje.naziv FROM zvanje WHERE zvanje.id_zvanje=zaposleni_obrazovanje.zvanje_id) AS vocation "
               ."FROM zaposleni INNER JOIN zaposleni_obrazovanje ON zaposleni.id_zaposleni=zaposleni_obrazovanje.id_zaposleni "
               ."ORDER BY id_zaposleni " 
               ."LIMIT " . ($pageNum - 1)*$pageSize . ", " . $pageSize;

        $sqlResult = mysqli_query($this->db_conn, $sql);
        
        if (mysqli_num_rows($sqlResult) > 0){
            while($row = mysqli_fetch_assoc($sqlResult)) {
                $row = array_map('utf8_encode', $row);
                $result[] = $row; 
            }
        }

        return $result;
        
    }
    
    function insert_employee($first_name, $last_name, $parents_name, $jmbg, $date_of_birth, $gender){
        
        $sql = "INSERT INTO zaposleni (ime, prezime, srednje_ime, jmbg, datum_rodjenja, pol)"
                ." VALUES ('".$first_name."','".$last_name."','".$parents_name
                ."','".$jmbg."','".$date_of_birth."','".$gender."')";
       
        $sqlResult = mysqli_query($this->db_conn, $sql);
        echo mysqli_insert_id($this->db_conn);
        echo "  ";

        return $sqlResult;
        
    }
    
    function saltCreator($saltLen) {
        $buffer = '';
        for ($i = 0; $i < $saltLen; $i++) {
            $rndNum = mt_rand(48, 122);
            while ($rndNum >= 58 AND $rndNum <= 64 OR $rndNum >= 91 AND $rndNum <= 96){
                $rndNum = mt_rand(48, 122);
            }  
            $buffer .= chr($rndNum);
        };
        return $buffer;
    }
    
    function password_creator($length) {
        $buffer = '';
        for ($i = 0; $i < $length; $i++) {
            $rndNum = mt_rand(48, 122);
            while ($rndNum >= 58 AND $rndNum <= 64 OR $rndNum >= 91 AND $rndNum <= 96){
                $rndNum = mt_rand(48, 122);
            }  
            $buffer .= chr($rndNum);
        };
        return $buffer;
    }
    
    function insert_employee_login($first_name, $last_name, $email, $password, $confirm){       
        
        if($password===$confirm){
            $sql = "INSERT INTO users (first_name, last_name, email)"
                ." VALUES ('".$first_name."','".$last_name."','".$email."')";                
  
            $sqlResult = mysqli_query($this->db_conn, $sql);

            $id_users=mysqli_insert_id($this->db_conn);


            $salt_md5="$1$".$this->saltCreator(9);

            $pwd_MD5 = crypt($password, $salt_md5);
            $sql_password="INSERT INTO password(id_users,salt,password)"
                ." VALUES (".$id_users.",'".$salt_md5."','".$pwd_MD5."')";
            $sqlResult = mysqli_query($this->db_conn, $sql_password);
            
            return 1000;
        }
        else{
            return 1001;        
        }     
        
    }
    
    function login($email, $password){
        
        $sql_users = "SELECT * FROM users WHERE email='".$email."'";
       
        $sqlResult_users = mysqli_query($this->db_conn, $sql_users);
        
        if(mysqli_num_rows($sqlResult_users)>0){
            $row_users = mysqli_fetch_assoc($sqlResult_users);
            $id_users=$row_users['id_users'];
            
            $sql_salt="SELECT * FROM password WHERE id_users=".$id_users;
            $sqlResult_password = mysqli_query($this->db_conn, $sql_salt);
            $row_password = mysqli_fetch_assoc($sqlResult_password);
            $pwd_MD5 = crypt($password, $row_password['salt']);
            
            if($pwd_MD5==$row_password['password']){
                //correct login
                return 1000;
            }
            else{
                //wrong password
                return 1001;
            }
        }
        else{
            //wrong email
            return 1002;
        }       
    }
    
    function update_password($id_users, $password){

        $salt_md5="$1$".$this->saltCreator(9);
        $pwd_MD5 = crypt($password, $salt_md5);
        $sql_update_pwd="UPDATE password SET salt='".$salt_md5."',password='".$pwd_MD5
                ."' WHERE id_users=".$id_users;
        $result = mysqli_query($this->db_conn, $sql_update_pwd);
        return $result;
    }
    
    function check_email($email){
        $sql = "SELECT id_users FROM users WHERE email='".$email."'";   
        $sqlResult = mysqli_query($this->db_conn, $sql);
        $array=array('found'=>'?','id'=>'?');
        
        if($sqlResult->num_rows > 0){
            $array['found']=TRUE;
            $row=$sqlResult->fetch_assoc();
            $array['id']=$row['id_users'];
        }
        else{
            $array['found']=FALSE;
            $array['id']='?';
        }
        return $array;
    }
    
    function send_pdf($pageNum = 1, $pageSize = 2000){
        $page=($pageNum - 1)*$pageSize;
        
        $sql = "SELECT zaposleni.id_zaposleni, zaposleni.ime, zaposleni.prezime "
                ."FROM zaposleni WHERE zaposleni.status=1 "
                ." ORDER BY zaposleni.prezime, zaposleni.ime, zaposleni.srednje_ime, zaposleni.jmbg"
                ." LIMIT " . $page . ", " . $pageSize;
        $sqlResult = mysqli_query($this->db_conn, $sql);
        
        $result=array();
        if($sqlResult->num_rows > 0){
            while($row = mysqli_fetch_assoc($sqlResult)) {
                $row = array_map('utf8_encode', $row);                
                $result_row=array("id_zaposleni"=>"", "name"=>"", "address"=>"","phone"=>"","vocation"=>"");
                $result_row['id_zaposleni']=$row['id_zaposleni'];
                $result_row['name']=$row['ime'];
                $result_row['lastname']=$row['prezime'];
                
                $address_array=array();
                
                $sql_address="SELECT zaposleni.id_zaposleni, zaposleni.ime, "
                ." CONCAT(zaposleni_adresa.ulica,', ',zaposleni_adresa.opstina,', ',zaposleni_adresa.mesto) AS address "
                ." FROM zaposleni LEFT JOIN zaposleni_adresa ON zaposleni.id_zaposleni=zaposleni_adresa.id_zaposleni "
                ." WHERE zaposleni.id_zaposleni=".$row['id_zaposleni'];
                $sqlResult_adr = mysqli_query($this->db_conn, $sql_address);
                if($sqlResult_adr->num_rows > 0){
                    while($row_adr = mysqli_fetch_assoc($sqlResult_adr)){
                        if(!is_null($row_adr['address'])){
                            $address_array[]=$row_adr['address'];
                        }
                    }
                }
                $result_row['address']=$address_array;
                
                $phone_array=array();
                $sql_phone="SELECT zaposleni.id_zaposleni, zaposleni.ime, zaposleni_telefon.broj "
                ." FROM zaposleni LEFT JOIN zaposleni_telefon ON zaposleni.id_zaposleni=zaposleni_telefon.id_zaposleni "
                ." WHERE zaposleni.id_zaposleni=".$row['id_zaposleni'];
                $sqlResult_adr = mysqli_query($this->db_conn, $sql_phone);
                if($sqlResult_adr->num_rows > 0){
                    while($row_phone = mysqli_fetch_assoc($sqlResult_adr)){
                        if(!is_null($row_phone['broj'])){
                            $phone_array[]=$row_phone['broj'];
                        }
                    }
                }
                $result_row['phone']=$phone_array;
                
                $vocation_array=array();
                $sql_vocation=" SELECT zaposleni.id_zaposleni, zaposleni.ime, zvanje.naziv "
                ." FROM zaposleni LEFT JOIN zaposleni_obrazovanje ON zaposleni.id_zaposleni=zaposleni_obrazovanje.id_zaposleni "
                ." LEFT JOIN zvanje ON zaposleni_obrazovanje.zvanje_id=id_zvanje "
                ." WHERE zaposleni.id_zaposleni=".$row['id_zaposleni'];
                $sqlResult_voc = mysqli_query($this->db_conn, $sql_vocation);
                if($sqlResult_voc->num_rows > 0){
                    while($row_voc = mysqli_fetch_assoc($sqlResult_voc)){
                        if(!is_null($row_voc['naziv'])){
                            $vocation_array[]=$row_voc['naziv'];
                        }

                    }
                }
                $result_row['vocation']=$vocation_array;
                
                $result[] =$result_row;            
            }
        }
        
        return $result;
    }
    
    function send_csv($pageNum = 1, $pageSize = 2000){
        $page=($pageNum - 1)*$pageSize;
        $sql = "SELECT zaposleni.id_zaposleni, zaposleni.ime, zaposleni.prezime "
                ." FROM zaposleni WHERE zaposleni.status=1 "
                ." ORDER BY zaposleni.prezime, zaposleni.ime, zaposleni.srednje_ime, zaposleni.jmbg"
                ." LIMIT " . $page . ", " . $pageSize;
        $sqlResult = mysqli_query($this->db_conn, $sql);
        
        $result=array();
        if($sqlResult->num_rows > 0){
            while($row = mysqli_fetch_assoc($sqlResult)){
                $row = array_map('utf8_encode', $row);                
                $result_row=array("id_zaposleni"=>"", "name"=>"","lastname"=>"", "address"=>"","phone"=>"","vocation"=>"");
                
                $address_array=array();
                $phone_array=array();
                $vocation_array=array();
                
                $sql_address="SELECT zaposleni.id_zaposleni, zaposleni.ime, "
                ." CONCAT(zaposleni_adresa.ulica,', ',zaposleni_adresa.opstina,', ',zaposleni_adresa.mesto) AS address "
                ." FROM zaposleni LEFT JOIN zaposleni_adresa ON zaposleni.id_zaposleni=zaposleni_adresa.id_zaposleni "
                ." WHERE zaposleni.id_zaposleni=".$row['id_zaposleni'];
                $sqlResult_adr = mysqli_query($this->db_conn, $sql_address);
                if($sqlResult_adr->num_rows > 0){
                    while($row_adr = mysqli_fetch_assoc($sqlResult_adr)){
                        $address_array[]=$row_adr['address'];
                    }
                }
                
                
                $sql_phone="SELECT zaposleni.id_zaposleni, zaposleni.ime, zaposleni_telefon.broj, "
                ." IF(zaposleni_telefon.area IS NOT NULL, CONCAT(zaposleni_telefon.area, SUBSTRING(zaposleni_telefon.pozivni,2), zaposleni_telefon.broj), "
                ." CONCAT(zaposleni_telefon.pozivni, zaposleni_telefon.broj)) AS phone_number "
                ." FROM zaposleni LEFT JOIN zaposleni_telefon ON zaposleni.id_zaposleni=zaposleni_telefon.id_zaposleni "
                ." WHERE zaposleni.id_zaposleni=".$row['id_zaposleni'];
                $sqlResult_adr = mysqli_query($this->db_conn, $sql_phone);
                if($sqlResult_adr->num_rows > 0){
                    while($row_phone = mysqli_fetch_assoc($sqlResult_adr)){
                        $phone_array[]=$row_phone['phone_number'];
                    }
                }
                
                
                $sql_vocation=" SELECT zaposleni.id_zaposleni, zaposleni.ime, zvanje.naziv "
                ." FROM zaposleni LEFT JOIN zaposleni_obrazovanje ON zaposleni.id_zaposleni=zaposleni_obrazovanje.id_zaposleni "
                ." LEFT JOIN zvanje ON zaposleni_obrazovanje.zvanje_id=id_zvanje "
                ." WHERE zaposleni.id_zaposleni=".$row['id_zaposleni'];
                $sqlResult_voc = mysqli_query($this->db_conn, $sql_vocation);
                if($sqlResult_voc->num_rows > 0){
                    while($row_voc = mysqli_fetch_assoc($sqlResult_voc)){
                        $vocation_array[]=$row_voc['naziv'];
                    }
                }
                
                $max_len=max(sizeof($address_array),sizeof($phone_array),sizeof($vocation_array));
                if($max_len>0){
                    for ($x = 0; $x <$max_len; $x++) { 
                        $result_row['id_zaposleni']=$row['id_zaposleni'];
                        $result_row['name']=$row['ime'];
                        $result_row['lastname']=$row['prezime'];
                        if(!empty($address_array[$x])){
                            $result_row['address']=$address_array[$x];
                        }
                        else{
                            $result_row['address']="";
                        }
                        if(!empty($phone_array[$x])){
                            $result_row['phone']=$phone_array[$x];
                        }
                        else{
                            $result_row['phone']="";
                        }
                        if(!empty($vocation_array[$x])){
                            $result_row['vocation']=$vocation_array[$x];                       
                        }
                        else{
                            $result_row['vocation']="";
                        }
                        $result[] =$result_row;
                    }
                }  
                else{
                    $result_row['id_zaposleni']=$row['id_zaposleni'];
                    $result_row['name']=$row['ime'];
                    $result_row['lastname']=$row['prezime'];
                    $result[] =$result_row;
                }
            }
        }
        
        return $result;
    }
}
