#!/usr/bin/env bash

dir=`dirname $0`

cp -r ${dir}/coverage /home/services/api.go2yd.com/
cp -r ${dir}/collect_coverage /home/services/api.go2yd.com/htdocs/Website/

echo "zend_extension=/usr/lib64/php/modules/xdebug.so" >> /etc/php.ini
echo "auto_prepend_file=/home/services/api.go2yd.com/coverage/StartRecordCoverage.php" >> /etc/php.ini
echo "apc.shm_size=1024M" >> /etc/php.ini
sed -i 's/php_admin_value\[memory_limit\].*$/php_admin_value[memory_limit] = 1024M/g' /etc/php-fpm.d/api.go2yd.com.conf

nohup sh ${dir}/call_all.sh &
