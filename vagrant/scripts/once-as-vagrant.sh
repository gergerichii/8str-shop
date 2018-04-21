#!/usr/bin/env bash

#== Import script args ==

github_token=$(echo "$1")

#== Bash helpers ==

function info {
  echo " "
  echo "--> $1"
  echo " "
}

#== Provision script ==

info "Provision-script user: `whoami`"

info "Configure composer"
composer config --global github-oauth.github.com ${github_token}
echo "Done!"

info "Install project dependencies"
cd ~/sites
composer --no-progress --prefer-dist install

sed -i "s/'username' => 'root',/'username' => 'homestead',/g" environments/dev/common/config/local/main.php
sed -i "s/'password' => '',/'password' => 'secret',/g" environments/dev/common/config/local/main.php

info "Init project"
./init --env=Development --overwrite=y

info "Apply migrations"
./yii migrate --interactive=0
./yii_test migrate --interactive=0

info "Migrate old Base"
./yii oldbase/migrate-old-db-dump
info "Convert old data"
./yii oldbase

info "Enabling colorized prompt for guest console"
sed -i "s/#force_color_prompt=yes/force_color_prompt=yes/" /home/vagrant/.bashrc

info "Prepare MC config"
if [ -f /home/vagrant/.config/mc/panels.ini ]; then
    echo "MC already installed."
else
	if [ ! -d /home/vagrant/.config/mc ]; then
		mkdir -p /home/vagrant/.config/mc;
	fi
	block="
    [New Left Panel]
    display=listing
    reverse=0
    case_sensitive=1
    exec_first=0
    sort_order=name
    list_mode=brief
    brief_cols=2
    user_format=half type name | size | perm
    user_status0=half type name | size | perm
    user_status1=half type name | size | perm
    user_status2=half type name | size | perm
    user_status3=half type name | size | perm
    user_mini_status=1

    [New Right Panel]
    display=listing
    reverse=0
    case_sensitive=1
    exec_first=0
    sort_order=name
    list_mode=brief
    brief_cols=2
    user_format=half type name | size | perm
    user_status0=half type name | size | perm
    user_status1=half type name | size | perm
    user_status2=half type name | size | perm
    user_status3=half type name | size | perm
    user_mini_status=1

    [Dirs]
    current_is_left=true
    "
    echo "$block" > "/home/vagrant/.config/mc/panels.ini"
fi