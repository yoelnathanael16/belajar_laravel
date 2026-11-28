/* Chatbot "Tanya Pawon": rekomendasi menu + info menu. Berbasis aturan, berjalan penuh di browser. */
(function(P){
const norm=s=>s.toLowerCase().replace(/[^a-z0-9\s]/g,' ').replace(/\s+/g,' ').trim();
const isWk=()=>[0,6].includes(new Date().getDay());
const avail=m=>!m.weekend||isWk();
const pick=(ids,n=4)=>ids.map(P.byId).filter(m=>m&&avail(m)).slice(0,n);
const EV={
 ulang:{t:'Ulang tahun',ids:['nasi-pecel-ayam','ayam-goreng','sate-sapi','garang-asem','kopi-susu','soda-yakult']},
 kantor:{t:'acara kantor / organisasi',ids:['lodeh-tahu','ayam-bakar','pepes-tuna','bakwan','kopi-susu','teh']},
 keluarga:{t:'reuni / kumpul keluarga',ids:['garang-asem','mujair-bumbu','ayam-goreng','botok-ontong','wedang-jahe','teh-lemon-sereh']}};
function lev(a,b){const d=[...Array(b.length+1).keys()];for(let i=1;i<=a.length;i++){let p=d[0];d[0]=i;for(let j=1;j<=b.length;j++){const t=d[j];d[j]=Math.min(d[j]+1,d[j-1]+1,p+(a[i-1]===b[j-1]?0:1));p=t}}return d[b.length]}
function findItems(t){const w=t.split(' ');return P.menu.map(m=>{const n=norm(m.name);let s=0;if(t.includes(n))s=10+n.length;else{n.split(' ').forEach(x=>{if(x.length>2&&w.some(y=>y.length>2&&(y===x||(x.length>3&&lev(x,y)<=1))))s+=x.length})}return[m,s]}).filter(x=>x[1]>=4).sort((a,b)=>b[1]-a[1]).map(x=>x[0])}
function budget(t){const m=t.match(/(\d+)\s*(ribu|rb|k)\b/)||t.match(/\b(\d{4,6})\b/);if(!m)return 0;let v=+m[1];return v<1000?v*1000:v}
const hrs='Kami buka setiap hari pukul 08.00 - 17.00 WIB.';
function reply(raw){const t=norm(raw),out={text:'',items:[],links:[]};
 let fm=findItems(t);if(fm.length&&t.includes(norm(fm[0].name)))fm=[fm[0]];
 if(/^(halo|hai|hi|hello|pagi|siang|sore|malam|permisi)\b/.test(t)&&t.split(' ').length<4){out.text='Halo! Saya bisa bantu rekomendasi menu atau cek menu yang tersedia. Mau cari apa?';return out}
 if(/jam buka|buka jam|tutup|jam operasional|buka kah|kapan buka/.test(t)||/\bjam\b/.test(t)&&/buka/.test(t)){out.text=hrs;return out}
 if(/alamat|lokasi|dimana|di mana|maps|arah/.test(t)){out.text='Kanigoro, Tirtomoyo, Kec. Pakis, Kabupaten Malang, Jawa Timur 65154.';return out}
 if(/\bwa\b|whatsapp|nomor|telepon|telpon|kontak|hubungi/.test(t)){out.text='WhatsApp / telepon: +62 812 1386 5343.';out.links.push(['Chat via WhatsApp','https://wa.me/'+P.WA]);return out}
 /* tombol "Order via GoFood / GrabFood" di desain Figma; link toko belum ada, jadi arahkan cari di aplikasi */
 if(/go ?food|gojek|grab ?food|\bgrab\b|pesan antar|delivery/.test(t)){const g=/grab/.test(t);out.text=`Pesan antar lewat ${g?'GrabFood':'GoFood'}: buka aplikasi ${g?'Grab':'Gojek'}, pilih ${g?'GrabFood':'GoFood'}, lalu cari "Pawon Ratu Roso".`;return out}
 if(/booking|reservasi|pesan meja|pesan tempat|book/.test(t)){out.text='Booking meja dilakukan lewat halaman Booking dengan 5 langkah (acara, jumlah tamu, waktu, menu, konfirmasi). Ketersediaan meja dikonfirmasi admin lewat WhatsApp.';out.links.push(['Buka halaman Booking',P.url('booking.html')]);return out}
 if(/weekend|akhir pekan|sabtu|minggu/.test(t)){const w=P.menu.filter(m=>m.weekend);out.text='Menu Khusus Weekend hanya tersedia Sabtu & Minggu'+(isWk()?' (hari ini tersedia):':' (hari ini belum tersedia):');out.items=w;return out}
 for(const k of [['ulang',/ulang tahun|ultah|birthday/],['kantor',/kantor|organisasi|rapat|meeting|kerja/],['keluarga',/keluarga|reuni|arisan|kumpul/]]){if(k[1].test(t)){const e=EV[k[0]];out.text='Untuk '+e.t+' saya sarankan:';out.items=pick(e.ids,6);out.links.push(['Mulai booking',P.url('booking.html')]);return out}}
 if(/harga|berapa|price/.test(t)&&fm.length){out.text=fm.slice(0,3).map(m=>`${m.name}: ${P.fmt(m.price)}/${m.unit}${m.cold?' (dingin '+P.fmt(m.cold)+')':''}`).join('\n');out.items=fm.slice(0,3);return out}
 const b=budget(t);
 const want=[[/vegetarian|vegan|tanpa daging|sayur/,'vegetarian','menu vegetarian / tanpa daging'],[/pedas|sambal|pedes/,'pedas','menu pedas'],[/seafood|ikan|udang/,'ikan|seafood','menu ikan & seafood'],[/ayam/,'ayam','menu ayam'],[/camilan|cemilan|snack|gorengan|ngemil/,'camilan','camilan'],[/kopi/,'kopi','menu kopi'],[/segar|dingin|\bes\b|soda/,'segar|dingin','minuman segar'],[/hangat|panas|jahe|wedang/,'hangat|jahe','minuman hangat'],[/minum/,'minuman','minuman'],[/manis/,'manis','menu manis'],[/sate/,'sate','sate'],[/anak|kids/,'anak','menu ramah anak'],[/kuah|berkuah|soto/,'kuah','menu berkuah']];
 let hit=want.filter(w=>w[0].test(t));if(hit.length>1)hit=hit.filter(h=>h[2]!=='minuman');
 if(hit.length||b||/rekomen|saran|enak|bingung|apa ya|favorit|populer|terlaris|andalan/.test(t)){
  let L=P.menu.filter(avail);
  hit.forEach(h=>{const re=new RegExp(h[1]);L=L.filter(m=>re.test(m.tags+' '+m.name.toLowerCase()))});
  if(b)L=L.filter(m=>m.price<=b);
  if(!hit.length&&!b){out.text='Ini menu favorit pelanggan kami:';out.items=P.fav.map(P.byId);return out}
  if(!L.length){out.text='Belum ada menu yang cocok dengan kriteria itu. Coba kriteria lain, misalnya "menu pedas" atau "minuman di bawah 15 ribu".';return out}
  out.text='Rekomendasi '+(hit.map(h=>h[2]).join(' + ')||'menu')+(b?' maksimal '+P.fmt(b):'')+':';out.items=L.slice(0,5);if(L.length>5)out.text+=` (${L.length} menu cocok, ini 5 teratas)`;return out}
 if(fm.length){const m=fm[0];out.text=m.weekend&&!isWk()?`${m.name} hanya tersedia Sabtu & Minggu.`:`Ada! ${m.name} (${m.desc}) ${P.fmt(m.price)}/${m.unit}${m.cold?', dingin '+P.fmt(m.cold):''}.`;out.items=fm.slice(0,3);return out}
 if(/menu apa|ada apa|daftar menu|semua menu|menu tersedia|tersedia/.test(t)){const cats=[...new Set(P.menu.map(m=>m.cat.split('|')[0]))];out.text='Kategori menu kami:\n'+cats.map(c=>'- '+c).join('\n')+'\n\nTanya kategori mana pun, misalnya "menu daging" atau "ada minuman apa?".';return out}
 if(/menu (\w+)/.test(t)){const c=P.menu.filter(m=>norm(m.cat).includes(t.match(/menu ([\w ]+)/)[1].split(' ')[0]));if(c.length){out.text='Berikut isi kategori itu:';out.items=c.slice(0,6);return out}}
 if(/makasih|terima kasih|thanks/.test(t)){out.text='Sama-sama! Selamat menikmati masakan Pawon.';return out}
 out.text='Maaf, saya belum paham. Saya bisa bantu rekomendasi menu (mis. "menu pedas", "minuman di bawah 15 ribu", "untuk ulang tahun"), cek ketersediaan & harga, jam buka, dan alamat.';return out}
/* UI */
const wrap=document.createElement('div');
wrap.innerHTML=`<button class="fab" aria-label="Buka chat Tanya Pawon" aria-expanded="false">\ud83d\udcac</button>
<section class="bot" role="dialog" aria-label="Tanya Pawon" hidden><header><h2>Tanya Pawon</h2><button aria-label="Tutup chat">\u2715</button></header><div class="msgs" aria-live="polite"></div>
<div class="chips"></div><form><input aria-label="Tulis pesan" placeholder="Tanya menu atau rekomendasi..." autocomplete="off"><button class="btn" type="submit">Kirim</button></form></section>`;
document.body.append(...wrap.children);
const fab=document.querySelector('.fab'),box=document.querySelector('.bot'),msgs=box.querySelector('.msgs'),chips=box.querySelector('.chips'),form=box.querySelector('form'),inp=form.querySelector('input');
/* tombol cepat sesuai desain Figma "Booking - Chatbot" */
const CH=['Jam buka','Alamat','Rekomendasi Menu','Order via GoFood','Order via GrabFood'];
chips.innerHTML=CH.map(c=>`<button type="button">${c}</button>`).join('');
const add=(h,u)=>{const d=document.createElement('div');d.className='m'+(u?' u':'');if(typeof h==='string')d.textContent=h;else d.append(h);msgs.append(d);msgs.scrollTop=msgs.scrollHeight;return d};
function bot(r){const d=document.createElement('div');d.append(document.createTextNode(r.text));
 r.items.forEach(m=>{const b=document.createElement('button');b.type='button';b.className='mi';b.textContent=`${m.name} \u2013 ${P.fmt(m.price)}/${m.unit}`;b.onclick=()=>{if(P.goto)P.goto(m.id);else location.href=P.url('menu.html')+'#m-'+m.id};d.append(b)});
 r.links.forEach(l=>{const a=document.createElement('a');a.href=l[1];a.textContent=l[0];const p=document.createElement('div');p.append(a);d.append(p)});add(d)}
function ask(x){add(x,1);setTimeout(()=>bot(reply(x)),250)}
const open=o=>{box.hidden=!o;fab.setAttribute('aria-expanded',o);if(o)inp.focus()};
fab.onclick=()=>open(box.hidden);box.querySelector('header button').onclick=()=>{open(false);fab.focus()};
chips.onclick=e=>{if(e.target.tagName==='BUTTON')ask(e.target.textContent)};
form.onsubmit=e=>{e.preventDefault();const v=inp.value.trim();if(!v)return;inp.value='';ask(v)};
document.addEventListener('keydown',e=>{if(e.key==='Escape'&&!box.hidden)open(false)});
add('Halo! Saya bisa bantu kasih rekomendasi menu atau info dasar seperti jam buka dan alamat. Untuk cek ketersediaan meja dan konfirmasi booking, tetap lewat admin di WhatsApp ya.');
P.chatReply=reply;
})(window.Pawon);