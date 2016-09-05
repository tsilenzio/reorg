FROM nginx:latest

MAINTAINER Taylor Silenzio <tsilenzio@gmail.com>

# Add the config files
ADD conf.d/* /tmp/

# Install configuration files
RUN rm -f /etc/nginx/nginx.conf \
    && mv /tmp/nginx.conf /etc/nginx/nginx.conf \
    && chown root:root /etc/nginx/nginx.conf \
    && rm -f /etc/nginx/fastcgi.conf \
    && mv /tmp/fastcgi.conf /etc/nginx/fastcgi.conf \
    && chown root:root /etc/nginx/fastcgi.conf \
    && rm -f /etc/nginx/fastcgi_params \
    && mv /tmp/fastcgi_params /etc/nginx/fastcgi_params \
    && chown root:root /etc/nginx/fastcgi_params

# Add persistant volume
VOLUME ["/srv/http"]
