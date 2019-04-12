#/bin/sh

set -e 

touch /var/log/api.log

curl -X GET "http://webapi/api/Job/NewAppointments" -H "accept: application/json"
