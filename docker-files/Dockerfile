FROM centos:6
MAINTAINER Adrian Oprea<adrian@codesi.nz>

RUN rpm --import https://getfedora.org/static/0608B895.txt  && \
    yum -y install http://rpms.famillecollet.com/enterprise/remi-release-6.rpm && \
    yum update -y && \
    yum install -y sudo tar python-setuptools && \
    yum install -y --enablerepo=remi,remi-php56 php php-fpm && \
    yum clean all && \
    rm -rf /var/cache/yum && \
    easy_install supervisor

RUN mkdir -p /var/log/supervisor
COPY conf/supervisord.conf /etc/supervisord.conf

# WORKDIR /tmp
# RUN curl -O http://download.macromedia.com/pub/adobemediaserver/5_0_8/AdobeMediaServer5_x64.tar.gz
WORKDIR /tmp/ams_latest
COPY conf/installAMS.input installAMS.input

RUN curl http://10.161.81.177/src/AdobeMediaServer5_x64_5_0_15.tar.gz -o ../AdobeMediaServer5_x64.tar.gz && \
    tar zxvf ../AdobeMediaServer5_x64.tar.gz -C . --strip-components=1 && \
    rm ../AdobeMediaServer5_x64.tar.gz && \
    rm -Rf License.txt && \
    sed -i -e 's:read cont < /dev/tty:#read cont < /dev/tty:g' installAMS && \
    ./installAMS < installAMS.input && \
    rm -r *

COPY certs /opt/adobe/certs
COPY conf/Adaptor.xml /opt/adobe/ams/conf/_defaultRoot_/Adaptor.xml

# allow admin commands via http
COPY conf/Users.xml /opt/adobe/ams/conf/Users.xml 

# 設定 Apache 可透過 PHP-FPM 執行 PHP 程式
# 並且允許透過 sudo 執行 get_open_vod_files.php
COPY conf/httpd-php-fpm.conf /opt/adobe/ams/Apache2.4/conf/
RUN echo "Include conf/httpd-php-fpm.conf" >> /opt/adobe/ams/Apache2.4/conf/httpd.conf  && \
    echo "Defaults:apache !requiretty" >> /etc/sudoers && \
    echo "apache ALL=(ALL) NOPASSWD: /opt/adobe/ams/webroot/ajtest/cinfo/bin/get_open_vod_files.php" >> /etc/sudoers

# CLEANUP -- 放在這兒，並不能減少 image size，故 mark 掉
# WORKDIR /tmp
# RUN rm -Rf ams_latest AdobeMediaServer5_x64.tar.gz

# 我用 webroot 的目錄，執行時再 bind 目錄
# VOLUME ["/opt/adobe/ams/applications"]

EXPOSE 80 443 1111 1935

CMD ["/usr/bin/supervisord"]
