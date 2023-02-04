<?php
// Merchant secret key
$secret_key = "your_secret_key";

// Data received from gateway
$order_id = $_POST['order_id'];
$amount = $_POST['amount'];
$status = $_POST['status'];
$post_hash = $_POST['post_hash'];


$encrypted_hash=base64_decode($post_hash);
$remote_hash=decrypt($encrypted_hash, $secret_key);
// Compute the payment hash locally
$local_hash = md5($order_id.$amount.$status.$secret_key);

if ($remote_hash == $local_hash) {
 // Mark the transaction as success & process the order
  // You can write code process the order herer
  // Confirm the amount , order_id ..etc with your record
  // Update your db with payment success
  $hash_status = "Hash Matched";
  $acknowledge='yes';
}
else {
  // Suspicious payment, dont process this payment.
  $hash_status = "Hash Mismatch";
  $acknowledge='no';
}

$data['hash_status']=$hash_status; 
$data['acknowledge']=$acknowledge;
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);  


function decrypt($ivHashCiphertext, $password) {
    $method = "AES-256-CBC";
    $iv = substr($ivHashCiphertext, 0, 16);
    $hash = substr($ivHashCiphertext, 16, 32);
    $ciphertext = substr($ivHashCiphertext, 48);
    $key = hash('sha256', $password, true);

    if (!hash_equals(hash_hmac('sha256', $ciphertext . $iv, $key, true), $hash)) return null;
    return openssl_decrypt($ciphertext, $method, $key, OPENSSL_RAW_DATA, $iv);
}


?>
