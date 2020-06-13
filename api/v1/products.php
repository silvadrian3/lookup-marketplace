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
    $limit = isset($_GET['limit']) ? 'LIMIT ' . $_GET['limit'] : '';
    $rand = isset($_GET['random']) ? 'ORDER BY RAND()' : '';
    
    if(isset($_GET['id']) && !empty($_GET['id'])){ // read product by id
        $product_id = $_GET['id'];
        $product_id = base64_decode($product_id);    
        $product_id = mysqli_real_escape_string($GLOBALS['connection'], $product_id);
        
        $query = "SELECT a.id as product_id, a.name, a.code, a.unit_price, a.unit_price, a.selling_price, a.price_range_id, a.qty, a.description, c.name as category_name, c.id as category_id, d.id as img_id, d.url as img_url, d.location as img_location, d.file_name as img_filename FROM products as a INNER JOIN client_products as b ON (a.id = b.product_id) INNER JOIN product_categories as c ON (a.category_id = c.id) LEFT JOIN files as d ON (a.image_id = d.id) WHERE a.id = '". $product_id ."' AND a.status = 1 AND c.status = 1";
        
    } else if(isset($_GET['category_id']) && !empty($_GET['category_id'])){ // read product by category
        $category_id = $_GET['category_id'];
        
        if(!is_array($category_id)){
            $whereCat = 'c.id = "'.$category_id.'"';
        } else {
            $category_id = implode(",", $category_id);
            $whereCat = 'c.id IN ('.$category_id.')';
        }
        
        $query = "SELECT a.id as product_id, a.name, a.code, a.unit_price, a.unit_price, a.selling_price, a.price_range_id, a.qty, a.description, c.name as category_name, c.id as category_id, d.id as img_id, d.url as img_url, d.location as img_location, d.file_name as img_filename FROM products as a INNER JOIN client_products as b ON (a.id = b.product_id) INNER JOIN product_categories as c ON (a.category_id = c.id) LEFT JOIN files as d ON (a.image_id = d.id) WHERE b.client_id = '".$client_id."' AND ".$whereCat." AND a.status = 1 " . $rand . ' ' . $limit;
        
    } else { // show all products
        $query = "SELECT a.id as product_id, a.name, a.code, a.unit_price, a.unit_price, a.selling_price, a.price_range_id, a.qty, a.description, c.name as category_name, c.id as category_id, d.id as img_id, d.url as img_url, d.location as img_location, d.file_name as img_filename FROM products as a INNER JOIN client_products as b ON (a.id = b.product_id) INNER JOIN product_categories as c ON (a.category_id = c.id) LEFT JOIN files as d ON (a.image_id = d.id) WHERE b.client_id = '".$client_id."' AND a.status = 1 " . $rand . ' ' . $limit;

    }
    
        $qresult = mysqli_query($GLOBALS['connection'], trim($query));

        if($qresult){
            if(mysqli_num_rows($qresult)!=0){
                while($fetchResult = mysqli_fetch_array($qresult)){
                    $row_array['product_id'] = trim(stripslashes($fetchResult['product_id']));
                    $row_array['category_id'] = trim(stripslashes($fetchResult['category_id']));
                    $row_array['category_name'] = trim(stripslashes($fetchResult['category_name']));
                    $row_array['img_url'] = trim(stripslashes($fetchResult['img_url']));
                    $row_array['img_location'] = trim(stripslashes($fetchResult['img_location']));
                    $row_array['img_filename'] = trim(stripslashes($fetchResult['img_filename']));
                    $row_array['name'] = trim(stripslashes($fetchResult['name']));
                    $row_array['code'] = trim(stripslashes($fetchResult['code']));
                    $row_array['barcode'] = "../api/ctrl/barcode.php?codetype=Code128&text=" . trim(stripslashes($fetchResult['code'])) . "&print=true";
                    $row_array['description'] = trim(stripslashes($fetchResult['description']));
                    $row_array['unit_price'] = trim(stripslashes($fetchResult['unit_price']));
                    $row_array['selling_price'] = trim(stripslashes($fetchResult['selling_price']));
                    $row_array['price_range_id'] = trim(stripslashes($fetchResult['price_range_id']));
                    $row_array['qty'] = trim(stripslashes($fetchResult['qty']));
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