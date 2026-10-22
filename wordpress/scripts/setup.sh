#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")/.."
python3 - <<'PY'
from pathlib import Path
import secrets, os
p=Path('.env')
if not p.exists():
    fd=os.open(p, os.O_WRONLY|os.O_CREAT|os.O_EXCL,0o600)
    with os.fdopen(fd,'w') as f:
        for key in ['DB_PASSWORD','DB_ROOT_PASSWORD','WP_ADMIN_PASSWORD']:
            f.write(key+'='+secrets.token_hex(24)+'\n')
    print('Generated local credentials in wordpress/.env (not displayed).')
PY
mkdir -p .cache
if [ ! -f .cache/woocommerce-11.2.1.zip ]; then
  curl --fail --location --retry 2 https://github.com/woocommerce/woocommerce/releases/download/11.2.1/woocommerce.zip -o .cache/woocommerce-11.2.1.zip
fi
if [ ! -f .cache/woocommerce-gateway-stripe-11.1.1.zip ]; then
  curl --fail --location --retry 2 https://github.com/woocommerce/woocommerce-gateway-stripe/releases/download/11.1.1/woocommerce-gateway-stripe.zip -o .cache/woocommerce-gateway-stripe-11.1.1.zip
fi
# Verify the official release archive; never execute an HTML error response.
python3 - <<'PY'
import zipfile
with zipfile.ZipFile('.cache/woocommerce-11.2.1.zip') as archive:
    assert 'woocommerce/woocommerce.php' in archive.namelist()
PY
chmod -R a+rX theme scripts .cache mu-plugins
docker compose up -d --wait db web
for attempt in $(seq 1 30); do
  if docker compose exec -T web test -f /var/www/html/wp-config.php; then break; fi
  sleep 1
done
docker compose run --rm -T --entrypoint sh cli -c 'if ! wp core is-installed; then wp core install --url="http://127.0.0.1:8080" --title="BellaAquaBella" --admin_user=shopowner --admin_email=owner@example.test --admin_password="$WP_ADMIN_PASSWORD" --skip-email; fi'
docker compose run --rm -T cli eval-file /setup/install.php
docker compose run --rm -T cli theme activate bellaaquabella
docker compose run --rm -T cli plugin is-installed woocommerce || docker compose run --rm -T cli plugin install /packages/woocommerce-11.2.1.zip --activate
docker compose run --rm -T cli plugin activate woocommerce
docker compose run --rm -T cli plugin is-installed woocommerce-gateway-stripe || docker compose run --rm -T cli plugin install /packages/woocommerce-gateway-stripe-11.1.1.zip --activate
docker compose run --rm -T cli plugin activate woocommerce-gateway-stripe
docker compose run --rm -T cli eval-file /setup/catalog.php
curl --fail --silent http://127.0.0.1:8080/ -o /dev/null
printf 'Local WordPress ready. Admin username: shopowner. Password is only in wordpress/.env.\n'
