
<?php 
if(isset($_GET["key"]) || !empty($_GET["key"])) {

    $k = "pirlazlolaGvlWoreyitDoEGzoSdiU547R6uD";
    $key = md5($k);
    $get = $_GET["key"];
    $body = '';
    $firstname = trim($_POST["firstname"]);
    $lastname = trim($_POST["lastname"]);
    $email_address = trim($_POST["email"]);
    $contactno = trim($_POST["contactno"]);
    $message = trim($_POST["message"]);
    $seller = trim($_POST["seller"]);
    $product_name = trim($_POST["product_name"]);
    $bit = true;
    $result = array();

    if($key == $get) {

        if($message != ""){
            $message = nl2br($message);
        } else {
            $message = 'N/A';
        }

        $body .= '<html><body style="font-family:calibri, arial;">';
        $body .= '<p><i>Please do not reply. This is an automated email.</i></p><br/>';
        $body .= '<p>Dear Sales Team,</p><br/>';
        $body .= '<p>'. $firstname . ' ' . $lastname .' is requesting a quotation for the product you posted from Lookup.ph. Please see below details:<br/></p>';
        $body .= '<p>Product Name: <b>' . $product_name . '</b></p>';
        $body .= '<p>Email Address: <b>' . $email_address . '</b></p>';
        $body .= '<p>Contact Number: <b>' . $contactno . '</b></p>';
        $body .= '<p>Message: <br/><b>' . $message . '</b></p><br/>';
        $body .= "<p>Please send a copy of the Quotation on the given contact details.<br/></p>";
        $body .= "<p>If you need additional help, feel free to contact our support team.</p>";
        $body .= "<p>Sincerely,<br/><a href=\"www.lookup.ph\">Lookup.ph</a></p>";
        $body .= "</body></html>";
        
        $from_name = null;
        $from_email = "no-reply@lookup.ph";
        $subject = "Request for Quotation";
        $to_name = null;
        $to_email = "silvadrian3@gmail.com";
        $content_type = "text/html";
        $content_body = $body;
        $bcc1_name = null;
        $bcc1_email = "adrianquijanosilva@gmail.com";
        $success_msg = "Request for Quotation successfully submitted. ".$seller." will contact you shortly.";
        
        include "sender.php";

        if ($bit) {
            $arr_result['result'] = true;
        } else {
            $arr_result['result'] = false;
        }

        $arr_result['message'] = $result_msg;

        array_push($result, $arr_result);
        echo json_encode($result);
    }

}
?>