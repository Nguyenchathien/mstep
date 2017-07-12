#!/usr/bin/env bash

. ./function.sh

DEVELOP_MODE=`isDevelop`

DEV_PHPDIR_DUC='D:/xampp/htdocs/SpcMstep-master/php/import_weather.php'
DEV_PHPDIR_HIEN='D:/xampp/htdocs/SpcMstep-master/php/import_weather.php'
DEV_PHPDIR_KIYOSAWA='/Applications/XAMPP/htdocs/Mstep/php/import_weather.php'


PHP=$DEV_PHPDIR_DUC
if [ $DEVELOP_MODE == 4 ]; then
    PHP=$DEV_PHPDIR_HIEN
fi

if [ $DEVELOP_MODE == 0 ]; then
    PHP=$DEV_PHPDIR_KIYOSAWA
fi

consoleWait

if [ $? == 1 ]; then
	echo "Cancel"
	exit 1
fi

php -f ${PHP}
exit 0
