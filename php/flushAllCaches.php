<?php

date_default_timezone_set("America/Los_Angeles");
echo "Start Cleaning all caches at ... " . date("Y-m-d H:i:s") . "\n\n";

    define('MAGENTO', realpath(dirname(dirname(__DIR__))) . '/Magento');
    require_once MAGENTO . '/app/Mage.php';

    Mage::app('admin')->setUseSessionInUrl(false);
    Mage::getConfig()->init();

    $types = Mage::app()->getCacheInstance()->getTypes();

try 
{
    echo "Cleaning data cache... \n";
    flush();
    foreach ($types AS $type => $data) 
    {
        echo "Removing $type ... ";
        echo Mage::app()->getCacheInstance()->clean($data["tags"]) ? "[OK]" : "[ERROR]";
        echo "\n";
    }
} catch (exception $e) {
    die("[ERROR:" . $e->getMessage() . "]");
}

echo "\n";

try {
    echo "Cleaning stored cache... ";
    flush();
    echo Mage::app()->getCacheInstance()->clean() ? "[OK]" : "[ERROR]";
    echo "\n\n";
} catch (exception $e) {
    die("[ERROR:" . $e->getMessage() . "]");
}

try {
    echo "Cleaning merged JS/CSS...";
    flush();
    Mage::getModel('core/design_package')->cleanMergedJsCss();
    Mage::dispatchEvent('clean_media_cache_after');
    echo "[OK]\n\n";
} catch (Exception $e) {
    die("[ERROR:" . $e->getMessage() . "]");
}

try {
    echo "Cleaning image cache... ";
    flush();
    echo Mage::getModel('catalog/product_image')->clearCache();
    echo "[OK]\n";
} catch (exception $e) {
    die("[ERROR:" . $e->getMessage() . "]");
}
