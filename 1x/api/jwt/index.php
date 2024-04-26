<?php 

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://pacific.localhost/wp-json/wc/v3/products',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',

  CURLOPT_SSL_VERIFYHOST => false,   // Disabled the ssl verification
  CURLOPT_SSL_VERIFYPEER => false,   // Disabled the ssl verification

  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_POSTFIELDS => array('TokenExpiration_token_key' => '45','TokenExpiration_token_secret' => '44'),
  CURLOPT_HTTPHEADER => array(
    'Authorization: Basic Y2tfZWY2NzkxMDUxNmRjODliN2YxNjFkN2Q5NmQyMzRmYjgxNzA4NDJiODpjc182NWNjNDg5NGJmNWQ5OTY2M2QzNDNjN2E1NDZhZmUyMmIwNmI1Yjc0',
    'Cookie: mailchimp_landing_site=http%3A%2F%2Fpacific.localhost%2Foauth1%2Frequest'
  ),
));

$response = curl_exec($curl);

$error = curl_error($curl);

curl_close($curl);
echo $response;



?>

