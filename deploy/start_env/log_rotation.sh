#Usage: sh log_rotation.sh env filename reserved_days
if [ $# -ne 3 ];then
    echo "Arguments is not 3"
    exit 0
fi
env=$1
filename=$2
reserved=$3
cur_date=`date +"%Y-%m-%d"`
log_path=/home/services/${env}.go2yd.com/logs
mv ${log_path}/${filename} ${log_path}/${filename}.${cur_date}
/usr/local/nginx/sbin/nginx -s reload
# /usr/sbin/nginx -s reload
#kill -USR1 `cat /usr/local/nginx/var/nginx.pid`
chown -R services:services /home/services/${env}.go2yd.com
# chown -R nobody:nobody /home/services/${env}.go2yd.com


# clear a log which is just $reserved days ago
rm_date=`date -d "${reserved} days ago" +"%Y-%m-%d"`
rm_log=${log_path}/${filename}.${rm_date}
echo ${rm_log} will be removed
rm -f $rm_log
