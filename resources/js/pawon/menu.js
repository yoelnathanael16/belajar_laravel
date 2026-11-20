(function(P){
const root=document.getElementById('menuRoot'),q=document.getElementById('q'),cnt=document.getElementById('count');
const order=['Menu Favorit','Menu Baru','Menu 12k','Menu Khusus Weekend','Menu Daging','Menu Utama','Menu Cemilan','Menu Khas Pawon','Menu Seger-seger',"Menu Ombe'an"];
const note={'Menu 12k':'(Nasi + Sayur + 2 Gorengan + Free Sambel Terasi)','Menu Cemilan':'Serba 12k'};
function render(){const k=q.value.trim().toLowerCase();let n=0;
 const ok=m=>!k||(m.name+' '+m.desc+' '+m.tags+' '+m.cat).toLowerCase().includes(k);
 root.innerHTML=order.map(o=>{let items=o==='Menu Favorit'?P.fav.map(P.byId):P.menu.filter(m=>m.cat.split('|')[0]===o);
  items=items.filter(ok);if(!items.length)return'';if(o!=='Menu Favorit')n+=items.length;
  let h;if(o==='Menu 12k'){const g={};items.forEach(m=>(g[m.cat.split('|')[1]]=g[m.cat.split('|')[1]]||[]).push(m));
   h=Object.keys(g).map(s=>`<h3 class="sub">${s}</h3><div class="grid">${g[s].map(P.card).join('')}</div>`).join('')}
  else h=`<div class="grid">${items.map(m=>o==='Menu Favorit'?P.card(m).replace('id="m-','data-f="').replace('<article','<article id="f-'+m.id+'"'):P.card(m)).join('')}</div>`;
  return`<section class="sec"><h2>${o}${note[o]?`<small>${note[o]}</small>`:''}</h2>${h}</section>`}).join('')||'<p>Menu tidak ditemukan. Coba kata kunci lain atau tanya Pawon lewat chat.</p>';
 cnt.textContent=k?n+' menu ditemukan':n+' menu';}
q.oninput=render;render();
P.goto=id=>{const el=document.getElementById('m-'+id);if(!el)return;q.value='';render();const t=document.getElementById('m-'+id);t.scrollIntoView({block:'center'});t.classList.add('flash');setTimeout(()=>t.classList.remove('flash'),2200)};
if(location.hash.startsWith('#m-'))setTimeout(()=>P.goto(location.hash.slice(3)),100);
})(window.Pawon);