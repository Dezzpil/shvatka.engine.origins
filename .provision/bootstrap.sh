#!/usr/bin/env bash

sudo apt-get update
sudo apt-get install -y nginx php5-fpm php5-mysql 
sudo rm /etc/nginx/sites-enabled/default
sudo cp /vagrant/.provision/nginx.conf /etc/nginx/sites-enabled/default
sudo service nginx restart