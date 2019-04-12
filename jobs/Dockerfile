FROM alpine

RUN apk add curl

COPY root /etc/crontabs/root

RUN chmod 0644 /etc/crontabs/root

CMD ["crond", "-f", "-d", "8"]
