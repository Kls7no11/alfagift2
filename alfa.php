<?php
error_reporting(E_ALL ^ E_WARNING);
error_reporting(E_ALL ^ E_DEPRECATED);
welcome:
function curl($url,$headers,$data){
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    $resp = curl_exec($curl);
    curl_close($curl);
    return $resp;
    }
$random1 = rand(11111111,99999999);
$random2 = rand(11111111,99999999);
$random3 = rand(1119,9999);
$random4 = rand(1,9999);

$deviceid= "$random1-936d-974b-a9ef-$random2$random3";
$deviceid2 = "\"$deviceid\"";
echo "\n";
echo "\n";
echo "| Welcome to ALFAGIFT ACCOUNT GENERATOR!! \n";
echo "| Simpel script by Arie \n";
echo "| Wa 085782946968 \n";
echo "| --------------------------- \n";
if(file_exists("config.json")){
    $arr2 = json_decode(file_get_contents('config.json'), true);
    $token = $arr2['token'];
    $memberid = $arr2['id'];
    $hp = $arr2['hp'];
    echo "| Anda sudah login menggunakan nomer hp $hp \n";
    echo "| 1. Login \n";
    echo "| 2. Daftar \n";
    echo "| Pilih: ";
    $pilih = trim(fgets(STDIN));
    if($pilih !== '1'){
        goto daftar;
    }else{goto login;}
}
daftar:
echo "| Masukan Nomor HP: ";
$nomorhp = trim(fgets(STDIN));
$nomorhp = "\"$nomorhp\"";

$data_otp = '{"action":"REGISTER","mobileNumber":'.$nomorhp.',"type":0}';
$header_otp = array(
    "accept: application/json",
    "accept-language: id",
    "versionname: 4.0.30",
    "versionnumber: 403016",
    "devicemodel: Xiaomi Redmi Note 8",
    "packagename: com.alfamart.alfagift",
    "signature: 6E:41:03:61:A5:09:55:05:B6:84:84:C9:75:0B:89:56:5D:1D:41:C7",
    "latitude: 0.0",
    "longitude: 0.0",
    "deviceid: $deviceid",
    "Content-Type: application/json",
    "user-agent: okhttp/3.14.4",
 );
$url_otp = "https://api.alfagift.id/v1/otp/request";
$get_otp = curl($url_otp,$header_otp,$data_otp);
$get_otp = json_decode($get_otp,true);
$status = $get_otp['status']['code'];
if($status == "00"){
    $pesan = $get_otp['otpDescription'];
    echo "| $pesan \n";
}elseif($status == "01"){
    $pesan = $get_otp['otpDescription'];
    echo "| $pesan \n";
}else{echo "| Gagal"; die;}

otp:
echo "| Masukan KODE OTP: ";
$otp = trim(fgets(STDIN));
$otp = "\"$otp\"";
$url_verif_otp = "https://api.alfagift.id/v1/otp/verify";
$data_otp_login = '{"action":"REGISTER","mobileNumber":'.$nomorhp.',"otpCode":'.$otp.',"type":0}';
$verif_otp = curl($url_verif_otp,$header_otp,$data_otp_login);
$verif_otp = json_decode($verif_otp,true);
$status_login = $verif_otp['status']['code'];
if($status_login !== "00"){
    $pesan = $verif_otp['verifyOtpDescription'];
    echo "| $pesan \n";
    goto otp;
}else{
    $token = $verif_otp['token'];
    $token2 = "\"$token\"";
}


$url_daftar = "https://api.alfagift.id/v1/account/member/create";
$random_name = "Arie"." ".substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 4);
$random_email = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 7);
$random_name = "\"$random_name\"";
$random_email = "\"$random_email@gmail.com\"";
$data_create = '{"address":"","birthDate":"1991-10-11","debug":false,"deviceId":'.$deviceid2.',"email":'.$random_email.',"firstName":"","fullName":'.$random_name.',"gender":"F","lastName":"","latitude":0,"longitude":0,"maritalStatus":"M","password":"akun1212","phone":'.$nomorhp.',"postCode":"","registerPonta":true,"token":'.$token2.'}';
$create_akun = curl($url_daftar,$header_otp,$data_create);
$put = file_put_contents('createakunalfa.txt',$create_akun);
$create_akun = json_decode($create_akun,true);
$status_create = $create_akun['status']['code'];
if($status_create !== "00"){
    $message = $create_akun['status']['message'];
    echo "| $message";
    die;
}else{
    $message = $create_akun['status']['message'];
    echo "| Status: $message \n";
    $memberid = $create_akun['memberId'];
    $token = $create_akun['status']['token'];
    $id_ponta = $create_akun['member']['ponta']['accountCard'];
    $no_hp = $create_akun['member']['ponta']['phoneNumber'];
    echo "| Sukses daftar!!! \n";
    echo "| Nomer hp $no_hp dan password MantapJiwa \n";
    echo "| Member Ponta $id_ponta \n";
    echo "| Loading voucher \n";
    sleep(4);
    $arr1 = ["token"=>$token,"id"=>$memberid, "hp"=>$no_hp];
    file_put_contents("config.json",json_encode($arr1));
    $file = fopen("alfajoss.txt","a");  
    fwrite($file,"--------------------------------------------------------".PHP_EOL);
    fwrite($file,"$no_hp & Nomor PONTA : $id_ponta".PHP_EOL);
    fwrite($file,"--------------------------------------------------------".PHP_EOL);
    fclose($file);
    goto login;
}



login:
$heder_jadi = array(
    "accept: application/json",
    "accept-language: id",
    "versionname: 4.0.30",
    "versionnumber: 403016",
    "devicemodel: Xiaomi Redmi Note 8",
    "packagename: com.alfamart.alfagift",
    "signature: 6E:41:03:61:A5:09:55:05:B6:84:84:C9:75:0B:89:56:5D:1D:41:C7",
    "latitude: 0.0",
    "longitude: 0.0",
    "deviceid: $deviceid",
    "token: $token",
    "id: $memberid",
    "Content-Type: application/json",
    "user-agent: okhttp/3.14.4",
 );
 $url_login = "https://api.alfagift.id/v1/promotion/myVoucher";
 $data_login = '{"limit":10,"start":0}';
 $ogin = curl($url_login,$heder_jadi,$data_login);
 $login = json_decode($ogin,true);
 $status = $login['status']['code'];
 if($status == "00"){
    $total_voucher = $login['totalVouchers'];
    if($total_voucher == null){
        echo "| Voucher Kosong!!! \n";
        
    }elseif($total_voucher !== null){
        echo "| Total voucher = $total_voucher \n";
        $voucher = $login['vouchers'];
        print_r($voucher);
        
    }
 }

goto welcome;





