
### WebHook Integration 

WebHook has to integrate on your server, where our application inform you about the status change of payment

#### Required Fields
```sh
secret_key = given secret key;
```

In the POST request you will get following values
```sh 
order_id:
amount:
status:
post_hash:
refund_info:
```

```sh 
# if there is a refund request happened `refund_info` has follwing data 
refunded_upi 
refund_amount
refund_initiated_time
refund_completed_time
refund_status
refund_notes
```                

 From the webHook you will get following status
        Approved	:> Payment is Approved by our system
        Late Approved :> Payment is Late approved by human intervention
        Declined :>	Payment is declined by our system
        No Matching Payment for UTR	:> system waited till timeout but no payment/matching UTR received against the payment 
        Pending	:> User session in active waiting to finish payment
        User Timed Out :>	User did'nt finished payment within the session period
        Refund Initiated :> Refund initiated for this order_id
        Refund Completed :> Refund completed for this order_id

![image](https://user-images.githubusercontent.com/30625676/213218551-7deef0e1-2812-421c-8845-767a5207fef6.png)


### Following are steps to integrate WebHook
### Step 1:> base64 decode post_hash
- This step is to decode received post_hash to verify integrity of received data
- Use base64_decode()
```sh
#PHP Example:
 $encrypted_hash=base64_decode($_POST['post_hash']);
```

### step 2:> Decrypt Hash 
- The Hash is encrypted format so we want to decrypt hash to see actual hash
```sh
#PHP Example:
// Function for decrypt
 function decrypt($ivHashCiphertext, $password) {
    $method = "AES-256-CBC";
    $iv = substr($ivHashCiphertext, 0, 16);
    $hash = substr($ivHashCiphertext, 16, 32);
    $ciphertext = substr($ivHashCiphertext, 48);
    $key = hash('sha256', $password, true);

    if (!hash_equals(hash_hmac('sha256', $ciphertext . $iv, $key, true), $hash)) return null;
    return openssl_decrypt($ciphertext, $method, $key, OPENSSL_RAW_DATA, $iv);
}
// remote hash 
$remote_hash=decrypt($encrypted_hash,$secret_key);
```


### step 3:>  Compute local hash 
- Use md5 128 bit hashing algorithm to generate hash)
- We will compare local and remote hash to verify integrity of data

```sh
// Compute the payment hash locally In (PHP Example)
$local_hash = md5($order_id.$amount.$status.$secret_key);  
```
### step 4:>  Verify hash 
- Compare remote hash given at request and local hash 
```sh 
if ($remote_hash == $local_hash)
{
  // Mark the transaction as success & process the order
  // You can write code process the order herer
  // Confirm the amount , order_id ..etc with your record
  // Update your db with payment success
  $hash_status = "Hash Matched";
  
    
}
  else
  {
      // Verification failed
       $hash_status = "Hash Mismatch";
  }
```
### Step 5 :>  Acknowledge Back payment gateway 
-  You should  Acknowledge back payment gateway that you logged the status of payment  
-  Otherwise you will get multiple acknowledge polling 
- This measure is to avoid network faliure , Packet loss , System overload issues to update data 

```sh
$data['hash_status']=$hash_status; // 'Hash Matched' or 'Hash Mismatch' 
$data['acknowledge']=$acknowledge; // 'yes' or 'no'
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data); // output as a json file
```

       




