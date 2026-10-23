# BellaAquaBella / BeAqua

Český responzivní prototyp e-shopu s plavkami a vybavením pro akvabely. Ukázkové produkty mají vlastní SVG ilustrace. Úvodní banner funguje bez fotografie, s modrým a tyrkysovým pozadím. Fonty jsou uložené lokálně; aplikace nepotřebuje externí obrázky ani fontové služby.

## Vývoj

Node.js 22.12+ nebo 24, npm.

```sh
npm ci --cache /tmp/aquabely-npm-cache
npm run dev -- --port 5173
```

Kontroly a produkční náhled:

```sh
npm test
npm run build
npm run preview -- --port 4173
```

Aktuální návrh má 12 ukázkových produktů. Funguje hledání, kategorie, filtry typu, barvy a ceny, řazení, oblíbené produkty, detail s velikostmi a množstvím, přepínání bannerů a boční košík uložený v prohlížeči. Na úvodu je šest doporučených produktů; tlačítko Zobrazit vše otevře celý katalog. Statická ukázka neodesílá osobní údaje. Produkty, ceny a texty jsou návrhové. Prototyp nepřijímá objednávky ani platby a není připravený pro ostrý prodej.

Před prodejem je potřeba doplnit skutečný katalog a fotografie, provozovatele a právní texty, backend objednávek, sklad, dopravu a platební bránu. Ceny a skladovou dostupnost musí při objednávce ověřovat server.


## WordPress a WooCommerce

Nová funkční lokální verze a pokyny k nasazení jsou v [wordpress/README.md](wordpress/README.md). GitHub Pages zobrazuje pouze statickou ukázku vzhledu. Administrace objednávek a plugin platby kartou patří do WordPressu, který vyžaduje hosting s PHP a databází.
