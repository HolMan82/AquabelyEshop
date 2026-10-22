import './style.css';
import { products, money, validCart, total, addItem } from './shop.js';

const poolImage = `${import.meta.env.BASE_URL}pool.svg`;

const icons = {
  bag: '<path d="M5 8h14l1 13H4L5 8Z"/><path d="M8 9V6a4 4 0 0 1 8 0v3"/>',
  arrow: '<path d="M4 12h16m-6-6 6 6-6 6"/>',
  wave: '<path d="M2 9c4-6 6 6 10 0s6 6 10 0M2 16c4-6 6 6 10 0s6 6 10 0"/>',
  heart: '<path d="M20 5c-3-3-7 0-8 2-1-2-5-5-8-2-5 5 8 15 8 15S25 10 20 5Z"/>',
  box: '<path d="m3 7 9-5 9 5v11l-9 4-9-4V7Zm0 0 9 5 9-5M12 12v10M7 4l9 5"/>'
};
const icon = name => `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">${icons[name]}</svg>`;
function artwork(p) {
  const shapes = {
    suit: '<path d="M89 40h22l10 52q19 18 38 0l10-52h22l-1 76 12 76-28 45-34-39-34 39-28-45 12-76Z"/><path class="seam" d="m99 43 7 59q34 32 68 0l7-59M80 188q30-3 60 10 30-13 60-10"/>',
    cap: '<path d="M52 177C35 42 230 27 229 177q-85 31-177 0Z"/><path class="seam" d="M60 169q80 25 160 0M143 62q-48 26-51 98"/>',
    towel: '<path d="m76 44 132 13-22 185-132-13Z"/><path class="seam" d="m70 193 119 12M68 204l119 12M85 62l100 10"/><path d="m55 230-2 14m16-12-2 14m16-12-2 14m16-12-2 14m16-12-2 14m16-12-2 14m16-12-2 14m16-12-2 14m16-12-2 14" stroke="currentColor" fill="none"/>',
    goggles: '<path d="M44 117q-32-77 32-64l130 12q55 9 23 70" fill="none" stroke="currentColor" stroke-width="8"/><path d="m33 112 88 4-8 65q-65 20-80-25Zm126 6 88 7-8 43q-30 40-84 13Z"/><path d="M121 137q20-19 36 2" fill="none" stroke="currentColor" stroke-width="10"/>',
    nose: '<path d="M90 190 78 110q-5-70 62-70t62 70l-12 80" fill="none" stroke="currentColor" stroke-width="16"/><ellipse cx="89" cy="190" rx="23" ry="35"/><ellipse cx="191" cy="190" rx="23" ry="35"/>',
    bag: '<path d="M62 101h156l-10 128H72Z"/><path d="M102 112V68q38-44 76 0v44" fill="none" stroke="currentColor" stroke-width="12"/><path class="seam" d="M77 115v100h116M128 167h26"/>'
  };
  return `<svg class="product-art" style="color:${p.shade}" viewBox="0 0 280 280" role="img" aria-label="Ilustrace: ${p.name}"><g fill="currentColor">${shapes[p.art]}</g></svg>`;
}
let cart = [];
try { cart = validCart(JSON.parse(localStorage.getItem('aquabely-cart') || '[]')); } catch {}
let category = 'Vše';
let query = '';
let sort = 'featured';
document.querySelector('#app').innerHTML = `
  <div class="announcement">Malé radosti. Velké chvíle ve vodě. <span>Objevte svět BellaAquaBella ${icon('wave')}</span></div>
  <header class="header"><a class="logo" href="#" aria-label="BellaAquaBella úvod"><img src="${import.meta.env.BASE_URL}beaqua-logo.png" alt="BellaAquaBella"></a><nav aria-label="Hlavní navigace"><a href="#kolekce">Obchod</a><a href="#pribeh">Náš příběh</a><a href="#kontakt">Kontakt</a></nav><button class="cart-button" id="open-cart" aria-label="Otevřít košík">${icon('bag')}<span class="cart-label">Košík</span><b id="cart-count">0</b></button></header>
  <main><section class="hero"><div class="hero-copy"><p class="eyebrow">S LÁSKOU K VODĚ A SYNCHRONIZOVANÉMU PLAVÁNÍ</p><h1>Vše pro<br><em>akvabely.</em></h1><p>Plavky, plavecké pomůcky a malé nezbytnosti. Pro první trénink i velké chvíle nad hladinou.</p><a class="button" href="#kolekce">Objevit kolekci ${icon('arrow')}</a><div class="hero-note"><span>≈</span> Z lásky k vodě. S citem pro detail.</div></div><div class="hero-image"><img src="${poolImage}" alt="Ilustrace tyrkysové hladiny plaveckého bazénu" fetchpriority="high"><div class="image-caption"><span>BELLA VE VODĚ. BELLA V SRDCI.</span><small>A zbytek přijde sám.</small></div><div class="round-stamp">STVOŘENO<br>PRO VODU<br><span>≈</span></div></div></section>
  <section class="benefits" aria-label="Naše hodnoty"><div>${icon('wave')}<span>Pro pohyb bez hranic</span></div><div>${icon('heart')}<span>Vybráno s láskou k plavání</span></div><div>${icon('box')}<span>Od tréninku až na dovolenou</span></div></section>
  <section class="collection section" id="kolekce"><div class="section-heading"><div><p class="eyebrow">VAŠE NOVÁ PLAVECKÁ VÝBAVA</p><h2>Oblíbené pro váš trénink.</h2></div><p>Oblíbené kousky, které s vámi<br>udrží tempo.</p></div><div class="catalog-toolbar"><div class="tabs" aria-label="Kategorie">${['Vše', 'Plavky', 'Plavecké brýle', 'Klipy na nos', 'Plavecké pomůcky', 'Doplňky'].map((c,i) => `<button data-category="${c}" class="${i === 0 ? 'active' : ''}" aria-pressed="${i === 0}">${c}</button>`).join('')}</div><div class="catalog-controls"><input id="search" type="search" placeholder="Hledat kousek…" aria-label="Hledat produkty"><select id="sort" aria-label="Seřadit produkty"><option value="featured">Doporučené</option><option value="asc">Od nejlevnějších</option><option value="desc">Od nejdražších</option></select></div></div><div class="product-grid" id="products"></div><p class="catalog-note">Ukázková kolekce · Ilustrace produktů · Ceny a nabídka jsou návrhové</p></section>
  <section class="story" id="pribeh"><div class="story-art" aria-hidden="true"><span>Najdi svůj<br><em>flow.</em></span><div class="ripple r1"></div><div class="ripple r2"></div><div class="ripple r3"></div></div><div class="story-copy"><p class="eyebrow">VODA NÁS SPOJUJE</p><h2>Více než sport.<br>Kousek vašeho světa.</h2><p>BellaAquaBella je koncept obchodu pro všechny, kdo se ve vodě cítí doma. Pro ranní plavání, společné tréninky i chvíle, kdy si jen chcete vyčistit hlavu.</p><p>Chceme vybírat věci, které dávají smysl. Pohodlné, krásné a připravené na další skok.</p><a class="text-link" href="#kolekce">Objevte kolekci ${icon('arrow')}</a></div></section>
  <section class="contact section" id="kontakt"><p class="eyebrow">POJĎME TO VYTVOŘIT SPOLEČNĚ</p><h2>Každá vlna má svůj začátek.</h2><p>Právě si prohlížíte první návrh e-shopu BellaAquaBella.<br>Skutečné produkty, kontakty a dopravu doplníme před otevřením.</p><span class="demo-label">PROTOTYP · OBJEDNÁVKY ZATÍM NEPŘIJÍMÁME</span></section></main>
  <footer><a class="logo" href="#"><img src="${import.meta.env.BASE_URL}beaqua-logo.png" alt="BellaAquaBella"></a><p>Ve svém živlu. Od prvního tempa.</p><small>© ${new Date().getFullYear()} BellaAquaBella · Návrh e-shopu</small></footer>
  <dialog id="product-dialog" aria-label="Detail produktu"></dialog><dialog id="cart-dialog" aria-label="Nákupní košík"></dialog><div id="toast" role="status" aria-live="polite"></div>`;
