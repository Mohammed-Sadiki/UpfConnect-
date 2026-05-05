<?php
$files = [
    "resources/views/layouts/guest.blade.php",
    "resources/views/dashboard/index.blade.php",
    "resources/views/auth/login.blade.php",
    "resources/views/auth/register.blade.php",
    "database/seeders/DatabaseSeeder.php",
    "app/Http/Controllers/Api/V1/ApiController.php"
];
$replacements = [
    "Ã©" => "é", "Ã¨" => "è", "Ã" => "à", "â" => "à", "Ã‰" => "É",
    "Média" => "Média", "Événement" => "Événement", 
    "PrivÃ©" => "Privé", "CrÃ©er" => "Créer", "Ã " => "à"
];
foreach ($files as $file) {
    $c = file_get_contents($file);
    foreach ($replacements as $k => $v) {
        $c = str_replace($k, $v, $c);
    }
    file_put_contents($file, $c);
}
echo "Fixed";

