(function(P){
/* pesanan dari database (terbaru dulu); open = menunggu atau diterima */
const os=P.orders(),proc=o=>o.open;
const nOpen=os.filter(proc).length;
document.getElementById('c1').textContent=nOpen;document.getElementById('c2').textContent=os.length;
document.getElementById('n1').textContent=nOpen;document.getElementById('n2').textContent=os.length-nOpen;
const dt=s=>new Date(s+'T00:00').toLocaleDateString('id-ID',{weekday:'long',day:'numeric',month:'long',year:'numeric'});
const esc=P.esc;
/* ikon acara seperti di desain Figma */
const IC={'Ulang Tahun':'🎂','Acara Kantor / Organisasi':'💼','Reuni / Kumpul Keluarga':'👪','Lainnya':'✨'};
const porsi=o=>o.items.reduce((a,i)=>a+i.q,0);
let tab='p';
function show(){const L=os.filter(o=>tab==='p'?proc(o):!proc(o));
 document.getElementById('orders').innerHTML=L.map(o=>`<article class="ord">
  <header><span class="oic" aria-hidden="true">${IC[o.ev]||'🍽️'}</span><div><h3>${esc(o.ev)}</h3><span class="small">Kode Pesanan: ${o.code}</span></div><span class="badge">${esc(o.status)}</span></header>
  <div class="meta"><span>Tanggal <b>${dt(o.date)}</b></span><span>Jam <b>${o.time}</b></span><span>Tamu <b>${esc(o.g)}</b></span></div>
  ${o.reason?`<p class="reason">Alasan penolakan: ${esc(o.reason)}</p>`:''}
  <details><summary class="small">Lihat detail menu (${porsi(o)} porsi)</summary>${o.items.map(i=>`<div>${esc(i.n)} x${i.q}</div>`).join('')}<b>Total ${P.fmt(o.total)}</b></details>
  <a class="btn wa" target="_blank" rel="noopener" href="https://wa.me/${P.WA}?text=${encodeURIComponent('Halo admin, saya ingin menanyakan pesanan '+o.code)}">Hubungi Admin via WhatsApp</a></article>`).join('')||`<p class="small">Belum ada pesanan di sini. <a href="${P.url('booking.html')}">Buat booking baru</a>.</p>`}
document.querySelector('.tabs').onclick=e=>{const b=e.target.closest('button');if(!b)return;tab=b.dataset.t;document.querySelectorAll('.tabs button').forEach(x=>x.classList.toggle('on',x===b));show()};
show();
})(window.Pawon);
