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
\cp -f `dirname $0`/php-fpm.conf /etc/php-fpm.conf
\cp -f `dirname $0`/api.go2yd.com.conf /etc/php-fpm.d/api.go2yd.com.conf

# 宿主机ip不一定为172.17.0.1
if [ -z "${YIDIAN_LOCAL_IP}" ]; then
    printf "\nenv[YIDIAN_LOCAL_IP]=172.17.0.1\n" >> /etc/php-fpm.d/api.go2yd.com.conf
else
    printf "\nenv[YIDIAN_LOCAL_IP]=%s\n" "${YIDIAN_LOCAL_IP}" >> /etc/php-fpm.d/api.go2yd.com.conf
fi

# replace config codes
if [ "${environment}" != "local" ]; then
    cnt_env=${environment}
    cd /home/services/recipe/$cnt_env && sh api.rule && cd -
fi

# 更新ipip数据
cd /home/services/api.go2yd.com/htdocs/Website/data && wget http://10.103.17.28/ipip/ipdata_ipv6v4_2in1.ipdb.zip && unzip -o ipdata_ipv6v4_2in1.ipdb.zip && rm -f ipdata_ipv6v4_2in1.ipdb.zip && cd -


mv `dirname $0`/ini/php.ini /etc/php.ini


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
#修改anacrontab为每天0到24点运行,延迟5分钟,anacrontab作用为防止crontab运行失败
# sed -i "s/START_HOURS_RANGE=3-22/START_HOURS_RANGE=0-24/g" /etc/anacrontab
# sed -i "s/RANDOM_DELAY=45/RANDOM_DELAY=5/g" /etc/anacrontab
# cp -f /home/services/logrotate.daily /etc/cron.daily/logrotate
# chmod 700 /etc/cron.daily/logrotate

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
cp -f /home/services/apc.php /home/services/api.go2yd.com/htdocs/Website/debug/apc.php

#delete local_deploy
rm -rf /home/services/api.go2yd.com/htdocs/Website/local_deploy

# 根据不同环境修改filebeat配置文件中service_name配置, service_name决定该环境下的日志最终在日志服务器上所在的目录
# php-error.log access.log slow.log仅区分环境
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
    sh api_checker/api_checker.sh ${port} # 检测php-fpm是否启动成功
    if [ "$?" != 0 ]; then
        if [ ! -f /home/services/api.go2yd.com/logs/start_script.done ]; then
            exit 1
        else
            kill $fpm_pid
            sleep 10
        fi
    else
        touch /home/services/api.go2yd.com/logs/start_script.done
    fi
    wait $fpm_pid
    exit 1
done;
