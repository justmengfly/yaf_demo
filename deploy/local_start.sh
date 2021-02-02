cd /home/services
mkdir -p api.go2yd.com/logs
chown -R nobody:nobody api.go2yd.com/logs
#rm -f /usr/local/nginx/conf/conf.d/*
rm -f /etc/php-fpm.d/*

#sed -i "s/^.*server_name.*$/server_name $1.go2yd.com;/g" /usr/local/nginx/conf/conf.d/api.go2yd.com.conf
#sed -i "s/proxy_set_header Host XX.go2yd.com;/proxy_set_header Host $1.go2yd.com;/g" /usr/local/nginx/conf/conf.d/api.go2yd.com.conf

#cp /home/services/api.go2yd.com/htdocs/Website/deploy/yidian_template/nginx/api.go2yd.com.conf /usr/local/nginx/conf/conf.d
cp /home/services/api.go2yd.com/htdocs/Website/deploy/yidian_template/api.go2yd.com.conf /etc/php-fpm.d/

nohup /usr/sbin/php-fpm &
nohup /usr/local/nginx/sbin/nginx &