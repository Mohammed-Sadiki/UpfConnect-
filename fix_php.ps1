$path = 'C:\Program Files\PHP\current\php.ini'
try {
    $content = [System.IO.File]::ReadAllText($path)
    $content = $content.Replace(";extension=mysqli", "extension=mysqli")
    $content = $content.Replace(";extension=pdo_mysql", "extension=pdo_mysql")
    [System.IO.File]::WriteAllText($path, $content)
    "Success" | Out-File "d:\laravel\uniconnect\fix_log.txt"
} catch {
    $_.Exception.Message | Out-File "d:\laravel\uniconnect\fix_log.txt"
}
