/* Mendaftarkan semua gambar Pawon ke Vite (agar masuk manifest) dan menyediakan Pawon.img(nama) */
window.Pawon = window.Pawon || {};

const files = import.meta.glob('../../images/pawon/*.{jpg,jpeg,png,webp,svg}', {
    eager: true,
    query: '?url',
    import: 'default',
});

const byName = {};
for (const path in files) byName[path.split('/').pop()] = files[path];

window.Pawon.img = (name) => byName[name] || '';
