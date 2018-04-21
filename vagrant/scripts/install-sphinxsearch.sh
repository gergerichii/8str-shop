#!/usr/bin/env bash

echo "install Sphinxsearch engine"

# Check if Elasticsearch has been installed

if [ -f /home/vagrant/.sphinxsearch ]
then
    echo "Sphinxsearch already installed."
    exit 0
fi

touch /home/vagrant/.sphinxsearch

# Install Sphinxsearch
sudo apt-get update
sudo apt-get -y install sphinxsearch

#Configure Sphinxsearch
sudo cp -f /tmp/sphinx.conf /etc/sphinxsearch/sphinx.conf
sudo sed -i "s/START=no/START=yes/" /etc/default/sphinxsearch

# Start Sphinxsearch on boot
sudo update-rc.d sphinxsearch defaults 95 10

# Start Sphinxsearch
sudo service sphinxsearch restart
sudo -u sphinxsearch indexer --all
