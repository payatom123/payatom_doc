# Payatom integration 
## Request Payment from Android/IOS APP 

Please follow the steps to integrate payment request from your app

##### Following data need to hold before intitating a payment request

### Notes
1. The IP should be whitelisted from our side to accept request 
2. This step is back end to back end communication 
## Required Fields:
-  gateway endpoint : https://app.payatom.in/pay/request.php
-  order_id: your order id (it should be unique and atleast 10 character)
-  pid: given merchant id
-  amount: amount to send



## Procedure

-  Step 1: Send following details into given api 
-   method: GET
-   Api: https://app.payatom.in/pay/request.php
-   Body : JSON data
```sh
##Example Request Data: 
{
     "pid":"000000000000",
     "amount":"1",
     "order_id":"20"
}

```
#####  You will get a response which has JSON data

```sh

##Example Success Response JSON: 
{ 
"hash_value": "b3JkZXJfaWQ9eW91cl9vcmRlcl9pZCZwaWQ9Z2l2ZW5fbWVyY2hhbnRfaWQmcHVycG9zZT1hbnlfcHVycG9zZSZhbXQ9eW91cl9hbW91bnQmZW1haWw9eW91cmVtYWlsQGV4YW1wbGUuY29t" ,"status": "ok"
}
```


```sh
##Example Failed Response JSON: 
{ 
 "status" : "error", "message" : "Duplicate order_id Found"}
```

if status is 'error' there will be a reason note in 'message' field
``
``
-  Step 2: Append hash_value to connect.php for transfering customer for payment completion  

Now you can redirect customer for payment with payment hash
-   method: GET
-   Api:  https://app.payatom.in/pay/connect.php?code=$hash_value

```sh
 ##Payment request: 
 redirect 
 https://app.payatom.in/pay/connect.php?code=b3JkZXJfaWQ9eW91cl9vcmRlcl9pZCZwaWQ9Z2l2ZW5fbWVyY2hhbnRfaWQmcHVycG9zZT1hbnlfcHVycG9zZSZhbXQ9eW91cl9hbW91bnQmZW1haWw9eW91cmVtYWlsQGV4YW1wbGUuY29t
 ```
  
-    step 3: Launch this url from your app as LaunchMode.externalApplication
```sh
##Kotlin Example: 
val webIntent: Intent = Uri.parse('https://app.payatom.in/pay/connect.php?code=b3JkZXJfaWQ9eW91cl9vcmRlcl9pZCZwaWQ9Z2l2ZW5fbWVyY2hhbnRfaWQmcHVycG9zZT1hbnlfcHVycG9zZSZhbXQ9eW91cl9hbW91bnQmZW1haWw9eW91cmVtYWlsQGV4YW1wbGUuY29t').let { webpage ->Intent(Intent.ACTION_VIEW, webpage)}
```
```sh    
##Java Example: 
Uri webpage = Uri.parse('https://app.payatom.in/pay/connect.php?code=b3JkZXJfaWQ9eW91cl9vcmRlcl9pZCZwaWQ9Z2l2ZW5fbWVyY2hhbnRfaWQmcHVycG9zZT1hbnlfcHVycG9zZSZhbXQ9eW91cl9hbW91bnQmZW1haWw9eW91cmVtYWlsQGV4YW1wbGUuY29t');
Intent webIntent = new Intent(Intent.ACTION_VIEW,webpage);
```
```sh 
##Flutter Example: 
canLaunchUrl(Uri.parse('https://app.payatom.in/pay/connect.php?code=b3JkZXJfaWQ9eW91cl9vcmRlcl9pZCZwaWQ9Z2l2ZW5fbWVyY2hhbnRfaWQmcHVycG9zZT1hbnlfcHVycG9zZSZhbXQ9eW91cl9hbW91bnQmZW1haWw9eW91cmVtYWlsQGV4YW1wbGUuY29t')).then((result) => {   
if(result==true)  
{ launchUrl(Uri.parse(url),mode: LaunchMode.externalApplication) }
else  
{   throw "Could not launch $url"  }   
    
});
```

- Step 5 : Its done  
 
