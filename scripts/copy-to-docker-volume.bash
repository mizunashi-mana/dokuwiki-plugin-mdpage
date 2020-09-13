#!/usr/bin/env bash

[ "$DEBUG" = "true " ] && set -x
set -euo pipefail

CID="${CID:-"$(docker-compose ps -q)"}"
if [ -z "$CID" ]; then
    echo "Cannot find docker containers.  Plese exec 'docker-compose up -d'." >&2
    exit 1
fi

for cid_item in $CID; do
    docker cp . $cid_item:/bitnami/dokuwiki/lib/plugins/mdpage
    docker cp assets/sample-page.txt $cid_item:/bitnami/dokuwiki/data/pages/start.txt
done
