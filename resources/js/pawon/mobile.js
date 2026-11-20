/* Pawon Ratu Roso - pelengkap tampilan mobile: tab bar bawah, chip kategori menu, bottom sheet chatbot */
(function () {
  var P = window.Pawon || {};
  var page = P.page || 'index.html';
  document.body.classList.add('pg-' + page.replace('.html', ''));
  var logged = P.me && P.me();
  var ic = {
    home: '<path d="M3 11l9-8 9 8"/><path d="M5 10v10h14V10"/>',
    menu: '<path d="M4 3v8a3 3 0 0 0 3 3v7"/><path d="M7 3v6"/><path d="M10 3v8a3 3 0 0 1-3 3"/><path d="M17 3c-2 2-3 5-3 8h3v10"/>',
    book: '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M3 10h18M8 3v4M16 3v4"/>',
    chat: '<path d="M21 12a8 8 0 0 1-11.5 7.2L4 21l1.8-5.5A8 8 0 1 1 21 12z"/>',
    user: '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/>'
  };
  var tabs = [
    ['index.html', 'Beranda', 'home'], ['menu.html', 'Menu', 'menu'], ['booking.html', 'Booking', 'book'],
    ['hubungi-kami.html', 'Kontak', 'chat'],
    logged ? ['profil.html', 'Profil', 'user'] : ['masuk.html', 'Masuk', 'user']
  ];
  var nav = document.createElement('nav');
  nav.className = 'tabbar';
  nav.setAttribute('aria-label', 'Navigasi utama');
  nav.innerHTML = tabs.map(function (t) {
    var on = page === t[0] || (t[0] === 'masuk.html' && page === 'daftar.html');
    return '<a href="' + (P.url ? P.url(t[0]) : t[0]) + '"' + (on ? ' class="on" aria-current="page"' : '') + '><svg viewBox="0 0 24 24" aria-hidden="true">' + ic[t[2]] + '</svg><span>' + t[1] + '</span></a>';
  }).join('');
  document.body.appendChild(nav);

  /* tutup drawer saat menu dipilih */
  var drawer = document.getElementById('mainnav'), burger = document.querySelector('.burger');
  if (drawer) drawer.addEventListener('click', function (e) {
    if (e.target.closest('a')) { drawer.classList.remove('open'); if (burger) burger.setAttribute('aria-expanded', 'false'); }
  });

  /* chip kategori di halaman menu */
  var root = document.getElementById('menuRoot'), tools = document.querySelector('.menutools');
  if (root && tools) {
    var bar = document.createElement('div');
    bar.className = 'catbar';
    bar.setAttribute('role', 'group');
    bar.setAttribute('aria-label', 'Lompat ke kategori');
    tools.appendChild(bar);
    var build = function () {
      var secs = root.querySelectorAll('.sec');
      bar.hidden = !secs.length;
      bar.innerHTML = '';
      secs.forEach(function (s) {
        var h = s.querySelector('h2'), b = document.createElement('button');
        b.type = 'button';
        b.textContent = h.childNodes[0].textContent.replace(/^Menu /, '');
        b.onclick = function () {
          s.scrollIntoView({ behavior: 'smooth', block: 'start' });
          bar.querySelectorAll('button').forEach(function (x) { x.classList.toggle('on', x === b); });
        };
        bar.appendChild(b);
      });
    };
    build();
    new MutationObserver(build).observe(root, { childList: true });
  }

  /* kunci scroll halaman saat chat terbuka */
  var box = document.querySelector('.bot');
  if (box) new MutationObserver(function () {
    document.body.classList.toggle('chat-open', !box.hidden && matchMedia('(max-width:760px)').matches);
  }).observe(box, { attributes: true, attributeFilter: ['hidden'] });
})();
