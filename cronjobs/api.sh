#/bin/sh

set -e 

touch result.log

curl -X GET "http://webapi/api/Customers" -H "accept: application/json" >> result.log
