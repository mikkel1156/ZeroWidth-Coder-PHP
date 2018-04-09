# Zero-Width Coder PHP
A PHP module to encode/decode text into zero-width characters which will result in them being *"invisible"*.

## Usage example
```
<?php
require_once "zw_coder.php";
$coder = new zw_coder();
$encoded = $coder->encode("TOP-SECRET", "This does so not contain a secret...");
echo "Encoded: " . $encoded;
echo "Decoded: " . $coder->decode($encoded);
?>
```