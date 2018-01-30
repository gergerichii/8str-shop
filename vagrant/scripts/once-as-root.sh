#!/usr/bin/env bash

#== Import script args ==

timezone=$(echo "$1")

#== Bash helpers ==

function info {
  echo " "
  echo "--> $1"
  echo " "
}

#== Provision script ==

info "Provision-script user: `whoami`"

export DEBIAN_FRONTEND=noninteractive

apt-get -y upgrade
apt-get -y dist-upgrade

info "Configure timezone"
timedatectl set-timezone ${timezone} --no-ask-password

info "Install Midnight Commander"
apt-get install -y mc

info "prepare php"
sed -i 's/short_open_tag = Off/short_open_tag = On/g' /etc/php/5.6/fpm/php.ini
sed -i 's/short_open_tag = Off/short_open_tag = On/g' /etc/php/7.0/fpm/php.ini
sed -i 's/short_open_tag = Off/short_open_tag = On/g' /etc/php/7.1/fpm/php.ini
sed -i 's/short_open_tag = Off/short_open_tag = On/g' /etc/php/7.2/fpm/php.ini

ln -s /etc/php/7.0/mods-available/xdebug.ini /etc/php/7.0/cli/conf.d/20-xdebug.ini
ln -s /etc/php/7.1/mods-available/xdebug.ini /etc/php/7.1/cli/conf.d/20-xdebug.ini
ln -s /etc/php/7.2/mods-available/xdebug.ini /etc/php/7.2/cli/conf.d/20-xdebug.ini
ln -s /etc/php/7.0/mods-available/xdebug.ini /etc/php/7.0/fpm/conf.d/20-xdebug.ini
ln -s /etc/php/7.1/mods-available/xdebug.ini /etc/php/7.1/fpm/conf.d/20-xdebug.ini
ln -s /etc/php/7.2/mods-available/xdebug.ini /etc/php/7.2/fpm/conf.d/20-xdebug.ini

if ! grep "xdebug.remote_autostart=1" /etc/php/7.1/fpm/php.ini; then
	cat << EOF >> /etc/php/7.1/mods-available/xdebug.ini
xdebug.remote_autostart=1
EOF
fi

if ! grep "xdebug.remote_autostart=1" /etc/php/7.1/fpm/php.ini; then
	cat << EOF >> /etc/php/7.1/mods-available/xdebug.ini
xdebug.remote_autostart=1
EOF
fi


info "Restart services"
service php5.6-fpm restart; service php7.0-fpm restart; service php7.1-fpm restart; service php7.2-fpm restart;
