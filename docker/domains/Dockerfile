FROM debian:jessie

MAINTAINER Taylor Silenzio <tsilenzio@gmail.com>

# Add nginx configuration files
ADD conf.d/ /etc/nginx/conf.d/

# Add persistant volume
VOLUME ["/etc/nginx/conf.d/"]
