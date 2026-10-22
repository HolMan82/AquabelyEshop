export const products = [
  { id: 'wave', name: 'Plavky Wave', category: 'Plavky', tag: 'Oblíbené', price: 1290, color: 'Petrolejová', sizes: ['XS', 'S', 'M', 'L', 'XL'], art: 'suit', shade: '#226969', description: 'Jednodílné tréninkové plavky s otevřenými zády. Navržené pro volnost pohybu a každodenní chvíle ve vodě.' },
  { id: 'bloom', name: 'Plavky Bloom', category: 'Plavky', tag: 'Novinka', price: 1490, color: 'Korálová', sizes: ['XS', 'S', 'M', 'L'], art: 'suit', shade: '#d67c71', description: 'Výrazná barva, čistý střih a pohodlí při každém záběru. Plavky pro trénink i radost z plavání.' },
  { id: 'cap', name: 'Čepice Flow', category: 'Vybavení', tag: '', price: 290, color: 'Šalvějová', sizes: ['Univerzální'], art: 'cap', shade: '#94aaa0', description: 'Pružná silikonová čepice v jemném odstínu. Jednoduchý doplněk do každé plavecké tašky.' },
  { id: 'towel', name: 'Ručník Soft', category: 'Doplňky', tag: 'Na každý trénink', price: 690, color: 'Písková', sizes: ['70 × 140 cm'], art: 'towel', shade: '#c0a78b', description: 'Měkký ručník s poutkem v přírodním odstínu. Na okraj bazénu, na pláž i na cestu domů.' },
  { id: 'goggles', name: 'Brýle Clear', category: 'Vybavení', tag: '', price: 590, color: 'Kouřová', sizes: ['Univerzální'], art: 'goggles', shade: '#577c80', description: 'Tréninkové brýle s nastavitelným páskem a měkkým těsněním pro pohodlí ve vodě.' },
  { id: 'bag', name: 'Taška Everyday', category: 'Doplňky', tag: '', price: 890, color: 'Krémová', sizes: ['24 l'], art: 'bag', shade: '#c5b69b', description: 'Prostorná taška na všechno, co patří k vašemu tréninku. S dlouhými uchy pro pohodlné nošení.' }
];
export const money = value => new Intl.NumberFormat('cs-CZ', { style: 'currency', currency: 'CZK', maximumFractionDigits: 0 }).format(value);
export function validCart(value) {
  if (!Array.isArray(value)) return [];
  return value.filter(item => item && products.some(p => p.id === item.id && p.sizes.includes(item.size)) && Number.isInteger(item.quantity) && item.quantity > 0 && item.quantity <= 99);
}
export const total = cart => cart.reduce((sum, item) => sum + (products.find(p => p.id === item.id)?.price || 0) * item.quantity, 0);
export function addItem(cart, id, size) {
  if (!products.some(p => p.id === id && p.sizes.includes(size))) return cart;
  const existing = cart.find(item => item.id === id && item.size === size);
  return existing ? cart.map(item => item === existing ? { ...item, quantity: Math.min(99, item.quantity + 1) } : item) : [...cart, { id, size, quantity: 1 }];
}
