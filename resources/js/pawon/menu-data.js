/* Helper menu. Item: id,name,desc,price,unit,cat,img,cold,tags */
window.Pawon=window.Pawon||{};
(function(P){
const fmt=n=>'Rp'+n.toLocaleString('id-ID');
/* Data menu dibaca dari resources/data/menu.json (dikirim Blade lewat <script id="pawon-menu">) */
const el=document.getElementById('pawon-menu');
const data=el?JSON.parse(el.textContent):{favorites:[],items:[]};
const DRINK=/Khas|Seger|Ombe/;
P.menu=data.items.map(m=>({...m,drink:DRINK.test(m.cat),weekend:/Weekend/.test(m.cat)}));
P.fav=data.favorites;
P.fmt=fmt;
P.byId=id=>P.menu.find(m=>m.id===id);
P.card=m=>`<article class="item" id="m-${m.id}">${m.img?`<img src="${P.img(m.img)}" alt="${m.name}" loading="lazy">`:`<div class="ph" aria-hidden="true">${m.drink?'\u2615':'\ud83c\udf5b'}</div>`}<h3>${m.name}</h3><p>${m.desc}</p><div class="pr">${m.cold?`${fmt(m.price)} / ${fmt(m.cold)}`:`${fmt(m.price)}/${m.unit}`}</div>${m.weekend?'<span class="tag">Sabtu & Minggu</span>':''}</article>`;
})(window.Pawon);