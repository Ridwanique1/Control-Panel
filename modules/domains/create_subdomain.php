<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subdomain = trim($_POST["subdomain"]);
    $root_dir = "C:/xampp/htdocs/$subdomain";

    if (!empty($subdomain) && preg_match("/^[a-zA-Z0-9-]+$/", $subdomain)) {
        $vhostConfig = "
<VirtualHost *:80>
    ServerAdmin admin@$subdomain.ridwanique.local
    DocumentRoot \"$root_dir\"
    ServerName $subdomain.ridwanique.local
    <Directory \"$root_dir\">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>";

        file_put_contents("C:/xampp/apache/conf/extra/httpd-vhosts.conf", $vhostConfig, FILE_APPEND);

        if (!file_exists($root_dir)) {
            mkdir($root_dir, 0777, true);
            file_put_contents("$root_dir/index.php", "<?php echo '<h1>Welcome to $subdomain</h1>'; ?>");
        }

        exec("C:/xampp/apache/bin/httpd.exe -k restart");
        echo "Subdomain '$subdomain' created!";
    } else {
        echo "Invalid subdomain name!";
    }
}
?>
