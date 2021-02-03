# 说明: 本脚本为docker的启动脚本, 执行此脚本请传入2个参数
# 参数个数为2: $1->环境, $2->端口


if [ $# -lt 1 ]; then
    echo "env cannot empty."
    exit -1
fi

environment=${1}
port=${2}

sysctl -w net.core.somaxconn=65535 net.ipv4.ip_local_port_range='1024 65530' vm.nr_hugepages=512

#close transparent_hugepage
echo never > /sys/kernel/mm/transparent_hugepage/enabled
echo never > /sys/kernel/mm/transparent_hugepage/defrag

#start_script.done is the mark when service start
rm -f /home/services/api.go2yd.com/logs/start_script.done

#load the config file
if [ ! -d "/etc/php-fpm.d/" ]; then
  mkdir /etc/php-fpm.d/
fi
if [ ! -d "/var/run/php-fpm/" ]; then
  mkdir /var/run/php-fpm/
fi
\cp -f /home/services/api.go2yd.com/htdocs/Website/deploy/start_env/php-fpm.conf /etc/php-fpm.conf
\cp -f /home/services/api.go2yd.com/htdocs/Website/deploy/start_env/api.go2yd.com.conf /etc/php-fpm.d/api.go2yd.com.conf

# 宿主机ip不一定为172.17.0.1
if [ -z "${YIDIAN_LOCAL_IP}" ]; then
    printf "\nenv[YIDIAN_LOCAL_IP]=172.17.0.1\n" >> /etc/php-fpm.d/api.go2yd.com.conf
else
    printf "\nenv[YIDIAN_LOCAL_IP]=%s\n" "${YIDIAN_LOCAL_IP}" >> /etc/php-fpm.d/api.go2yd.com.conf
fi

# replace config codes
# if [ "${environment}" != "local" ]; then
#     cnt_env=${environment}
#     cd /home/services/recipe/$cnt_env && sh api.rule && cd -
# fi

# choose the php ini file for different env
if [ X"${environment}" == X"a3-local" ]; then
    mv /home/services/api.go2yd.com/htdocs/Website/deploy/start_env/ini/php-a3-local.ini /etc/php.ini
else
    mv /home/services/api.go2yd.com/htdocs/Website/deploy/start_env/ini/php-local.ini /etc/php.ini
fi

# performance profiler
if [ X"${environment}" == X"a3-local" ]; then
    sed -i 's/^auto_prepend_file.*$/auto_prepend_file = "\/home\/services\/api.go2yd.com\/htdocs\/xhgui\/external\/header.php"/g' /etc/php.ini
fi

if [ -e "/usr/share/zoneinfo/Asia/Shanghai" ];then
    ln -sf /usr/share/zoneinfo/Asia/Shanghai /etc/localtime
fi

#code collect coverage for FT
if [ "$collect_coverage" = "true" ]; then
    sh /home/services/collect_coverage/start_collect_coverage.sh
fi

#set the pool of php-fpm
sed -i "/^\[.*\]$/s#api#${environment}#g" /etc/php-fpm.d/api.go2yd.com.conf
rsyslogd > /dev/null 2>&1

cp -f /home/services/logrotate.conf /etc/logrotate.d/php-fpm

mv /etc/anacrontab /etc/anacrontab.bak  # 取消系统调度logrotate，采用自定义的crontab时间执行
#crontab
nohup /usr/sbin/crond >crond.nohup &
crontab /home/services/crontab.conf

#create the log file, fix dir permissions
if [ ! -d "/etc/php-fpm.d/" ]; then
    mkdir /home/services/api.go2yd.com/logs/
fi
if [ ! -f "/home/services/api.go2yd.com/logs/php-error.log" ];then
    touch /home/services/api.go2yd.com/logs/php-error.log
fi
if [ ! -f "/home/services/api.go2yd.com/logs/access.log" ];then
    touch /home/services/api.go2yd.com/logs/access.log
fi
if [ ! -f "/home/services/api.go2yd.com/logs/fpm-error.log" ];then
    touch /home/services/api.go2yd.com/logs/fpm-error.log
fi
if [ ! -f "/home/services/api.go2yd.com/logs/fpm-slow.log" ];then
    touch /home/services/api.go2yd.com/logs/fpm-slow.log
fi
chmod -R 777 /home/services/api.go2yd.com/logs
chown -R nobody:nobody /home/services/api.go2yd.com/logs

#add apc.php to the web root
cp -f /home/services/api.go2yd.com/htdocs/Website/deploy/start_env/apc.php /home/services/api.go2yd.com/htdocs/Website/debug/apc.php


#delete local_deploy
rm -rf /home/services/api.go2yd.com/htdocs/Website/local_deploy

sed -i "s/service_name: 'api.go2yd.com'/service_name: '${environment}.go2yd.com'/g" filebeat-log.yml
# filebeat start
chmod go-w /home/services/filebeat-log.yml
nohup /filebeat-6.3.1-linux-x86_64/filebeat -c /home/services/filebeat-log.yml &

while true; do
    nohup /usr/sbin/php-fpm -F &
    fpm_pid=$!
    sleep 5
    #docker stop优雅关闭php-fpm
    trap "kill -3 $fpm_pid;exit 0" 15
    #sh api_checker/api_checker.sh ${port} # 检测php-fpm是否启动成功
    if [ "$?" != 0 ]; then
        if [ ! -f /home/services/api.go2yd.com/logs/start_script.done ]; then
            exit 2
        else
            kill $fpm_pid
            sleep 10
        fi
    else
        touch /home/services/api.go2yd.com/logs/start_script.done
    fi
    wait $fpm_pid
    exit 3
done;
