# BellaAquaBella / BeAqua

Český responzivní prototyp e-shopu s plavkami a vybavením pro akvabely. Ukázkové produkty mají vlastní SVG ilustrace. Úvodní ilustrace bazénu je také lokální SVG. Aplikace nepotřebuje externí obrázky ani fonty.

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

Funguje filtrování, hledání, řazení, detail produktu, výběr velikosti a košík uložený v prohlížeči. Statická ukázka neodesílá osobní údaje. Produkty, ceny a texty jsou návrhové. Prototyp nepřijímá objednávky ani platby a není připravený pro ostrý prodej.

Před prodejem je potřeba doplnit skutečný katalog a fotografie, provozovatele a právní texty, backend objednávek, sklad, dopravu a platební bránu. Ceny a skladovou dostupnost musí při objednávce ověřovat server.


## WordPress a WooCommerce

Nová funkční lokální verze a pokyny k nasazení jsou v [wordpress/README.md](wordpress/README.md). GitHub Pages zobrazuje pouze statickou ukázku vzhledu. Administrace objednávek a plugin platby kartou patří do WordPressu, který vyžaduje hosting s PHP a databází.
