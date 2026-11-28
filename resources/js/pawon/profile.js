(function(P){
/* pesanan dari database (terbaru dulu); open = menunggu atau diterima */
const os=P.orders(),proc=o=>o.open;
document.getElementById('c1').textContent=os.filter(proc).length;document.getElementById('c2').textContent=os.length;
const dt=s=>new Date(s+'T00:00').toLocaleDateString('id-ID',{weekday:'long',day:'numeric',month:'long',year:'numeric'});
const esc=P.esc;
let tab='p';
function show(){const L=os.filter(o=>tab==='p'?proc(o):!proc(o));
 document.getElementById('orders').innerHTML=L.map(o=>`<article class="ord"><header><h3>${esc(o.ev)}</h3><span class="badge">${esc(o.status)}</span></header><span class="small">Kode Pesanan: ${o.code}</span><div>${dt(o.date)} \u00b7 ${o.time} \u00b7 ${esc(o.g)}</div>${o.reason?`<p class="small">Alasan penolakan: ${esc(o.reason)}</p>`:''}<details><summary class="small">Lihat detail menu</summary>${o.items.map(i=>`<div>${esc(i.n)} x${i.q}</div>`).join('')}<b>Total ${P.fmt(o.total)}</b></details><a class="btn wa" target="_blank" rel="noopener" href="https://wa.me/${P.WA}?text=${encodeURIComponent('Halo admin, saya ingin menanyakan pesanan '+o.code)}">Hubungi Admin via WhatsApp</a></article>`).join('')||`<p class="small">Belum ada pesanan di sini. <a href="${P.url('booking.html')}">Buat booking baru</a>.</p>`}
document.querySelector('.tabs').onclick=e=>{const b=e.target.closest('button');if(!b)return;tab=b.dataset.t;document.querySelectorAll('.tabs button').forEach(x=>x.classList.toggle('on',x===b));show()};
show();
})(window.Pawon);