#!/usr/bin/env bash

sudo su

apt-get update

# ставим субд с предустановленным паролем
debconf-set-selections <<< 'mysql-server mysql-server/root_password password toor'
debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password toor'
apt-get install -y mysql-server

# настраиваем доступ к субд для обращения с машины-хоста
# https://github.com/AlexDisler/mysql-vagrant/blob/master/install.sh
sudo apt-get install -y vim curl python-software-properties
sed -i "s/^bind-address/#bind-address/" /etc/mysql/my.cnf
mysql -uroot -ptoor -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY 'toor' WITH GRANT OPTION; FLUSH PRIVILEGES;"
sudo /etc/init.d/mysql restart

# создаем БД и импортируем схемы
cat /vagrant/.provision/create.sql | mysql -uroot -ptoor
mysql -uroot -ptoor shvatka < /vagrant/db/shvatka.sql
mysql -uroot -ptoor shvatka_tests < /vagrant/db/shvatka.sql

# создаем пользователя для тестов и назначаем ему права
mysql -uroot -ptoor -e "CREATE USER 'tester'@'%' IDENTIFIED BY 'tester';"
mysql -uroot -ptoor -e "GRANT ALL PRIVILEGES ON shvatka_tests.* TO 'tester'@'%' IDENTIFIED BY 'tester'; FLUSH PRIVILEGES;"

# ставим сервер, интерпретатор и настраиваем
apt-get install -y nginx php5-fpm php5-mysql php5-xdebug phpunit
rm /etc/nginx/sites-enabled/default
cp /vagrant/.provision/nginx.conf /etc/nginx/sites-enabled/default
service nginx restart