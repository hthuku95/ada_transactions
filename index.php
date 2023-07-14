<?php include_once "header.php" ?>

<?php

require_once "Transaction.php";

$t0 = new Transaction('addr_test1qpym7u78vvjt03u4mt3ms9nne9dn9z82dmv3x93al3x2say49htvd6x8ak24ygq2nmnu0tz48lz227cwx3e3t0q0za3qdmx4nh',200000);
$t0->addReceiverAddress('addr_test1qzgq94d7eu9zwnqd992qv0jzg2mhrm79xqey6wlcsdvfwt4yzaukg2vr5uwcn59z40r2hrsmynvc6gvnufx57n7gw9mqxf9zdz',50,'16fdd33c86af604e837ae57d79d5f0f1156406086db5f16afb3fcf51','44474f4c44',0);

$t0->addReceiverAddress('addr_test1qq3arev5srh47000d3fsq406aj4dn0sxec98cydrzratjdjtmt8ux8mzul7xa8ssm3egmmwx7qau4xvgwxhfcxsmaa5s998juh',50,'dc280dbb5381ef28afbab4ca31751f13c29562d376998e907ff1af32','474e5547',0);

echo 'Raw transaction'.'<br>';
echo '<pre>';
var_dump($t0);
echo '<pre>';

echo 'Returned transaction'.'<br>';
echo '<pre>';
var_dump($t0->getTransaction());
echo '<pre>';

echo 'CBOR  Serialized transaction'.'<br>';
echo '<pre>';
var_dump($t0->serializeTransaction());
echo '<pre>';

// Serialize the transaction
$serializedTransaction = $t0->serializeTransaction();
file_put_contents('serializedTransaction.txt',$serializedTransaction);

// Export the serialized transaction to external signing process (e.g., save to a file, send to a remote server, etc.)
file_put_contents('serialized_transaction.txt', hex2bin($serializedTransaction));
?>

<?php include_once "footer.php" ?>