# BellaAquaBella — WordPress + WooCommerce

Lokální vývojová verze obchodu: modrobílá šablona BellaAquaBella podle nového návrhu, dvanáct ukázkových produktů, velikostní varianty, košík, pokladna a skutečná administrace objednávek WooCommerce.

## Lokální spuštění

Je potřeba Docker Engine s Compose, Bash, curl a Python 3. Použijte existující checkout, nový Git worktree není potřeba.

```sh
./wordpress/scripts/setup.sh
```

Skript připraví WordPress, MariaDB, WooCommerce 11.2.1 a oficiální WooCommerce Stripe Gateway 11.1.1. Balíčky stahuje přes ověřované HTTPS z oficiálních GitHub releases. Obrazy kontejnerů jsou připnuté na ověřené digesty. Při opakovaném spuštění zachovává existující produkty a nastavení. Zkopírujte si či zálohujte databázi před většími změnami katalogu.

Web běží na portu 8080 pouze na loopback rozhraní. `/wp-login.php` je přihlášení, `/wp-admin/admin.php?page=wc-orders` správa objednávek. Záhlaví nabízí menu účtu a po přihlášení majitele také odkazy na objednávky a produkty. Kontrola oprávnění probíhá ve WordPressu a WooCommerce.

Lokální jméno správce je `shopowner`. Náhodné heslo vzniká v `wordpress/.env` jako `WP_ADMIN_PASSWORD`. Soubor je ignorovaný Gitem, má oprávnění 600 a nepatří na veřejný server ani do zpráv. Pro ostrý web vytvořte nové účty a nové přístupy. Pouze vlastník hostingu má mít plnou roli administrátora; pro obsluhu objednávek lze přidělit roli `shop_manager`.

## Kontroly

```sh
cd wordpress
docker compose run --rm -T cli eval-file /setup/validate.php
```

Kontrola vytvoří dočasnou objednávku, ověří cenu, uložení a oprávnění majitele a objednávku zase odstraní. Nespouštějte ji na produkci. E-maily jsou ve vývojovém prostředí potlačené souborem `mu-plugins/development.php`.

Zastavení bez smazání dat:

```sh
cd wordpress
docker compose stop
```

Databáze a soubory WordPressu jsou v Docker volumes. `docker compose down -v` je smaže. Nepředpokládejte, že volumes přežijí výměnu cloudového stroje; setup umí novou lokální instalaci obnovit s ukázkovými daty, ale skutečné objednávky by vyžadovaly zálohu databáze a uploads.

## Platba kartou

Oficiální plugin Stripe je aktivní, ale jeho platební metoda je **vypnutá** a nastavená na **testovací režim**. Bez vašeho účtu a testovacích klíčů nelze ověřit skutečný platební tok. Klíče nepatří do Gitu ani do chatu. Připojení proveďte v zabezpečené administraci WooCommerce → Settings → Payments → Stripe. Před ostrým prodejem ověřte testovací platbu, chybnou platbu, 3D Secure, podepsané webhooky a refundaci; teprve potom připojte produkční účet na HTTPS doméně.

Pro lokální ověření objednávek je dostupná metoda „Testovací objednávka — bez platby“ a bezplatná testovací doprava. Nejde o funkční platbu kartou ani o skutečný prodej. Před produkcí obě testovací metody nahraďte skutečnými.

## Pozdější hosting

Hosting zatím nebyl objednán. GitHub Pages může zobrazovat jen statickou ukázku, nikoli WordPress nebo správu objednávek. Pro hotový obchod zvolte WordPress hosting s podporovaným PHP a databází, HTTPS, zálohami a možností obnovy. Docker pro běžný spravovaný WordPress hosting nepotřebujete.

Na nový WordPress nahrajte složku `theme/bellaaquabella` jako šablonu, nainstalujte aktuální kompatibilní WooCommerce a oficiální Stripe plugin a přeneste katalog. Docker compose je pouze pro místní vývoj; nepoužívejte jeho nastavení a přístupy v produkci. Před spuštěním doplňte skutečné produkty, sklad, dopravu, daně dle provozovatele, obchodní a reklamační podmínky, soukromí, kontakty, SMTP a češtinu WordPressu/WooCommerce. Současná lokální instalace má anglické systémové texty a české vlastní texty.

Logo BeAqua je rastrový návrh s průhledností, ne finální vektorový podklad pro tisk. Před tiskovou produkcí jej nechte odborně převést do křivek a ověřte dostupnost názvu značky.


## Nový vzhled katalogu

Záhlaví obsahuje hledání, účet, oblíbené a košík. Úvod má šest dlaždic kategorií a šest doporučených produktů. Archivy produktů mají boční kategorie a nativní cenový filtr WooCommerce. Velikosti v detailu mají podobu tlačítek, která ovládají původní validované varianty WooCommerce. Oblíbené jsou uložené pouze v daném prohlížeči; nesynchronizují se na zákaznický účet.

Skript `scripts/sync-demo-catalog.php` aktualizuje pouze lokální ukázkový katalog podle nového návrhu: ceny, velikosti, kategorie a 12 ilustračních produktů. Existující objednávky zachovává a mimo prostředí `local` odmítne běžet. Setup provede tuto migraci jednou, poté běžné opakované spuštění katalog nepřepisuje. Stripe zůstává vypnutý v testovacím režimu. Obchod neobsahuje smyšlené zákaznické recenze.


## Změna úvodního banneru v administraci

Majitel s rolí administrátora může otevřít **Vzhled → Přizpůsobit → Úvodní banner**. Zde lze vybrat/nahrát fotografii akvabely, volitelné vodní pozadí, změnit oba řádky nadpisu, doprovodný text, text a odkaz tlačítka a ztmavení pod textem. Původní logo se tím nemění. Změny se ukládají tlačítkem **Publikovat**. Použitá úvodní fotografie je generovaná a uložená v šabloně; nevyžaduje žádnou externí obrazovou službu. Po odebrání fotografie zůstává vodní pozadí. Vlastní pozadí je vrstva pod hlavní fotografií a samostatně je vidět po jejím odebrání nebo u jejího okraje.

Lokální PHP nastavení dovoluje obrázky do 8 MB. Produkční hosting může mít jiný limit. Přístupy k této administraci budou veřejně dostupné až po nasazení WordPressu na hosting; GitHub Pages samotné tuto administraci neprovozuje.
