FROM alpine:3.9

COPY api.sh /etc/periodic/daily/api.sh

COPY root /etc/crontabs/root

RUN chmod 0644 /etc/periodic/daily/api.sh \
 && chmod 0644 /etc/crontabs/root

CMD crond
