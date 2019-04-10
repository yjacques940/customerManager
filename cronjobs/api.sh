#/bin/sh

set -e 

touch result.log

curl -X GET "http://webapi/api/Job/NewAppointments" -H "accept: application/json">> result.log
