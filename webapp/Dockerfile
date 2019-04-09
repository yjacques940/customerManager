FROM php:7.2-apache

RUN apt update -qq \
 && apt install unzip wget -qy

WORKDIR /var/www/html/

RUN wget -qO- -O fullcalendar.zip https://github.com/fullcalendar/fullcalendar/releases/download/v4.0.2/fullcalendar-4.0.2.zip \
 && mkdir -p addons \
 && unzip -q fullcalendar.zip 'fullcalendar-4.0.2/packages/*' -d addons \
 && mv addons/fullcalendar-4.0.2/packages/* addons/fullcalendar-4.0.2/ \
 && rm fullcalendar.zip addons/fullcalendar-4.0.2/packages -r

# COPY ./src /var/www/html
