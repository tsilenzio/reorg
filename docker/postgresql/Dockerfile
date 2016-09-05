FROM postgres:latest

MAINTAINER Taylor Silenzio <tsilenzio@gmail.com>

# Add the config files
ADD conf.d/* /tmp/

# Install configuration files
RUN rm -rf /var/lib/postgresql/data/postgresql.conf \
    && mv /tmp/postgresql.conf /var/lib/postgresql/data/postgresql.conf \
    && chown postgres:postgres /var/lib/postgresql/data/postgresql.conf \
    && chmod 644 /var/lib/postgresql/data/postgresql.conf \
    && rm -rf /var/lib/postgresql/data/pg_hba.conf \
    && mv /tmp/pg_hba.conf /var/lib/postgresql/data/pg_hba.conf \
    && chown postgres:postgres /var/lib/postgresql/data/pg_hba.conf \
    && chmod 644 /var/lib/postgresql/data/pg_hba.conf
