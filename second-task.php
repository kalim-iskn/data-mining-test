<?php
$file = @fopen("transactions.csv", "r");
$i = 0;
$productsNumbers = array();
$allPurchasingPower = 0;
if ($file) {
    while (($line = fgets($file)) !== false) {
        $productCode = explode(";", $line)[0];
        $productsNumbers[$productCode]++;
        $allPurchasingPower++;
    }
    if (!feof($file)) {
        die("Произошла ошибка");
    }
    fclose($file);
}
arsort($productsNumbers);

foreach ($productsNumbers as $productCode => $purchasingPower) {
    $percent = ($purchasingPower * 100) / $allPurchasingPower;
    echo $productCode . "=" . $purchasingPower . "(" . $percent . "%)<br>";
}
