<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    //
    public function parse_parameters( $request1, $api_name){
         // dd($api_name);/* 方法名test*/
         return 'controlTest';
    }

    public function save()
    {   
		//echo "12345";
		echo getenv('DB_USERNAME');
        $result=DB::table('users')->insert(
            ["id" => "1b7161ea8542462dbf21db".mt_rand(1,1000000),
                'name' => 'sam',
                'email' => 'sam@mail.com'
            //    'password' => Hash::make("sam1"),
            ]
        );
        echo $result;
    }
	
	public function redis1(Request $Request)
    {   
	   $postdata2 = array(
            'message_type'=>'status_change',
            'message_content'=>array('alg'=>'RSA-OAEP-512-8',
            'value'=>"This is a audi Q8 from T606!!!"));
		$postdatajson = json_encode($postdata2);  
		Redis::set("tutorial-n9".mt_rand(1,1000000), $postdatajson); 
		$test=Redis::get("tutorial-name1");
        echo "Stored string in redis:: " .$test; 
       	
	}
	
    public function sendsbmsasbatch(Request $Request)
    {
         //send message to service bus with token
         $cURL = curl_init();
         $header=array(
              'Content-Type:application/atom+xml;type=entry;charset=utf-8',
              'Authorization:SharedAccessSignature sr=https%3a%2f%2fsbn-tntdv-tmstset01.servicebus.windows.net%2fmagento-tms&sig=sCAAXNaFR75qDB8LqMxi%2Bez6ZDKGIEeezS%2B6e5U5KRk%3D&se=1686690092&skn=magento-tms_send',
            //  'BrokerProperties:{"Label":"M22","State":"Active","TimeToLive":3600}'
          );
          //message content
          $postdata2 = array(
            array('type'=>'order_info'), 
            array('alg'=>'RSA-OAEP-512-8',
             'value'=>"This is a audi Q8 from Tie!!!")
          );
      
    
         //转换为json格式
         $postdatajson = json_encode($postdata2);
         dd($postdatajson);
         curl_setopt($cURL, CURLOPT_URL, "https://tie0502.servicebus.windows.net/magentoq/messages");
         curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($cURL, CURLOPT_HTTPHEADER, $header); 
         curl_setopt($cURL, CURLOPT_POSTFIELDS, $postdatajson);
         curl_setopt($cURL, CURLOPT_POST, true);
         $json_response_data1 = curl_exec($cURL);
         $info = curl_getinfo($cURL);
         curl_close($cURL);
         echo "<pre>";//输出换行，等同于键盘ctrl+u
         print_r($info);
         print_r("The sending message response code is ".$info['http_code']); 
         //如果发送失败，将发送失败的信息（json格式）存入log。
         //页面提供一个功能，将json格式的信息黏贴进去，点击发送可以trigger这段代码再次发送message到service bus queue
         file_put_contents("php://stdout", 'Error(send message failure):  '.$postdatajson."\r\n");
    }
    
    public function jwttoken(Request $Request)
    {
		$token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsIng1dCI6ImpTMVhvMU9XRGpfNTJ2YndHTmd2UU8yVnpNYyIsImtpZCI6ImpTMVhvMU9XRGpfNTJ2YndHTmd2UU8yVnpNYyJ9.eyJhdWQiOiJodHRwczovL3NlcnZpY2VidXMuYXp1cmUubmV0IiwiaXNzIjoiaHR0cHM6Ly9zdHMud2luZG93cy5uZXQvMjQyMmNhOTMtZDExNi00NjZjLWI4NTItMWUyNWY2MzAxMDM0LyIsImlhdCI6MTY1MTU0Njg0MSwibmJmIjoxNjUxNTQ2ODQxLCJleHAiOjE2NTE1NTA3NDEsImFpbyI6IkUyWmdZTGpwZVdqQ3lpYnZpTSszSm54Ty9pczZDUUE9IiwiYXBwaWQiOiIxYWJkYTBmYy1jYzJkLTRjNDQtODUxOC00ZDg1NmU4ZDcwMzQiLCJhcHBpZGFjciI6IjEiLCJpZHAiOiJodHRwczovL3N0cy53aW5kb3dzLm5ldC8yNDIyY2E5My1kMTE2LTQ2NmMtYjg1Mi0xZTI1ZjYzMDEwMzQvIiwib2lkIjoiYjE0OTE2NWEtOWM5Mi00OWQ2LTg4ZWItNTgzYmQzMGQ4NTQ4IiwicmgiOiIwLkFYMEFrOG9pSkJiUmJFYTRVaDRsOWpBUU5Qa09vWUJvZ1QxSnFfa3lsOFR2Ymp5YUFBQS4iLCJzdWIiOiJiMTQ5MTY1YS05YzkyLTQ5ZDYtODhlYi01ODNiZDMwZDg1NDgiLCJ0aWQiOiIyNDIyY2E5My1kMTE2LTQ2NmMtYjg1Mi0xZTI1ZjYzMDEwMzQiLCJ1dGkiOiJRM0dkYlVWaDZVS1ZRdVZOTWVnTkFBIiwidmVyIjoiMS4wIn0.C61IeGF1vpXGCDFy8_IMs9jF0WWbfiriS7UIWCeI29sTcuj-mWeVO5DVdGDXkp6PbxnQ18sGuClbWWbDrGDR7bXx4x07CYLltxoE7nmDdOwGBwjOewgfcnW4jLv419lP_4Oxoe81ewK8qDDyzjzLruAB53AKZy1FsBfOFi8frGOgQ83gcbc6Cm0MU3gby-AQVP3xdoy2kkL5OaOQ7zx80PAbDsfdM9fPhZGVkd2DcYUUoVTfU75BUoOGoAG_llTuYnEvCyqIeisBisQLdhTKv3YlaBVTgmX8VO7MkNFlnKFhU5KSGGYtb7iyjq9CPMlFtjUlImxM8o5NO-40Y2a--Q";
		$Signature=json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $token)[0]))));
		
		$arraySig=(array)$Signature;
		dd($arraySig);
	    echo $arraySig['kid']."             ";
        print_r(json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $token)[1])))));
	}	
    public function sendsbmsas(Request $Request)
    {
         //send message to service bus with token
         $cURL = curl_init();
         $header=array(
              'Content-Type:application/atom+xml;type=entry;charset=utf-8',
              'Authorization:SharedAccessSignature sr=https%3a%2f%2fsbn-tntdv-tmstset01.servicebus.windows.net%2fmagento-tms&sig=sCAAXNaFR75qDB8LqMxi%2Bez6ZDKGIEeezS%2B6e5U5KRk%3D&se=1686690092&skn=magento-tms_send',
              'message_type:order_info_change'
          );
          //require_delivery    new_order   order_status_change order_info_change
          //message content
          $postdata2 = array(
            'message_type'=>'order_info_change',
            'message_content'=>array('alg'=>'RSA-OAEP-512-8',
            'value'=>"This is a audi Q8 from T07!!!"));
         //转换为json格式
         $postdatajson = json_encode($postdata2);
        // dd($postdatajson);
         curl_setopt($cURL, CURLOPT_URL, "https://SBN-TNTDV-TMSTSET01.servicebus.windows.net/magento-tms/messages");
         curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($cURL, CURLOPT_HTTPHEADER, $header); 
         curl_setopt($cURL, CURLOPT_POSTFIELDS, $postdatajson);
         curl_setopt($cURL, CURLOPT_POST, true);
         $json_response_data1 = curl_exec($cURL);
         $info = curl_getinfo($cURL);
         curl_close($cURL);
         echo "<pre>";//输出换行，等同于键盘ctrl+u
         print_r($info);
         print_r("The sending message response code is ".$info['http_code']); 
         //如果发送失败，将发送失败的信息（json格式）存入log。
         //页面提供一个功能，将json格式的信息黏贴进去，点击发送可以trigger这段代码再次发送message到service bus queue
         file_put_contents("php://stdout", 'Error(send message failure):  '.$postdatajson."\r\n");
    }    

    public function receivesbmsas(Request $Request)
    {
        $la_paras = $Request->json()->all();
        // dd($la_paras,typeof($la_paras));
        //获取data(需要decode),message id,locktoken
        foreach ($la_paras as $message){
            $decode_ContentData=base64_decode( $message['ContentData'], $strict = true);
            $messageId=$message['Properties']['MessageId'];
            $LockToken=$message['Properties']['LockToken'];
            file_put_contents("php://stdout", 'MessageId is:  '.$messageId."\r\n");
            // dd(count($la_paras),$decode_ContentData,$la_paras[0]['Properties']['LockToken'],$la_paras[0]['Properties']['MessageId']);
            //写入数据库成功后，调用类中定义删除方法
            $this->deletesbmsas($decode_ContentData,$messageId,$LockToken);
       }
    }

    public function deletesbmsas($decode_ContentData,$messageId,$LockToken)
    {
        $cURL = curl_init();
        $header=array(
            'Authorization:SharedAccessSignature sr=https%3a%2f%2ftie0502.servicebus.windows.net%2fmagentoq&sig=nO39PLmWkAesRLK1VQ8A4NoG2gYcUG7tbHPCgjOYY68%3D&se=2283609685&skn=RootManageSharedAccessKey',
         );
         $messageId=$messageId;
         $LockToken=$LockToken;
        // dd($messageId,$LockToken);
         curl_setopt($cURL, CURLOPT_URL, "https://tie0502.servicebus.windows.net/magentoq/messages/".$messageId."/".$LockToken);
         curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($cURL, CURLOPT_HTTPHEADER, $header); 
         curl_setopt($cURL, CURLOPT_CUSTOMREQUEST, "DELETE");
         $json_response_data1 = curl_exec($cURL);
         $info = curl_getinfo($cURL);
         curl_close($cURL);
         print_r("The delete message response code is ".$info['http_code']."\r\n");
        return 'successfully';
    }
    
	public function sha512(Request $Request)
    {
		$plaintext = "TNT1655407670d3dfc330c54c3f59d3dfc330c54c3f65";
		$sha512test = hash("sha512",$plaintext);
		echo $sha512test;
	}
	//12669454f6746429fc24f2d58296b6031d1278b22dc61eaff9109b963af34b8fae5e6514d579929a9cd5bea7f9e308fb5e7637fa5d54aac061401143fcf2556c
	
	//12669454f6746429fc24f2d58296b6031d1278b22dc61eaff9109b963af34b8fae5e6514d579929a9cd5bea7f9e308fb5e7637fa5d54aac061401143fcf2556c
	
    public function testAES(Request $Request)
    {
       // $plaintext = "message to be encrypted";
      //  $cipher = "aes-256-ctr";
      //  $key="TNT1683394935xxxxxxx";//先被MD5
      //  $ivlen = openssl_cipher_iv_length($cipher);
      //  $iv = openssl_random_pseudo_bytes($ivlen);
      //  $ciphertext = openssl_encrypt($plaintext, $cipher, $key, $options=0, $iv);
     // $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ0cyI6IjEyMzQ1Njc4OTAiLCJwcm9qZWN0X25hbWUiOiJUTVMifQ.keYrWYJCykugycAK-hDm_awsuE5TzozuJdVa76scpvs";
     // print_r(json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $token)[1])))));
     $plaintext = "TNT1653438687d3dfc330c54c3f59d3dfc330c54c3f65";
     $cipher = "aes-256-cbc";
     $key="d3dfc330c54c3f59d3dfc330c54c3f65";
   
    $iv = "c54c3f595a4f31e0";
   
    $ciphertext = openssl_encrypt($plaintext, $cipher, $key, false, $iv);
    //dd($ciphertext);
    $original_plaintext = openssl_decrypt($ciphertext, $cipher, $key, $options=0, $iv);
    echo $ciphertext."\n";
    }    

    public function testAES1(Request $Request)
    {
   
        $key="TNT1683394935xxxxxxx";//先被MD5
        $iv = 'b"ê\x17eÉ \e±Ä+ú2£┬\x13\x0E¼"';
        $ciphertext="bRVB9aclD+nflQggLeKXduQaRSQgrwo=";
        $cipher = "aes-256-ctr";
        $original_plaintext = openssl_decrypt($ciphertext, $cipher, $key, $options=0, $iv);
        dd($original_plaintext);
    }    

    public function handle_new_order(Request $Request)
    {
      $headers = $Request->header(); 
      $la_paras = $Request->json()->all();
     // dd($headers['auth-sign']);
     // echo $Request->ContentData;
     dd(gettype($la_paras['ContentData']));
     dd(json_decode($la_paras['ContentData'])->message_type,(array)(json_decode($la_paras['ContentData'])->message_content));
    } 
     public function create_order(Request $Request)

    {
		//$issue="System Error:The create order function does not work";
		
		
		// file_put_contents("php://stdout", 'create_order-'.$issue."\r\n");
		// error_log('API Error:Some message here.');
		
		
		  Log::debug('an test-123');
		  Log::error('2 test-345');
		  Log::alert('alert-0620 test-345');
		  Log::channel('go3')->critical('Something happened12345!');
		
		
		 return 123; 
      //exception???
	     
	  // level的指定参数？？？
    }
    public function handle_require_delivery(Request $Request)
    {
        return 2;
    } 
    public function handle_status_change(Request $Request)
    {
        return 3;
    } 
    public function info_change(Request $Request)
    {
        return 4;
    } 
}
