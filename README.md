laravel8+bootstrap+vue-element-admin



git clone https://github.com/TLITMK/ZDistributeSystem.git

composer install

cd vue-admin
npm install

mysql 
grant all privileges on *.* to 'root'@'%' identified by '718040Ti!' with grant option;
flush privileges;

php artisan migrate --seed