<?php
// Either pass API credentials through the constructor 
$sdk = new XummSdk($"1e9144b6-adcf-45ac-bc96-930311f872eb", $"Ca2c3da5-fc04-452f-b6a0-7ec68904bc1f");

// Or set them as environment variables. See .env.example for the expected variable names.
// Note: the .env file is mostly applicable when contributing to the SDK itself.  
$sdk = XummSdk();

$sdk->createPayload(
    new Payload(
        transactionBody: [
            'TransactionType' => 'Payment',
            'Destination' => 'rPdvC6ccq8hCdPKSPJkPmyZ4Mi1oG2FFkT',
            'Fee' => '12'
        ],
    )
);
?>
