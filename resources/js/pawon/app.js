window.Pawon=window.Pawon||{};
(function(P){
/* konfigurasi dari Blade: halaman aktif (kunci lama, mis. 'menu.html') dan daftar URL rute */
P.page=document.body.dataset.page||'';
try{P.routes=JSON.parse(document.body.dataset.routes||'{}')}catch(e){P.routes={}}
P.url=f=>{const m=/^([^?#]*)(.*)$/.exec(f);return (P.routes[m[1]]||m[1])+m[2]};
const get=(k,d)=>{try{return JSON.parse(localStorage.getItem(k))??d}catch(e){return d}};
const set=(k,v)=>localStorage.setItem(k,JSON.stringify(v));
P.get=get;P.set=set;
/* escape teks sebelum dimasukkan ke innerHTML */
P.esc=s=>String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
/* akun dari session Laravel (data-user di layout), null untuk tamu */
P.me=()=>{try{return JSON.parse(document.body.dataset.user||'null')}catch(e){return null}};
/* pesanan milik akun ini, dikirim Blade di halaman Profil (<script id="pawon-orders">) */
P.orders=()=>{const el=document.getElementById('pawon-orders');return el?JSON.parse(el.textContent):[]};
P.WA='6281213865343';
const b=document.querySelector('.burger');if(b)b.onclick=()=>{const o=document.getElementById('mainnav').classList.toggle('open');b.setAttribute('aria-expanded',o)};
})(window.Pawon);