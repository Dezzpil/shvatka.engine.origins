# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
    config.vm.box = "ubuntu/trusty64"
    config.vm.provision :shell, path: ".provision/bootstrap.sh"
    config.vm.network "forwarded_port", guest: 80, host: 4567
    config.vm.network "forwarded_port", guest: 3306, host: 3307
end