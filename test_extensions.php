<?php
echo "Imagick loaded: " . (extension_loaded('imagick') ? 'YES' : 'NO') . "<br>";
echo "GD loaded: " . (extension_loaded('gd') ? 'YES' : 'NO') . "<br>";
if (extension_loaded('imagick')) {
    $im = new Imagick();
    echo "Imagick version: " . Imagick::getVersion()['versionString'] . "<br>";
}
?>