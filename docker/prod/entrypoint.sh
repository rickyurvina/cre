#!/bin/bash

log(){
	while read line ; do
		echo "`date '+%D %T'` $line"
	done
}

set -e
logfile=/home/LogFiles/entrypoint.log
test ! -f $logfile && mkdir -p /home/LogFiles && touch $logfile
exec > >(log | tee -ai $logfile)
exec 2>&1

set_var_if_null(){
	local varname="$1"
	if [ ! "${!varname:-}" ]; then
		export "$varname"="$2"
	fi
}

set -e
test ! -d "$APP_HOME" && echo "INFO: $APP_HOME not found. creating ..." && mkdir -p "$APP_HOME"
chown -R nginx:nginx $APP_HOME

#echo "Start sshd"
/usr/sbin/sshd

echo 'INFO: starting fpm'
php-fpm -D
echo 'INFO: starting nginx'
/usr/sbin/nginx