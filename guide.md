
curl --cacert .docker/certs/localhost.crt -X POST https://localhost:8000/api/register -H 'Content-Type: application/json' -d '{
  "username": "janedoe",
  "first_name": "Jane",
  "middle_name": "A.",
  "surname": "Doe",
  "email": "jane.doe@example.com",
  "mobile_number": "0987654321",
  "username": "janedoe",
  "password": "password123",
  "password_confirmation": "password123"
}'

curl --cacert .docker/certs/localhost.crt -X POST   https://localhost:8000/api/login   -H 'Content-Type: application/json'   -d '{
  "username": "janedoe",
  "password": "password123"
}'

curl --cacert .docker/certs/localhost.crt -X GET https://localhost:8000/api/user -H 'Accept: application/json' -H 'Authorization: Bearer 9|tMrjoRYJ69Lhs0HY9fScDuSzNZ9jPubqH3evsWHT17edfddd'

curl --cacert .docker/certs/localhost.crt -X POST https://localhost:8000/api/logout -H 'Accept: application/json' -H 'Authorization: Bearer 9|tMrjoRYJ69Lhs0HY9fScDuSzNZ9jPubqH3evsWHT17edfddd'