<?php
$clientId = $_POST['clientId'];
$secretKey = $_POST['secretKey'];
$requestId = time();
$amount = $_POST['amount'];
$callback = 'http://localhost/Rest%20API/Jokul%20Shopeepay/index.html';
date_default_timezone_set('UTC');
$path = '/shopeepay-emoney/v2/order';
$url = 'https://api-sandbox.doku.com'.$path;
$timestamp      = date('Y-m-d\TH:i:s\Z');
$waktu = date('Y-m-d-H-i-s');
$invoice = 'INV-'.time();
$Body = array (
'order' =>
    array (
        'amount' => $amount,
        'invoice_number' => $invoice,
        'callback_url' => $callback,
    ),
);
$digest = base64_encode(hash('sha256', json_encode($Body), true));
$abc = "Client-Id:".$clientId ."\n"."Request-Id:".$requestId . "\n"."Request-Timestamp:".$timestamp ."\n"."Request-Target:".$path ."\n"."Digest:".$digest;
$signature = base64_encode(hash_hmac('sha256', $abc, $secretKey, true));
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($Body));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Client-Id:' . $clientId,
    'Request-Id:' . $requestId,
    'Request-Timestamp:' . $timestamp,
    'Signature:' . "HMACSHA256=" . $signature,
));

$response = curl_exec($ch);
curl_close($ch);
$hasil = json_decode($response, true);
$invoicenumber = $hasil['order']['invoice_number'];
$waktutransaksi7 = date('l, d F Y H:i:s', strtotime($timestamp.'+7 hours'));
$waktumundur = date('l, d F Y H:i:s', strtotime($timestamp.'+7 hours +5 minutes'));
$amount = $hasil['order']['amount'];
$hasil_rupiah = "Rp " . number_format($amount,2,',','.');
$shopeeurl = $hasil['shopeepay_payment']['redirect_url_http'];
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Jokul Shopeepay - @ashddq</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <style>
      html, body {
      display: flex;
      justify-content: center;
      font-family: Roboto, Arial, sans-serif;
      font-size: 15px;
      }
      form {
      border: 5px solid #f1f1f1;
      }
      input[type=text], input[type=password] {
      width: 100%;
      padding: 16px 8px;
      margin: 8px 0;
      display: inline-block;
      border: 1px solid #ccc;
      box-sizing: border-box;
      }
      button {
      background-color: #ff7b00;
      color: white;
      padding: 14px 0;
      margin: 10px 0;
      border: none;
      cursor: grabbing;
      width: 100%;
      }
      h1 {
      text-align:center;
      font-size:18;
      }
      button:hover {
      opacity: 0.8;
      }
      .formcontainer {
      text-align: left;
      margin: 24px 50px 12px;
      }
      .container {
      padding: 16px 0;
      text-align:left;
      }
      span.psw {
      float: right;
      padding-top: 0;
      padding-right: 15px;
      }
      @media screen and (max-width: 300px) {
      span.psw {
      display: block;
      float: none;
      }
    </style>
  </head>
  <body>
  <section class="result" id="result">
      <div class="container">
        <div class="row mb-2">
          <div class="col text-center">
          <h1>Transaction Shopeepay Jokul</h1>
            <h3 id="demo"></h3>
            <h3>Invoice Number : <?= $invoicenumber ?></h3>
            <h3>Date : <?= $waktutransaksi7 ?></h3>
            <h3>Amount : <?= $hasil_rupiah ?></h3>
            <h3>Expired Time : <?= $waktumundur ?></h3>
          </div>
        </div>
        </div>
      </div>
      <a href=<?= $shopeeurl ?>><button type="submit">Go to Shopee</button></a>
      <div class="container" style="background-color: #eee">
        <label> <center><a href="https://www.instagram.com/ashddq">@ashddq</a></center>
    </section>
  </body>
</html>
