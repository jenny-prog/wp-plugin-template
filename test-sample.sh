rm -rf ../wp-sample
php generate-plugin.php
mv wp-sample ../
cd ../wp-sample
composer install
