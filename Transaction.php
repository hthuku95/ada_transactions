<?php include_once "header.php" ?>

<?php

class Transaction
{
    private static $counter = 0;
    
    private $walletSender;
    private $maxFee;
    private $receiverAddresses = [];
    
    public function __construct($walletSender, $maxFee)
    {
        $this->walletSender = $walletSender;
        $this->maxFee = $maxFee;
        self::$counter++;
    }
    
    public function addReceiverAddress($walletReceiver, $amountAda, $nativePolicyId, $nativeAssetName, $amountNative)
    {
        $receiver = [
            'walletReceiver' => $walletReceiver,
            'amountAda' => $amountAda,
            'nativePolicyId' => $nativePolicyId,
            'nativeAssetName' => $nativeAssetName,
            'amountNative' => $amountNative
        ];
        
        $this->receiverAddresses[] = $receiver;
    }
    
    public function generateTransaction()
    {
        $transaction = [
            'walletSender' => $this->walletSender,
            'maxFee' => $this->maxFee,
            'receiverAddresses' => $this->receiverAddresses
        ];
        
        return $transaction;
    }
    
    public function serializeTransaction()
    {
        $serializedTransaction = json_encode($this->generateTransaction());
        
        return $serializedTransaction;
    }
    
    public static function getTransactionCount()
    {
        return self::$counter;
    }
}

?>


<?php include_once "footer.php" ?>