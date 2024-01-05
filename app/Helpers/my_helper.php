<?php 

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    //Load Composer's autoloader
    require '../vendor/autoload.php';


    date_default_timezone_set("Asia/Bangkok");
    
    function cekValidation($uri){    
        $request = request();

        $secret_key     = trim(getenv("SECRET_KEY"));
        $http_method    = $_SERVER["REQUEST_METHOD"];
        $time           = $request->header('X-Timestamp')->getValue();
        $now            = time();

        $pattern = strtoupper($http_method . ":" . $uri . ":" . $time);
        $signature = hash_hmac('sha256', $pattern, $secret_key);
    
        if ($signature !== $request->header('X-Signature')->getValue()) {
            echo json_encode([
                "status"    => "102",
                "error_message"   => "Invalid Signature.",
                "data"      => null
            ]);
            die();
        } elseif ($now > ((int)$time + getenv('TIMEOUT_SIGNATURE'))) {
            echo json_encode([
                "status"    => "101",
                "error_message"   => "Expired Signature.",
                "data"      => null
            ]);
            die();
        }
    }

    function  generate_signature ($uri, $service=null) {
        $secret_key     = trim(getenv("SECRET_KEY"));
        $http_method    = 'POST';
        $time           = time();

        $pattern = strtoupper($http_method . ":" . $uri . ":" . $time);
        $signature = hash_hmac('sha256', $pattern, $secret_key);

        return [
            "X-Signature" => $signature,
            "X-Timestamp" => $time,
            "Secret-Key"  => $secret_key
        ];

    }

    function cek_session_login() {        
        // $request = request();
        // $session = session();

        // if($request->hasHeader('Authorization')) {
        //     $db = db_connect();
        //     $tokenLogin = $request->header('Authorization')->getValue();
        //     $builder = $db->table('app_users')->where('token_login', $tokenLogin);
        //     $dataUser = $builder->get()->getRow();
        //     $db->close();
        //     if ($dataUser) {
        //         $user = $dataUser;
        //         $session->set('login', $user);
        //         $session->set('token_login', $user->token_login);
        //         $session->set('token_api', $user->token_api);
        //     } else {
        //         echo '{
        //             "code": 1,
        //             "error": "Token is not valid!",
        //             "message": "Token is not valid!",
        //             "data": null
        //         }';
        //         exit();
        //     }
        // } else {
        //     echo '{
        //         "code": 1,
        //         "error": "Token is not valid!",
        //         "message": "Token is not valid!",
        //         "data": null
        //     }';
        //     exit();
        // }
    }

    function cek_token_login($postData) {        
        $request = request();
        // $session = session();

        if(isset($postData['token_login'])) {
            $db = db_connect();
            $tokenLogin = $postData['token_login'];
            // echo getenv('DB_NAME').'.app_users';
            // die();
            $builder = $db->table('app_users')->where('token_login', $tokenLogin);
            $dataUser = $builder->get()->getRow();
            $db->close();
            if ($dataUser) {
                // $user = $dataUser;
                // $session->set('login', $user);
                // $session->set('token_login', $user['token_login']);
                // $session->set('token_api', $user->token_api);

                unset($postData['token_login']);
                return $postData;
            } else {
                echo '{
                    "code": 1,
                    "error": "Token is not valid!",
                    "message": "Token is not valid!",
                    "data": null
                }';
                exit();
            }
        } else {
            echo '{
                "code": 1,
                "error": "Token is not valid!",
                "message": "Token is not valid!",
                "data": null
            }';
            exit();
        }
    }

    function format_rupiah($angka) {
        $rupiah=number_format($angka,0,',','.');
        return $rupiah;
    }

    function curl($url, $isPost=false, $postFields=false, $headers=false) {
        set_time_limit(120);
        ignore_user_abort(false);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, $isPost);
        if ($isPost) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        }

        // In real life you should use something like:
        // curl_setopt($ch, CURLOPT_POSTFIELDS, 
        //          http_build_query(array('postvar1' => 'value1')));
        if ($headers) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        // Receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
        // curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout in seconds
        // curl_setopt($ch, CURLOPT_TIMEOUT_MS, 200);

        
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);

        $server_output = curl_exec($ch);

        // $info = curl_getinfo($ch);
        // print_r($info);

        // $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // $curl_errno= curl_errno($ch);
        // $error_msg = curl_error($ch);
        // echo $url . ' - ' . $http_status;
        // echo "<br/>";
        // echo $curl_errno;
        // echo "<br/>";
        // echo $error_msg;
        // echo "<br/>";
        // if (curl_errno($ch)) {
        //     $error_msg = curl_error($ch);
        //     print_r($error_msg);
        // }

        // $redirect_url = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
        // echo $redirect_url;
        // echo "<br/>";
        // $redirectedUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        // echo $redirectedUrl;
        // echo "<br/>";

        curl_close($ch);

        return $server_output;
        // Further processing ...
        // if ($server_output == "OK") { ... } else { ... }
    }

    function get_services_price0($country) {
        // print_r($country);
        $data0 = json_decode(curl('https://api.sms-activate.org/stubs/handler_api.php?api_key='.getenv('API_SERVICE_KEY').'&action=getPrices&country='.$country));
        // print_r($data0);
        // die();
        $data1 = get_object_vars(get_object_vars($data0)[$country]);
        // print_r($data1);
        // die();
        $dataX = [];
        foreach($data1 as $key => $value) {
            $dataX[$key] = (get_object_vars($value)['cost'] * 1.6).'-'.get_object_vars($value)['count'];
        }
        // print_r($dataX);
        // die();
        return ($dataX);
    }

    function get_services_price($country) {
        // print_r($country);
        $data0 = json_decode(curl('https://api.sms-activate.org/stubs/handler_api.php?api_key='.getenv('API_SERVICE_KEY').'&action=getPrices&country='.$country));
        // print_r($data0);
        // die();
        $data1 = get_object_vars(get_object_vars($data0)[$country]);
        // print_r($data1);
        // die();
        $dataX = [];
        foreach($data1 as $key => $value) {
            $dataX[$key] = (get_object_vars($value)['cost'] * 1).'-'.get_object_vars($value)['count'];
        }
        // print_r($dataX);
        // die();
        return ($dataX);
    }

    function upload_file($_request)
    {   
        $file = $_request->getFile('userfile');
        $validationRule = [
            'userfile' => [
                'label' => 'Image File',
                'rules' => [
                    'uploaded[userfile]',
                    'is_image[userfile]',
                    'mime_in[userfile,image/jpg,image/jpeg,image/gif,image/png,image/webp]',
                    'max_size[userfile,100]',
                    'max_dims[userfile,1024,768]',
                ],
            ],
        ];
        if ($file->getSizeByUnit('mb') > 2) {
            return ['errors' => "File size must < 2mb!"];
        }
        if (
            $file->getMimeType() !== 'image/jpg' &&
            $file->getMimeType() !== 'image/jpeg' &&
            $file->getMimeType() !== 'image/png' &&
            $file->getMimeType() !== 'image/webp'
            ) {
            return ['errors' => "File type must an image!"];
        }

        $newName = $file->getRandomName();
        $x = $file->move(ROOTPATH  . 'public/images', $newName);
       
        $data = ['name' => '/images/'.$newName];
        return $data;
        // return view('upload_form', $data);
    }

    function create_random_captcha() {
        $seed = str_split('abcdefghijklmnopqrstuvwxyz'
                 .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                 .'0123456789'); // and any other characters
        shuffle($seed); // probably optional since array_is randomized; this may be redundant
        $rand = '';
        foreach (array_rand($seed, 6) as $k) $rand .= $seed[$k];
        return $rand;
    }
    
    function getUserIP()
    {
        // Get real visitor IP behind CloudFlare network
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
                $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
                $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP))
        {
            $ip = $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP))
        {
            $ip = $forward;
        }
        else
        {
            $ip = $remote;
        }

        return $ip;
    }

    function maskingString(string $string = NULL) {
        if (!$string) {
            return NULL;
        }
        $length = strlen($string);
        $visibleCount = (int) round($length / 4);
        $hiddenCount = $length - ($visibleCount * 2);
        return substr($string, 0, $visibleCount) . str_repeat('*', $hiddenCount) . substr($string, ($visibleCount * -1), $visibleCount);
    }

    
    function sendMail($toMail=false, $subject='', $message='') {   
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);
        
        try {
            //Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = getenv('SMTP_HOST');                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = getenv('SMTP_USER');                     //SMTP username
            $mail->Password   = getenv('SMTP_PASS');                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
            // $mail->SMTPSecure = getenv('SMTP_TLS');            //Enable implicit TLS encryption
            $mail->Port       = getenv('SMTP_PORT');                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
        
            //Recipients
            $mail->setFrom(getenv('SMTP_USER'), getenv('SMTP_NAME'));
            $mail->addReplyTo(getenv('SMTP_USER'), getenv('SMTP_NAME'));

            if ($toMail) {
                $mail->addAddress($toMail, 'OTP-US User');     //Add a recipient
            } else {
                $mail->addAddress(getenv('SMTP_USER'), 'OTP-US User');     //Add a recipient
            }
            // $mail->addAddress('ellen@example.com');               //Name is optional

            // $mail->addCC('cc@example.com');
            // $mail->addBCC('bcc@example.com');
        
            //Attachments
            // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
        
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $message;
            // $mail->AltBody = $message;

            // print_r($mail);
            // die();
        
            $mail->send();
            // echo 'Message has been sent';
        } catch (Exception $e) {
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }


?>