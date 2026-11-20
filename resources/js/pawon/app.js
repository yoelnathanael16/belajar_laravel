window.Pawon=window.Pawon||{};
(function(P){
/* konfigurasi dari Blade: halaman aktif (kunci lama, mis. 'menu.html') dan daftar URL rute */
P.page=document.body.dataset.page||'';
try{P.routes=JSON.parse(document.body.dataset.routes||'{}')}catch(e){P.routes={}}
P.url=f=>{const m=/^([^?#]*)(.*)$/.exec(f);return (P.routes[m[1]]||m[1])+m[2]};
/* hanya izinkan redirect ke URL satu origin */
P.safeNext=n=>{if(!n)return '';try{const x=new URL(n,location.origin);return x.origin===location.origin?x.href:''}catch(e){return ''}};
const get=(k,d)=>{try{return JSON.parse(localStorage.getItem(k))??d}catch(e){return d}};
const set=(k,v)=>localStorage.setItem(k,JSON.stringify(v));
P.get=get;P.set=set;
const hash=s=>btoa(unescape(encodeURIComponent(s)));/* demo only: bukan keamanan sungguhan */
P.users=()=>get('pawon_users',[]);
P.me=()=>{const e=get('pawon_session',null);return P.users().find(u=>u.email===e)||null};
P.orders=()=>get('pawon_orders',[]);
P.WA='6281213865343';
/* navbar */
const slot=document.getElementById('authSlot'),u=P.me(),page=P.page;
if(slot){ if(u) slot.innerHTML='<a class="prof-l'+(page==='profil.html'?' on':'')+'" href="'+P.url('profil.html')+'"><i></i>Profil</a>';
 else if(page==='masuk.html') slot.innerHTML='<a class="btn-nav out" href="'+P.url('masuk.html')+'">Masuk</a>';
 else if(page==='daftar.html') slot.innerHTML='<a class="btn-nav out" href="'+P.url('daftar.html')+'">Daftar</a>';}
const b=document.querySelector('.burger');if(b)b.onclick=()=>{const o=document.getElementById('mainnav').classList.toggle('open');b.setAttribute('aria-expanded',o)};
/* login / daftar */
const f=document.getElementById('authForm');
if(f){ if(u){location.replace(P.url('profil.html'))}
 f.onsubmit=e=>{e.preventDefault();const d=Object.fromEntries(new FormData(f)),err=document.getElementById('err'),us=P.users();
  const bad=m=>{err.textContent=m;err.hidden=false};
  d.email=(d.email||'').trim().toLowerCase();
  if(!/^\S+@\S+\.\S+$/.test(d.email))return bad('Format email belum benar.');
  if(f.dataset.mode==='daftar'){
   if(!d.name.trim())return bad('Nama lengkap wajib diisi.');
   if(d.password.length<6)return bad('Kata sandi minimal 6 karakter.');
   if(us.some(x=>x.email===d.email))return bad('Email ini sudah terdaftar. Silakan masuk.');
   us.push({name:d.name.trim(),email:d.email,pw:hash(d.password),since:new Date().toISOString()});set('pawon_users',us);
  } else {
   const x=us.find(x=>x.email===d.email&&x.pw===hash(d.password));
   if(!x)return bad('Email atau kata sandi salah.');
  }
  set('pawon_session',d.email);
  const nx=new URLSearchParams(location.search).get('next');location.href=P.safeNext(nx)||P.url('profil.html');};
}
})(window.Pawon);