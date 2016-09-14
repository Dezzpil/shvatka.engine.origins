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
mysql -u root -ptoor -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY 'toor' WITH GRANT OPTION; FLUSH PRIVILEGES;"
sudo /etc/init.d/mysql restart

# создаем БД и импортируем схему
cat /vagrant/.provision/create.sql | mysql -uroot -ptoor
mysql -uroot -ptoor shvatka < /vagrant/db/shvatka.sql

# ставим сервер, интерпретатор и настраиваем
apt-get install -y nginx php5-fpm php5-mysql
rm /etc/nginx/sites-enabled/default
cp /vagrant/.provision/nginx.conf /etc/nginx/sites-enabled/default
service nginx restart