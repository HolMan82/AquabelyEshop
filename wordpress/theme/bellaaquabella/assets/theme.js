(function($){
 'use strict';
 // Preserve WooCommerce's server-validated variation selection and its change events.
 $('.variations select').each(function(){
  const select=this,group=document.createElement('div');group.className='variation-buttons';group.setAttribute('role','radiogroup');group.setAttribute('aria-label',select.getAttribute('data-attribute_name')==='attribute_velikost'?'Velikost':'Varianta produktu');
  for(const option of select.options){if(!option.value)continue;const button=document.createElement('button');button.type='button';button.textContent=option.textContent;button.setAttribute('role','radio');button.setAttribute('aria-checked',String(select.value===option.value));button.dataset.value=option.value;button.addEventListener('click',()=>{$(select).val(option.value).trigger('change');});group.append(button);}
  select.after(group);select.classList.add('enhanced-variation');
  const sync=()=>{for(const button of group.children){const option=[...select.options].find(o=>o.value===button.dataset.value);button.disabled=!option||option.disabled;button.setAttribute('aria-checked',String(select.value===button.dataset.value));}};
  $(select).on('change',sync);$(select).closest('form').on('woocommerce_update_variation_values reset_data',sync);
  group.addEventListener('keydown',event=>{if(!['ArrowLeft','ArrowRight','Home','End'].includes(event.key))return;const buttons=[...group.querySelectorAll('button:not(:disabled)')];const index=buttons.indexOf(document.activeElement);if(index<0)return;event.preventDefault();const next=event.key==='Home'?0:event.key==='End'?buttons.length-1:(index+(event.key==='ArrowRight'?1:-1)+buttons.length)%buttons.length;buttons[next].click();buttons[next].focus();});
 });
})(jQuery);
(function(){
 const dialog=document.getElementById('wp-favorites-dialog');if(!dialog)return;
 let favorites=[];
 try{const saved=JSON.parse(localStorage.getItem('bella-wp-favorites')||'[]');if(Array.isArray(saved))favorites=saved.filter(p=>{if(!p||!Number.isInteger(p.id)||typeof p.name!=='string'||typeof p.url!=='string')return false;try{const u=new URL(p.url);return u.origin===location.origin&&['https:','http:'].includes(u.protocol);}catch{return false;}});}catch{}
 function update(){try{localStorage.setItem('bella-wp-favorites',JSON.stringify(favorites));}catch{}document.querySelectorAll('[data-wp-favorite]').forEach(button=>{const selected=favorites.some(p=>p.id===Number(button.dataset.wpFavorite));button.setAttribute('aria-pressed',String(selected));button.classList.toggle('selected',selected);});const list=document.getElementById('wp-favorites-list');list.replaceChildren();if(!favorites.length){const p=document.createElement('p');p.textContent='Zatím nemáte žádné oblíbené produkty.';list.append(p);}for(const product of favorites){const row=document.createElement('div'),link=document.createElement('a'),remove=document.createElement('button');row.className='wp-favorite-row';link.href=product.url;link.textContent=product.name;remove.type='button';remove.textContent='Odebrat';remove.addEventListener('click',()=>{favorites=favorites.filter(p=>p.id!==product.id);update();});row.append(link,remove);list.append(row);}}
 document.addEventListener('click',event=>{const button=event.target.closest('[data-wp-favorite]');if(button){const id=Number(button.dataset.wpFavorite);favorites=favorites.some(p=>p.id===id)?favorites.filter(p=>p.id!==id):[...favorites,{id,name:button.dataset.wpName,url:button.dataset.wpUrl}];update();}});
 document.getElementById('wp-favorites-open').addEventListener('click',()=>{update();dialog.showModal();});document.getElementById('wp-favorites-close').addEventListener('click',()=>dialog.close());update();
})();
document.querySelectorAll('.brand-directory').forEach(directory=>{
 const input=directory.querySelector('[data-brand-search]');if(!input)return;
 const normalize=value=>value.normalize('NFD').replace(/[\u0300-\u036f]/g,'').toLocaleLowerCase('cs');
 input.addEventListener('input',()=>{const query=normalize(input.value);let count=0;directory.querySelectorAll('[data-brand-name]').forEach(card=>{const visible=normalize(card.dataset.brandName).includes(query);card.hidden=!visible;if(visible)count++;});directory.querySelector('.brand-result-count').textContent=`Počet značek: ${count}`;directory.querySelector('.brand-no-results').hidden=count>0;});
});
