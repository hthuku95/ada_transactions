<?php include_once "header.php" ?>
<?php
    class Transaction
    {
        private static $counter = 0;
        private $senderAddress;
        private $outputs = [];
        private $maxFee;

        public function __construct($senderAddress,$maxFee)
        {
            $this->senderAddress = $senderAddress;
            $this->maxFee = $maxFee;
            self::$counter++;
        }

        public function addInput($transactionHash, $outputIndex)
        {
            $input = [
                'transaction_hash' => $transactionHash,
                'output_index' => $outputIndex,
            ];
    
            $this->inputs[] = $input;
        }

        public function addOutput($receiverAddress, $amountAda, $policyId = null, $assetName = null, $amountNative = 0)
        {
            $output = [
                'address' => $receiverAddress,
                'amount' => $amountAda,
            ];

            if ($policyId !== null && $assetName !== null) {
                $asset = [
                    'policy_id' => $policyId,
                    'asset_name' => $assetName,
                    'amount' => $amountNative,
                ];
                $output['native_assets'] = [$asset];
            }

            $this->outputs[] = $output;
        }

        public function getTransaction()
        {
            $transaction = [
                'sender' => $this->senderAddress,
                'inputs' => $this->inputs,
                'outputs' => $this->outputs,
                'max_fee' => $this->maxFee,
            ];

            // return json_encode($transaction);
            return $transaction;
        }

        public function serializeTransaction()
        {
            // Convert transaction object to CBOR
            $transactionCbor = $this->encodeCborValue($this->getTransaction());
    
            return $transactionCbor;
        }
    
        private function encodeCborValue($value)
        {
            if (is_int($value)) {
                return $this->encodeCborInt($value);
            } elseif (is_string($value)) {
                return $this->encodeCborString($value);
            } elseif (is_array($value)) {
                return $this->encodeCborArray($value);
            }
    
            return '';
        }
    
        private function encodeCborInt($value)
        {
            if ($value >= 0 && $value <= 23) {
                return chr(0b00000000 | $value);
            } elseif ($value >= 0 && $value <= 255) {
                return chr(0b00000001) . chr($value);
            } elseif ($value >= 0 && $value <= 65535) {
                return chr(0b00000010) . pack('n', $value);
            } elseif ($value >= 0 && $value <= 4294967295) {
                return chr(0b00000011) . pack('N', $value);
            }
    
            return '';
        }
    
        private function encodeCborString($value)
        {
            $length = strlen($value);
    
            if ($length <= 23) {
                return chr(0b01100000 | $length) . $value;
            } elseif ($length <= 255) {
                return chr(0b01100001) . chr($length) . $value;
            } elseif ($length <= 65535) {
                return chr(0b01100010) . pack('n', $length) . $value;
            } elseif ($length <= 4294967295) {
                return chr(0b01100011) . pack('N', $length) . $value;
            }
    
            return '';
        }
    
        private function encodeCborArray($value)
        {
            $length = count($value);
    
            if ($length <= 23) {
                $encoded = chr(0b10000000 | $length);
            } elseif ($length <= 255) {
                $encoded = chr(0b10000001) . chr($length);
            } elseif ($length <= 65535) {
                $encoded = chr(0b10000010) . pack('n', $length);
            } elseif ($length <= 4294967295) {
                $encoded = chr(0b10000011) . pack('N', $length);
            } else {
                return '';
            }
    
            foreach ($value as $item) {
                $encoded .= $this->encodeCborValue($item);
            }
    
            return $encoded;
        }
    
        private function hexToBinary($hex)
        {
            $binary = '';
            $length = strlen($hex);
            for ($i = 0; $i < $length; $i += 2) {
                $binary .= chr(hexdec(substr($hex, $i, 2)));
            }
            return $binary;
        }

        public static function getTransactionCount()
        {
            return self::$counter;
        }
    }

?>
<?php include_once "footer.php" ?>