FROM mysql:8

COPY initScripts /docker-entrypoint-initdb.d

COPY mysql.cnf /etc/mysql/conf.d/mysql.cnf

ENV MYSQL_ROOT_PASSWORD='admin'
