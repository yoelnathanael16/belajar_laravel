(function(P){
document.getElementById('favGrid').innerHTML=P.fav.map(i=>P.card(P.byId(i))).join('');
document.getElementById('newGrid').innerHTML=P.menu.filter(m=>m.cat==='Menu Baru').map(P.card).join('');
})(window.Pawon);