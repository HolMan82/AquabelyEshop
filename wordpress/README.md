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

Doména `bellaaquabella.cz` je již zakoupená a přiřazená ke stávajícímu hostingu Active24. Instalace WordPressu na této doméně a HTTPS zatím nebyly ověřené. GitHub Pages může zobrazovat jen statickou ukázku, nikoli WordPress nebo správu objednávek. Pro hotový obchod zvolte WordPress hosting s podporovaným PHP a databází, HTTPS, zálohami a možností obnovy. Docker pro běžný spravovaný WordPress hosting nepotřebujete.

Na nový WordPress nahrajte složku `theme/bellaaquabella` jako šablonu, nainstalujte aktuální kompatibilní WooCommerce a oficiální Stripe plugin a přeneste katalog. Docker compose je pouze pro místní vývoj; nepoužívejte jeho nastavení a přístupy v produkci. Před spuštěním doplňte skutečné produkty, sklad, dopravu, daně dle provozovatele, obchodní a reklamační podmínky, soukromí, kontakty, SMTP a češtinu WordPressu/WooCommerce. Současná lokální instalace má anglické systémové texty a české vlastní texty.

Logo BeAqua je rastrový návrh s průhledností, ne finální vektorový podklad pro tisk. Před tiskovou produkcí jej nechte odborně převést do křivek a ověřte dostupnost názvu značky.


## Nový vzhled katalogu

Záhlaví obsahuje hledání, účet, oblíbené a košík. Úvod má šest dlaždic kategorií a šest doporučených produktů. Archivy produktů mají boční kategorie a nativní cenový filtr WooCommerce. Velikosti v detailu mají podobu tlačítek, která ovládají původní validované varianty WooCommerce. Oblíbené jsou uložené pouze v daném prohlížeči; nesynchronizují se na zákaznický účet.

Skript `scripts/sync-demo-catalog.php` aktualizuje pouze lokální ukázkový katalog podle nového návrhu: ceny, velikosti, kategorie a 12 ilustračních produktů. Existující objednávky zachovává a mimo prostředí `local` odmítne běžet. Setup provede tuto migraci jednou, poté běžné opakované spuštění katalog nepřepisuje. Stripe zůstává vypnutý v testovacím režimu. Obchod neobsahuje smyšlené zákaznické recenze.


## Změna úvodního banneru v administraci

Majitel s rolí administrátora může otevřít **Vzhled → Přizpůsobit → Úvodní banner**. Zde lze vybrat/nahrát fotografii akvabely, volitelné vodní pozadí, změnit oba řádky nadpisu, doprovodný text, text a odkaz tlačítka a ztmavení pod textem. Původní logo se tím nemění. Změny se ukládají tlačítkem **Publikovat**. Použitá úvodní fotografie je generovaná a uložená v šabloně; nevyžaduje žádnou externí obrazovou službu. Po odebrání fotografie zůstává vodní pozadí. Vlastní pozadí je vrstva pod hlavní fotografií a samostatně je vidět po jejím odebrání nebo u jejího okraje.

Lokální PHP nastavení dovoluje obrázky do 8 MB. Produkční hosting může mít jiný limit. Přístupy k této administraci budou veřejně dostupné až po nasazení WordPressu na hosting; GitHub Pages samotné tuto administraci neprovozuje.


## Balíček šablony pro hosting

```sh
npm run package:theme
npm run build
```

První příkaz vytvoří `public/downloads/bellaaquabella-theme.zip` a kontrolní součet SHA-256. Archiv obsahuje jen složku šablony, nikoli `.env`, databázi, Docker konfiguraci, lokální účty nebo hesla. Druhý příkaz zahrne balíček do veřejného statického náhledu, odkud jej lze stáhnout.

V nové samostatné instalaci WordPressu na `bellaaquabella.cz` vyberte **Vzhled → Šablony → Instalovat šablonu → Nahrát šablonu** a nahrajte ZIP. Nainstalujte kompatibilní WooCommerce z oficiálního katalogu. Šablona sama nepřenáší produkty ani nastavení databáze; ty připravíme až po zprovoznění cílového WordPressu. Lokální testovací databázi ani místní přihlašovací údaje nekopírujte jako produkční instalaci.

Instalace musí patřit pouze nové doméně, do vlastního webového prostoru a vlastní databáze. Před změnou PHP nebo jiné sdílené volby ověřte, zda se vztahuje pouze na nový web, aby nebyl ovlivněn původní web `atc-astro.eu`. Aktivujte HTTPS pro novou doménu. Není potřeba kupovat další hosting ani žádný tarif WordPress.com.


## Propojení hlavních kategorií (šablona 0.2.0)

Nejprve vytvořte potřebné produktové kategorie přes **Produkty → Kategorie** a v editaci produktů je zaškrtněte. Potom otevřete **Vzhled → Přizpůsobit → Kategorie obchodu**. Každá dlaždice má výběr skutečné WooCommerce kategorie a volitelný vlastní název. Stejné propojení se použije i v horním menu a souvisejícím odkazu kolekce. Výchozí automatické hledání podle původního názvu zůstalo kvůli kompatibilitě. Ručně vybraná kategorie se ukládá svým ID, takže její přejmenování nebo změna URL propojení nerozbije. Prázdný vlastní název přebírá název vybrané kategorie. Obrázek dlaždice se automaticky přebírá z obrázku nastaveného v **Produkty → Kategorie**; bez obrázku zůstává původní ilustrace. Po změnách klikněte **Publikovat**.

Na již nainstalovaném webu nejprve nahrajte ZIP nové verze přes **Vzhled → Šablony → Instalovat šablonu → Nahrát šablonu** a potvrďte nahrazení stávající verze. Není nutné šablonu předem mazat. Produkty, produktové kategorie, média a nastavení banneru jsou v databázi a touto aktualizací se nepřepisují. Přímé změny PHP/CSS souborů mimo child theme by při aktualizaci byly nahrazeny.

Lokální integraci lze ověřit příkazem `docker compose -f wordpress/compose.yaml run --rm -T cli eval-file /setup/validate-category-mapping.php`. Test dočasně vytvoří kategorii, ověří přiřazení, přejmenování a vlastní název a obnoví původní nastavení.


## Seznam značek (šablona 0.3.0)

Položka **Značky** nyní otevírá skutečný seznam značek WooCommerce. Spravují se přes **Produkty → Značky** a přiřazují se produktům v jejich editaci. Seznam obsahuje název, případné logo/náhled, počet produktů a odkaz na nativní archiv dané značky. Zobrazuje i prázdné značky; bez jakýchkoli značek ukáže informaci a odkaz do obchodu. Hledání funguje i bez diakritiky. Žádné značky ani recenze se automaticky nevymýšlejí nebo nepřidávají do databáze.

Po nahrání aktualizace funguje odkaz ihned, bez zakládání stránky nebo ukládání trvalých odkazů. Používá podporovanou veřejnou taxonomii WooCommerce `product_brand`. Volitelně lze vytvořit stránku se slugem `znacky` a vložit `[bella_brands]`; menu pak automaticky použije její pěknou adresu. Pokud instalace WooCommerce správu značek nenabízí, ověřte její verzi a dostupnost funkce značek před doplňováním dat do jiné taxonomie.

Nový ZIP 0.3.0 obsahuje i předchozí propojení kategorií 0.2.0 a nastavení banneru. Produkty a uložená nastavení se nahráním aktualizace nemění. Statický náhled na GitHub Pages má pouze jasně označený ukázkový seznam; skutečné značky vašeho obchodu načítá až WordPress z databáze hostingu.
