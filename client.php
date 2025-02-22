<?php
function isValidPhoneNumber($phone_number, $customer_id, $api_key) {
    $api_url = "https://rest-ww.telesign.com/v1/phoneid/$phone_number";
    
    /*
    $headers = [
        "Authorization: Basic " . base64_encode("$customer_id:$api_key"),
        "Content-Type: application/x-www-form-urlencoded"
    ];
    */
    $headers = [
        "Accept: application/json",
        "Content-Type: application/json"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_USERPWD, "$customer_id:$api_key");
    curl_setopt($ch, CURLOPT_POSTFIELDS, '{}');

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    /*
    print_r($http_code);
    print_r($response); */
    if ($http_code !== 200) {
        return false; // API request failed
    }

    $data = json_decode($response, true);
    if (!isset($data['numbering']['phone_type'])) {
        return false; // Unexpected API response
    }
    
    $valid_types = ["FIXED_LINE", "MOBILE", "VALID"];
    return in_array(strtoupper($data['numbering']['phone_type']), $valid_types);
}

// Usage example
$phone_number = "16473003668"; // Replace with actual phone number
$customer_id = "xxx";
$api_key = "yyy";
$result = isValidPhoneNumber($phone_number, $customer_id, $api_key);
var_dump($result);