const productDialog = document.querySelector('#product-dialog');
const cartDialog = document.querySelector('#cart-dialog');
function renderProducts() {
  let list = products.filter(p => (category === 'Vše' || p.category === category) && `${p.name} ${p.color}`.toLocaleLowerCase('cs').includes(query.toLocaleLowerCase('cs')));
  if (sort !== 'featured') list.sort((a,b) => sort === 'asc' ? a.price-b.price : b.price-a.price);
  document.querySelector('#products').innerHTML = list.length ? list.map(p => `<article class="product-card"><button class="product-image" data-product="${p.id}" aria-label="Zobrazit ${p.name}">${p.tag ? `<span class="tag">${p.tag}</span>` : ''}${artwork(p)}<span class="product-plus" aria-hidden="true">+</span></button><div class="product-info"><div><button class="product-name" data-product="${p.id}">${p.name}</button><p><i style="background:${p.shade}"></i>${p.color}</p></div><strong>${money(p.price)}</strong></div></article>`).join('') : '<p class="empty">Žádný kousek neodpovídá hledání. Zkuste jiný název nebo kategorii.</p>';
}
function persist() {
  try { localStorage.setItem('aquabely-cart', JSON.stringify(cart)); } catch {}
  document.querySelector('#cart-count').textContent = cart.reduce((n,item) => n+item.quantity,0);
}
function showProduct(id) {
  const p = products.find(p => p.id === id);
  productDialog.innerHTML = `<button class="close" aria-label="Zavřít detail">×</button><div class="detail-art">${artwork(p)}</div><div class="detail-copy"><p class="eyebrow">${p.category} / ${p.color}</p><h2>${p.name}</h2><strong class="detail-price">${money(p.price)}</strong><p>${p.description}</p><form id="add-form"><label for="size">Vyberte velikost</label><select name="size" id="size">${p.sizes.map(s=>`<option>${s}</option>`).join('')}</select><button class="button" type="submit">Přidat do košíku ${icon('bag')}</button></form><small>Ukázkový produkt. Prodej zatím není spuštěný.</small></div>`;
  productDialog.querySelector('#add-form').addEventListener('submit', event => {
    event.preventDefault(); cart = addItem(cart,p.id,new FormData(event.target).get('size')); persist(); productDialog.close();
    const toast = document.querySelector('#toast'); toast.textContent = `${p.name} jsou v košíku`; toast.classList.add('visible'); clearTimeout(window.toastTimer); window.toastTimer = setTimeout(()=>toast.classList.remove('visible'),3000);
  });
  productDialog.showModal();
}
function renderCart() {
  cartDialog.innerHTML = `<button class="close" aria-label="Zavřít košík">×</button><p class="eyebrow">VAŠE VÝBAVA</p><h2>Nákupní košík</h2>${cart.length ? `<div class="cart-items">${cart.map((item,index)=>{const p=products.find(p=>p.id===item.id);return `<div class="cart-item"><div class="cart-art">${artwork(p)}</div><div class="cart-item-info"><strong>${p.name}</strong><small>${item.size} · ${p.color}</small><div class="quantity"><button data-quantity="${index}" data-delta="-1" aria-label="Ubrat ${p.name}">−</button><span>${item.quantity}</span><button data-quantity="${index}" data-delta="1" aria-label="Přidat ${p.name}" ${item.quantity>=99?'disabled':''}>+</button><button class="remove" data-remove="${index}" aria-label="Odebrat ${p.name}">Odebrat</button></div></div><strong>${money(p.price*item.quantity)}</strong></div>`}).join('')}</div><div class="cart-total"><span>Celkem za produkty</span><strong>${money(total(cart))}</strong></div><p class="cart-disclaimer">Toto je ukázkový košík. Objednávky a platby zatím nejsou dostupné. Doprava bude doplněna před spuštěním obchodu.</p>` : `<div class="empty-cart">${icon('bag')}<h3>Tady to teprve začíná.</h3><p>Vyberte si něco pro svůj příští trénink.</p></div>`}<button class="button continue">Pokračovat ve výběru ${icon('arrow')}</button>`;
}
document.addEventListener('click', event => {
  const product = event.target.closest('[data-product]'); if (product) showProduct(product.dataset.product);
  const tab = event.target.closest('[data-category]'); if(tab) { category=tab.dataset.category; document.querySelectorAll('[data-category]').forEach(t=>{t.classList.toggle('active',t===tab);t.setAttribute('aria-pressed',String(t===tab));}); renderProducts(); }
  if(event.target.closest('#open-cart')) { renderCart(); cartDialog.showModal(); }
  if(event.target.closest('.close, .continue')) event.target.closest('dialog').close();
  const quantity=event.target.closest('[data-quantity]'); const remove=event.target.closest('[data-remove]');
  if(quantity || remove) {
    if(remove) cart.splice(Number(remove.dataset.remove),1);
    else { const item=cart[Number(quantity.dataset.quantity)];item.quantity=Math.min(99,item.quantity+Number(quantity.dataset.delta));cart=cart.filter(i=>i.quantity>0); }
    persist();renderCart();
  }
});
document.querySelector('#search').addEventListener('input',e=>{query=e.target.value;renderProducts();});
document.querySelector('#sort').addEventListener('change',e=>{sort=e.target.value;renderProducts();});
for(const dialog of [productDialog,cartDialog]) dialog.addEventListener('click',e=>{if(e.target===dialog){const r=dialog.getBoundingClientRect();if(e.clientX<r.left||e.clientX>r.right||e.clientY<r.top||e.clientY>r.bottom)dialog.close();}});
renderProducts();persist();
