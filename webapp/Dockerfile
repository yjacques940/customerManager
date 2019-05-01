FROM php:7.2-apache

RUN apt update -qq \
 && apt install locales unzip wget -qy\
 && apt clean

RUN sed -i -e 's/# fr_CA.UTF-8 UTF-8/fr_CA.UTF-8 UTF-8/' /etc/locale.gen && \
    locale-gen
ENV LANG fr_CA.UTF-8
ENV LANGUAGE fr_CA:fr
ENV LC_ALL fr_CA.UTF-8

WORKDIR /var/www/html/

#RUN wget -qO- -O fullcalendar.zip https://github.com/fullcalendar/fullcalendar/releases/download/v4.0.2/fullcalendar-4.0.2.zip \
# && wget -qO- -O sweetalert2.all.min.js https://cdn.jsdelivr.net/npm/sweetalert2@8.8.2/dist/sweetalert2.all.min.js \
# && mkdir -p addons \
# && unzip -q fullcalendar.zip 'fullcalendar-4.0.2/packages/*' -d addons \
# && mv addons/fullcalendar-4.0.2/packages/* addons/fullcalendar-4.0.2/ \
# && mv sweetalert2.all.min.js addons/ \
# && rm fullcalendar.zip addons/fullcalendar-4.0.2/packages -r

# COPY ./src /var/www/html
