<?php

/**
 * Beispiel-Implementierung / Ticket 5401975
 */

require_once '../class/extension/extension.php';
$e = new \www1601com\df_rp\extension\extension();
$e-> httpAuth();

// Empfohlene Umsetzung gem. E-Mail von DF-MK vom Di 15.12.2015 15:07
$result = $e -> call('bbOrder::readDisposition', ['oeid' => 12, 'view' => 'main', 'return_array' => 1]);

echo "<pre>";
print_r($result);



