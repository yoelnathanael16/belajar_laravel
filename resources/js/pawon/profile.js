(function(P){
const u=P.me();if(!u){location.replace(P.url('masuk.html')+'?next='+encodeURIComponent(P.url('profil.html')));return}
document.getElementById('profRoot').hidden=false;
document.getElementById('av').textContent=u.name[0].toUpperCase();
document.getElementById('pn').textContent=u.name;document.getElementById('pe').textContent=u.email;
const os=P.orders().filter(o=>o.email===u.email).reverse(),proc=o=>o.status==='Menunggu konfirmasi';
document.getElementById('c1').textContent=os.filter(proc).length;document.getElementById('c2').textContent=os.length;
const dt=s=>new Date(s+'T00:00').toLocaleDateString('id-ID',{weekday:'long',day:'numeric',month:'long',year:'numeric'});
const esc=s=>String(s).replace(/[&<>"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));
let tab='p';
function show(){const L=os.filter(o=>tab==='p'?proc(o):!proc(o));
 document.getElementById('orders').innerHTML=L.map(o=>`<article class="ord"><header><h3>${esc(o.ev)}</h3><span class="badge">${esc(o.status)}</span></header><span class="small">Kode Pesanan: ${o.code}</span><div>${dt(o.date)} \u00b7 ${o.time} \u00b7 ${esc(o.g)}</div><details><summary class="small">Lihat detail menu</summary>${o.items.map(i=>`<div>${esc(i.n)} x${i.q}</div>`).join('')}<b>Total ${P.fmt(o.total)}</b></details><a class="btn wa" target="_blank" rel="noopener" href="https://wa.me/${P.WA}?text=${encodeURIComponent('Halo admin, saya ingin menanyakan pesanan '+o.code)}">Hubungi Admin via WhatsApp</a></article>`).join('')||`<p class="small">Belum ada pesanan di sini. <a href="${P.url('booking.html')}">Buat booking baru</a>.</p>`}
document.querySelector('.tabs').onclick=e=>{const b=e.target.closest('button');if(!b)return;tab=b.dataset.t;document.querySelectorAll('.tabs button').forEach(x=>x.classList.toggle('on',x===b));show()};
document.getElementById('logout').onclick=()=>{localStorage.removeItem('pawon_session');location.href=P.url('index.html')};
show();
})(window.Pawon);