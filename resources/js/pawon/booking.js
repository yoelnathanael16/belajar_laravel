(function(P){
const u=P.me(),gate=document.getElementById('gate'),wiz=document.getElementById('wiz');
if(!u){gate.hidden=false;gate.querySelectorAll('a[href="'+P.url('masuk.html')+'"],a[href="'+P.url('daftar.html')+'"]').forEach(a=>a.href+='?next='+encodeURIComponent(P.url('booking.html')));return}
wiz.hidden=false;
const EV=[['\ud83c\udf82','Ulang Tahun'],['\ud83d\udcbc','Acara Kantor / Organisasi'],['\ud83d\udc6a','Reuni / Kumpul Keluarga'],['\u2728','Lainnya']];
const G=['35-50 orang','50-70 orang','70-100 orang','Lebih dari 100 orang'];
const ST=['Acara','Jumlah Tamu','Waktu','Menu','Konfirmasi'];
const S={step:0,ev:'',g:'',date:'',time:'',cat:'',qty:{},done:false};
const pane=document.getElementById('pane'),next=document.getElementById('next'),back=document.getElementById('back'),sum=document.getElementById('sum'),steps=document.getElementById('steps');
const items=()=>Object.keys(S.qty).filter(i=>S.qty[i]>0).map(i=>({m:P.byId(i),q:S.qty[i]}));
const total=()=>items().reduce((a,x)=>a+x.m.price*x.q,0);
const today=new Date(),iso=d=>d.toISOString().slice(0,10),fd=s=>{const[y,m,d]=s.split('-');return`${d}-${m}-${y}`};
const canNext=()=>[S.ev,S.g,S.date&&S.time,items().length,1][S.step];
function draw(){
 steps.innerHTML=ST.map((s,i)=>`<li class="${i<S.step||S.done?'done':i===S.step?'on':''}"><b>${i<S.step||S.done?'\u2713':i+1}</b>${s}</li>`).join('');
 back.hidden=S.done;next.hidden=S.done;sum.textContent='';
 back.disabled=S.step===0;next.textContent=S.step===4?'Kirim ke WhatsApp Admin':'Lanjut';next.className='btn'+(S.step===4?' wa':'');
 next.disabled=!canNext();
 if(S.step===0)pane.innerHTML=`<h2>Acara apa yang akan Anda selenggarakan?</h2><p class="small">Pilih salah satu jenis acara</p><div class="opts ev">${EV.map(e=>`<button class="opt${S.ev===e[1]?' on':''}" data-v="${e[1]}">${e[0]} ${e[1]}</button>`).join('')}</div>`;
 if(S.step===1)pane.innerHTML=`<h2>Berapa jumlah perkiraan tamu?</h2><p class="small">Pilih rentang jumlah tamu yang akan hadir</p><div class="opts">${G.map(g=>`<button class="opt${S.g===g?' on':''}" data-v="${g}">${g}</button>`).join('')}</div>`;
 if(S.step===2)pane.innerHTML=`<h2>Pilih hari dan jam kedatangan</h2><p class="small">Pilihan jam mengikuti jam buka Pawon Ratu Roso.</p><label>Tanggal<input type="date" id="d" min="${iso(today)}" value="${S.date}"></label><label>Jam<select id="t"><option value="">Pilih jam</option>${[8,9,10,11,12,13,14,15,16].map(h=>{const v=String(h).padStart(2,'0')+'.00 WIB';return`<option${S.time===v?' selected':''}>${v}</option>`}).join('')}</select></label><p class="small">Buka setiap hari, 08.00 - 17.00 WIB. Ketersediaan meja akan dikonfirmasi oleh admin lewat WhatsApp.</p>`;
 if(S.step===3){if(!S.cat)pane.innerHTML=`<h2>Pilih menu dan jumlahnya</h2><p class="small">Pilih kategori menu yang ingin Anda tambahkan</p><div class="cat2"><button class="opt" data-c="f">\ud83c\udf57<br>Makanan<br><small>${P.menu.filter(m=>!m.drink).length} pilihan menu</small></button><button class="opt" data-c="d">\ud83e\udd65<br>Minuman<br><small>${P.menu.filter(m=>m.drink).length} pilihan menu</small></button></div>`;
  else{const L=P.menu.filter(m=>(S.cat==='d')===m.drink);pane.innerHTML=`<h2>Pilih menu dan jumlahnya</h2><p class="small"><a href="#" id="bc">\u2190 Pilih kategori</a></p><div class="rows">${L.map(m=>`<div class="row"><div><b>${m.name.replace('12k','')}</b><small>${P.fmt(m.price)}/${m.unit}${m.weekend?' (Sabtu & Minggu)':''}</small></div><div class="qty"><button data-i="${m.id}" data-d="-1" aria-label="Kurangi ${m.name}">\u2212</button><span>${S.qty[m.id]||0}</span><button data-i="${m.id}" data-d="1" aria-label="Tambah ${m.name}">+</button></div></div>`).join('')}</div>`}
  const n=items().reduce((a,x)=>a+x.q,0);sum.innerHTML=n?`<b>${n} porsi</b> dipilih \u00b7 Total <b>${P.fmt(total())}</b>`:'Belum ada menu dipilih'}
 if(S.step===4)pane.innerHTML=`<h2>Konfirmasi dan kirim ke admin</h2><p class="small">Periksa kembali data Anda, lalu kirim ke WhatsApp admin untuk diproses</p><div class="sumt"><div><span>Jenis Acara</span><b>${S.ev}</b></div><div><span>Jumlah Tamu</span><b>${S.g}</b></div><div><span>Tanggal</span><b>${fd(S.date)}</b></div><div><span>Jam</span><b>${S.time}</b></div><div><span>Menu</span><b>${items().map(x=>`${x.m.name} x${x.q}`).join('<br>')}</b></div><div><span>Total</span><b>${P.fmt(total())}</b></div></div><p class="small">Admin akan membalas lewat WhatsApp untuk mengonfirmasi atau menolak pesanan meja Anda.</p>`;
 if(S.done)pane.innerHTML=`<h2>Pesanan terkirim</h2><p>Kode pesanan Anda <b>${S.code}</b>. Jika WhatsApp tidak terbuka otomatis, kirim ulang pesan ke admin.</p><a class="btn wa" href="${S.wa}" target="_blank" rel="noopener">Kirim ke WhatsApp Admin</a> <a class="btn ghost" href="${P.url('profil.html')}">Lihat Pesanan Saya</a> <a class="btn ghost" href="${P.url('index.html')}">Kembali ke Beranda</a>`;
}
pane.onclick=e=>{const t=e.target.closest('button,a');if(!t)return;
 if(t.id==='bc'){e.preventDefault();S.cat='';return draw()}
 if(t.dataset.v){S[S.step===0?'ev':'g']=t.dataset.v}
 else if(t.dataset.c){S.cat=t.dataset.c}
 else if(t.dataset.i){const i=t.dataset.i;S.qty[i]=Math.max(0,(S.qty[i]||0)+ +t.dataset.d)}
 else return;draw()};
pane.onchange=e=>{if(e.target.id==='d')S.date=e.target.value;if(e.target.id==='t')S.time=e.target.value;next.disabled=!canNext()};
back.onclick=()=>{if(S.step===3&&S.cat){S.cat='';return draw()}S.step--;draw()};
next.onclick=()=>{
 if(S.step===3&&S.cat&&false)return;
 if(S.step<4){S.step++;if(S.step===3)S.cat='';return draw()}
 const os=P.orders(),code='PRS-'+String(os.length+14).padStart(4,'0');
 const lines=items().map(x=>`- ${x.m.name} x${x.q}`).join('\n');
 const msg=`Halo admin Pawon Ratu Roso, saya ${u.name} ingin booking.\nKode: ${code}\nAcara: ${S.ev}\nTamu: ${S.g}\nTanggal: ${fd(S.date)}\nJam: ${S.time}\nMenu:\n${lines}\nTotal: ${P.fmt(total())}`;
 os.push({code,email:u.email,name:u.name,ev:S.ev,g:S.g,date:S.date,time:S.time,items:items().map(x=>({n:x.m.name,q:x.q,p:x.m.price})),total:total(),status:'Menunggu konfirmasi',at:new Date().toISOString()});P.set('pawon_orders',os);
 S.wa=`https://wa.me/${P.WA}?text=${encodeURIComponent(msg)}`;S.code=code;S.done=true;draw();window.open(S.wa,'_blank','noopener');};
draw();
})(window.Pawon);