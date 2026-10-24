# Přesné grafické podklady z uživatelovy předlohy

Rozložení je přestavěné podle webu uprostřed předlohy: bílé záhlaví, modrý celý název BellaAquaBella, šest kategorií v úzké navigaci, celoplošný fotografický banner s bílým textem vlevo, šest kategorií v kruzích a pět hodnot značky. Přihlášení a objednávky ve WooCommerce zůstávají funkční.

Původní logo a fotografie nejsou zatím dostupné jako soubory v prostředí. Nástroj imagegen pro jejich převzetí selhal s chybou `Unknown parameter: reasoning`. Proto nejsou nahrazené odlišnou generovanou grafikou. Uživatel následně výslovně povolil dokončení bez obrázku. Publikovat lze verzi s modrým textovým názvem a modrotyrkysovým bannerem bez fotografie. Neoznačujte ji za přesnou reprodukci původní grafiky.

Potřebné podklady: samostatné původní logo BellaAquaBella (ideálně SVG nebo transparentní PNG), samostatná původní fotografie banneru s akvabelou, případně zdrojový návrh. Brand board vložený do konverzace je vizuální reference; není k dispozici jako lokální soubor.

Po získání podkladů je umístěte do `public/branding/`, nastavte schválené soubory v `src/brand-assets.js` a stejné podklady přidejte do šablony jako `assets/reference-logo.png` a `assets/reference-hero.jpg`. Pro SVG/WebP upravte přípony v šabloně bez konverze na rastrovou náhražku. Potom proveďte build a ověřte rozložení a načtení obrázků v prohlížeči na desktopu i mobilu. Fotografie a původní logo lze doplnit později; veřejná ukázka bez fotografie je nyní uživatelem povolená.


Aktuální návrh byl rozšířen podle poslední uživatelovy předlohy: záhlaví s hledáním a účtem, podrobné kategorie, šest dlaždic, kompaktní karty, oblíbené, detail s tlačítky velikostí a boční košík. Předchozí povolení dokončit veřejnou ukázku bez fotografií nadále platí. Aktuální styly jsou `src/storefront.css` a ve WordPressu `assets/storefront.css` + `assets/woo.css`; staré `reference.css` se již nenačítá. Původní fotografie a logo lze později zapojit přes `src/brand-assets.js` a stejné podklady vložit do šablony. Neoznačujte ilustrační SVG za skutečné produktové fotografie.


Fotografie byla později úspěšně vygenerována. Aktuální výchozí banner je `public/branding/hero-swimmer.png`, shodná kopie v šabloně `assets/hero-swimmer.png`. `src/brand-assets.js` ji načítá do statické ukázky. Ve WordPressu ji vybírá nastavení `bella_hero_image`; kompletní ovládání banneru je v `inc/customizer.php`. Předchozí záznamy o nefunkčním generátoru jsou historické. Hlavní logo zůstalo zachované.
