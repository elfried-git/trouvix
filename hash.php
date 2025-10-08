<?php
$motdepasse = '@KVA1206';
$hash = password_hash($motdepasse, PASSWORD_DEFAULT);
echo $hash;
?>