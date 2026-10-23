const icons = {
  user: '<circle cx="12" cy="7" r="3.5"/><path d="M4 21v-2a8 8 0 0 1 16 0v2Z"/>',
  search: '<circle cx="10.5" cy="10.5" r="6.5"/><path d="m16 16 5 5"/>',
  suit: '<path d="M7 3h3l1 6h2l1-6h3v8l2 7-4 4-3-4-3 4-4-4 2-7Z"/>',
  goggles: '<path d="M2 10q-1-7 4-6h12q5 0 4 7M2 10h7l-1 6q-5 3-6-2Zm13 0h7v4q-1 5-7 2Zm-6 2q3-3 6 0"/>',
  nose: '<path d="m7 20-2-10a7 7 0 0 1 14 0l-2 10M5 19l3-1m8 0 3 1"/>',
  towel: '<path d="m6 2 14 2-3 18-14-2Zm-2 15 14 2M6 5l11 1"/>',
  star: '<path d="m12 2 3 7 8 1-6 5 2 8-7-4-7 4 2-8-6-5 8-1Z"/>',
  diamond: '<path d="m2 7 5-5h10l5 5-10 15Zm0 0h20M7 2l5 20 5-20M7 7l5-5 5 5"/>',
  lotus: '<path d="M12 21C4 15 9 4 12 2c3 2 8 13 0 19Zm0 0C3 21 1 13 2 8c6 1 10 6 10 13Zm0 0c9 0 11-8 10-13-6 1-10 6-10 13Z"/>',
  leaf: '<path d="M3 21 20 3M6 18C-2 8 11 3 21 2c-1 10-6 23-15 16Z"/>',
  bag: '<path d="M5 8h14l1 13H4L5 8Z"/><path d="M8 9V6a4 4 0 0 1 8 0v3"/>',
  arrow: '<path d="M4 12h16m-6-6 6 6-6 6"/>',
  wave: '<path d="M2 9c4-6 6 6 10 0s6 6 10 0M2 16c4-6 6 6 10 0s6 6 10 0"/>',
  heart: '<path d="M20 5c-3-3-7 0-8 2-1-2-5-5-8-2-5 5 8 15 8 15S25 10 20 5Z"/>',
  box: '<path d="m3 7 9-5 9 5v11l-9 4-9-4V7Zm0 0 9 5 9-5M12 12v10M7 4l9 5"/>'
};
export const icon = name => `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">${icons[name]}</svg>`;
export function artwork(p) {
  const shapes = {
    suit: '<path d="M89 40h22l10 52q19 18 38 0l10-52h22l-1 76 12 76-28 45-34-39-34 39-28-45 12-76Z"/><path class="seam" d="m99 43 7 59q34 32 68 0l7-59M80 188q30-3 60 10 30-13 60-10"/>',
    cap: '<path d="M52 177C35 42 230 27 229 177q-85 31-177 0Z"/><path class="seam" d="M60 169q80 25 160 0M143 62q-48 26-51 98"/>',
    towel: '<path d="m76 44 132 13-22 185-132-13Z"/><path class="seam" d="m70 193 119 12M68 204l119 12M85 62l100 10"/><path d="m55 230-2 14m16-12-2 14m16-12-2 14m16-12-2 14m16-12-2 14m16-12-2 14m16-12-2 14m16-12-2 14m16-12-2 14" stroke="currentColor" fill="none"/>',
    goggles: '<path d="M44 117q-32-77 32-64l130 12q55 9 23 70" fill="none" stroke="currentColor" stroke-width="8"/><path d="m33 112 88 4-8 65q-65 20-80-25Zm126 6 88 7-8 43q-30 40-84 13Z"/><path d="M121 137q20-19 36 2" fill="none" stroke="currentColor" stroke-width="10"/>',
    nose: '<path d="M90 190 78 110q-5-70 62-70t62 70l-12 80" fill="none" stroke="currentColor" stroke-width="16"/><ellipse cx="89" cy="190" rx="23" ry="35"/><ellipse cx="191" cy="190" rx="23" ry="35"/>',
    pins: '<circle cx="100" cy="100" r="50"/><path d="m160 70 35 120m-20-130 35 120m-70-100 45 110" fill="none" stroke="currentColor" stroke-width="6"/>',
    jar: '<path d="M75 90h130v135H75Z"/><ellipse cx="140" cy="90" rx="65" ry="18"/><path d="M80 130h120v55H80Z" fill="#fff7e7"/>',
    board: '<path d="M78 220V95a62 62 0 0 1 124 0v125Z"/><path d="M100 190V95a40 40 0 0 1 80 0" fill="none" stroke="#ffffff44" stroke-width="3"/>',
    bag: '<path d="M62 101h156l-10 128H72Z"/><path d="M102 112V68q38-44 76 0v44" fill="none" stroke="currentColor" stroke-width="12"/><path class="seam" d="M77 115v100h116M128 167h26"/>'
  };
  return `<svg class="product-art" style="color:${p.shade}" viewBox="0 0 280 280" role="img" aria-label="Ilustrace: ${p.name}"><g fill="currentColor">${shapes[p.art]}</g></svg>`;
}
