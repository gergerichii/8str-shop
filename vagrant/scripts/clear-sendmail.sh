#!/usr/bin/env bash

# Clear The Old sendmail conf

conf="/etc/ssmtp/ssmtp.conf"

sudo apt-get -y purge ssmtp
if [ -e $conf ]
then 
	echo 'remove $conf'
    sudo rm $conf
fi 
sudo apt-get -y autoremove

sudo sed -i 's/sendmail_path =.*$/;sendmail_path =/' /etc/php/5.6/fpm/php.ini
sudo sed -i 's/sendmail_path =.*$/;sendmail_path =/' /etc/php/7.0/fpm/php.ini
sudo sed -i 's/sendmail_path =.*$/;sendmail_path =/' /etc/php/7.1/fpm/php.ini