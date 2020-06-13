<?php 

//include "../ctrl/connection.php";
$GLOBALS['connection'] = $nect;

if(isset($_GET['k']) && !empty($_GET['k'])){
    $k = '0100101001100101011100110111010101110011';
    $cof = md5($k);
    $fee = $_GET['k'];

    if($cof == $fee) {
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
            case 'PUT': _put(); break;
            case 'POST': _post(); break;
            case 'GET': _get(); break;
            case 'DELETE': _delete(); break;
            default: _error('default'); break;
        }
    }
}

function _post(){
    /**
    $result = array();
    $data = array();
    $input = json_decode(file_get_contents('php://input'), true);

    $client_id = mysqli_real_escape_string($GLOBALS['connection'], $_GET['client_id']);
    $modified_by = mysqli_real_escape_string($GLOBALS['connection'], $_GET["ui"]);
    
    isset($input["name"]) ? $name = mysqli_real_escape_string($GLOBALS['connection'], $input["name"]) : $name = "";
    isset($input["category_id"]) ? $category_id = mysqli_real_escape_string($GLOBALS['connection'], $input["category_id"]) : $category_id = "";
    isset($input["description"]) ? $description = mysqli_real_escape_string($GLOBALS['connection'], $input["description"]) : $description = "";
    isset($input["price"]) ? $price = mysqli_real_escape_string($GLOBALS['connection'], $input["price"]) : $price = "";
    isset($input["qty"]) ? $qty = mysqli_real_escape_string($GLOBALS['connection'], $input["qty"]) : $qty = "";
    
    $query = "INSERT INTO `products` (category_id, name, description, price, qty, modified_by, date_modified) VALUES ('". $category_id ."','". $name ."','". $description ."','". $price ."','". $qty ."','". $modified_by ."', '". $server_time ."')";
    
    $qresult = mysqli_query($GLOBALS['connection'], $query);
                    
    if($qresult){
        $product_id = mysqli_insert_id($GLOBALS['connection']);
        
        $query = "INSERT INTO `client_products` (partner_id, product_id) VALUES ('". $client_id ."','". $product_id ."')";
        $qresult = mysqli_query($GLOBALS['connection'], $query);
        if($qresult){
            
            $query = "UPDATE `products` SET code = '".$client_id . $modified_by . $product_id."', date_modified = '". $server_time ."' WHERE id = '".$product_id."'";
            $qresult = mysqli_query($GLOBALS['connection'], $query);
            if($qresult){
                $out['result'] = true;
            } else {
                _error(mysqli_error($GLOBALS['connection']));
            }
            
        } else {
            _error(mysqli_error($GLOBALS['connection']));
        }
        

    } else {
        _error(mysqli_error($GLOBALS['connection']));
    }

    array_push($result, $out);
    echo json_encode($result);
    */
    
    
}

function _get(){
    
    
    $result = array();
    $data = array();
    $client_id = $_GET['client_id'];
    $client_id = mysqli_real_escape_string($GLOBALS['connection'], base64_decode($client_id));
    
    //$url = "http://citiad.co/";
    $url = "";
    
    
    if(isset($_GET['id']) && !empty($_GET['id'])){ // read
        $category_id = $_GET['id'];
        $category_id = base64_decode($category_id);
        $category_id = mysqli_real_escape_string($GLOBALS['connection'], $product_id);

        $query = "SELECT id, name, description FROM `product_categories` WHERE id = '".$category_id."'";
        
    } else { // show all
        $query = "SELECT a.id, a.name, a.description FROM `product_categories` as a INNER JOIN `client_product_categories` as b ON (a.id = b.category_id) WHERE b.client_id = '".$client_id."' AND a.status = 1 AND a.id <> 16";
        
    }
    
    $qresult = mysqli_query($GLOBALS['connection'], $query);

    if($qresult){
        if(mysqli_num_rows($qresult)!=0){
            while($fetchResult = mysqli_fetch_array($qresult)){
                $row_array['id'] = trim(stripslashes($fetchResult['id']));
                $row_array['name'] = trim(stripslashes($fetchResult['name']));
                $row_array['description'] = trim(stripslashes($fetchResult['description']));
                array_push($data, $row_array);    
            }

            $out['result'] = true;
            $out['data'] = $data;
        } else {
            $out['result'] = false;
            $out['message'] = 'No result found. ';
        }

    } else {
        _error(mysqli_error($GLOBALS['connection']));
    }
    
    array_push($result, $out);
    echo json_encode($result[0]);
    
}

function _put(){
    /**
    include "../ctrl/con.php";
    
    $result = array();
    $data = array();
    $input = json_decode(file_get_contents('php://input'), true);

    $client_id = mysqli_real_escape_string($GLOBALS['connection'], $_GET['client_id']);
    $modified_by = mysqli_real_escape_string($GLOBALS['connection'], $_GET["ui"]);
    
    isset($input["name"]) ? $name = mysqli_real_escape_string($GLOBALS['connection'], $input["name"]) : $name = "";
    isset($input["category_id"]) ? $category_id = mysqli_real_escape_string($GLOBALS['connection'], $input["category_id"]) : $category_id = "";
    isset($input["description"]) ? $description = mysqli_real_escape_string($GLOBALS['connection'], $input["description"]) : $description = "";
    isset($input["price"]) ? $price = mysqli_real_escape_string($GLOBALS['connection'], $input["price"]) : $price = "";
    isset($input["qty"]) ? $qty = mysqli_real_escape_string($GLOBALS['connection'], $input["qty"]) : $qty = "";
    
    if(isset($_GET['id']) && !empty($_GET['id'])){
        $product_id = mysqli_real_escape_string($GLOBALS['connection'], $_GET['id']);
        $query = "UPDATE `products` SET category_id = '" . $category_id . "',  name = '" . $name . "', description = '" . $description . "', price = '" . $price . "', qty = '" . $qty . "', modified_by = '". $modified_by ."', date_modified = '". $server_time ."' WHERE id = '" . $product_id . "'";
    }
    
    $qresult = mysqli_query($GLOBALS['connection'], $query);
                    
    if($qresult){
        $out['result'] = true;
    } else {
        _error(mysqli_error($GLOBALS['connection']));
    }

    array_push($result, $out);
    echo json_encode($result);
    */
}

function _delete(){
    /**
    include "../ctrl/con.php";
    $result = array();
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    isset($_GET["id"]) ? $product_id = mysqli_real_escape_string($GLOBALS['connection'], $_GET["id"]) : $product_id = "";
    $modified_by = $_GET['ui'];
    
    $query = "UPDATE `products` SET status = '0', modified_by = '". $modified_by ."', date_modified = '". $server_time ."' WHERE id = '".$product_id."'";
    $qresult = mysqli_query($GLOBALS['connection'], $query);
    
    if($qresult){
        $out['result'] = true;
    } else {
        _error(mysqli_error($GLOBALS['connection']));
        }

    array_push($result, $out);
    echo json_encode($result);
    */
}

function _error($m){
    $result = array();
    $out['result'] = false;
    $out['error'] = $m;
    
    array_push($result, $out);
    echo json_encode($result);
    die();
}


?>