#!/usr/bin/env bash
export DEBIAN_FRONTEND=noninteractive
# Check If Maria Has Been Installed

conf="/etc/ssmtp/ssmtp.conf"

sudo apt-get -y install ssmtp
echo edit $conf
echo "root=$1
mailhub=$2
FromLineOverride=$5
AuthUser=$3
AuthPass=$4
AuthMethod=LOGIN
hostname=localhost
UseSTARTTLS=$6" | sudo tee $conf

sudo sed -i 's/^;sendmail_path =$/sendmail_path = \/usr\/sbin\/ssmtp -t/g' /etc/php/5.6/fpm/php.ini
sudo sed -i 's/^;sendmail_path =$/sendmail_path = \/usr\/sbin\/ssmtp -t/g' /etc/php/7.0/fpm/php.ini
sudo sed -i 's/^;sendmail_path =$/sendmail_path = \/usr\/sbin\/ssmtp -t/g' /etc/php/7.1/fpm/php.ini