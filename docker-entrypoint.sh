#!/bin/sh
set -e
sed -i -e "s/{{CLIENT_ID}}/$CLIENT_ID/;s/{{PROJECT_ID}}/$PROJECT_ID/;s/{{CLIENT_SECRET}}/$CLIENT_SECRET/" /src/translator/client_secret.json
exec "$@"
