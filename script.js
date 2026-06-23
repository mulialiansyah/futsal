// Dummy Data Turnamen
const turnamenData = [
    {
        id: 1,
        title: "Liga Futsal Nusantara 2026",
        date: "15 Agustus 2026",
        location: "Gelora Bung Karno",
        status: "Pendaftaran Buka",
        teamsRegistered: 12,
        maxTeams: 32,
        prize: "Rp 50.000.000"
    },
    {
        id: 2,
        title: "Piala Merdeka Cup",
        date: "20 Agustus 2026",
        location: "GOR Sumantri",
        status: "Pendaftaran Buka",
        teamsRegistered: 8,
        maxTeams: 16,
        prize: "Rp 15.000.000"
    },
    {
        id: 3,
        title: "Super League Futsal",
        date: "01 September 2026",
        location: "Arena Futsal Jakarta",
        status: "Segera Hadir",
        teamsRegistered: 0,
        maxTeams: 24,
        prize: "Rp 25.000.000"
    }
];

// Dummy Data Klub
const klubData = [
    {
        id: 1,
        name: "Garuda FC",
        city: "Jakarta",
        founded: "2020",
        players: 15,
        rating: "⭐️ 4.8",
        icon: "fa-shield-cat"
    },
    {
        id: 2,
        name: "Spartan Futsal",
        city: "Bandung",
        founded: "2018",
        players: 12,
        rating: "⭐️ 4.5",
        icon: "fa-dragon"
    },
    {
        id: 3,
        name: "Bintang Timur",
        city: "Surabaya",
        founded: "2015",
        players: 20,
        rating: "⭐️ 4.9",
        icon: "fa-star"
    },
    {
        id: 4,
        name: "Black Steel",
        city: "Papua",
        founded: "2017",
        players: 18,
        rating: "⭐️ 5.0",
        icon: "fa-bolt"
    }
];

// Render Turnamen Cards
function renderTurnamen() {
    const grid = document.getElementById('turnamen-grid');
    grid.innerHTML = '';

    turnamenData.forEach(item => {
        const card = document.createElement('div');
        card.className = 'card';
        card.innerHTML = `
            <div class="card-header">
                <div>
                    <h3 class="card-title">${item.title}</h3>
                    <div class="card-subtitle"><i class="fa-regular fa-calendar"></i> ${item.date}</div>
                </div>
                <span class="badge">${item.status}</span>
            </div>
            <div class="card-body">
                <div class="stat-row">
                    <span class="stat-label">Lokasi</span>
                    <span class="stat-value"><i class="fa-solid fa-location-dot" style="color: var(--text-secondary); margin-right: 4px;"></i> ${item.location}</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Tim Terdaftar</span>
                    <span class="stat-value">${item.teamsRegistered} / ${item.maxTeams}</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Prize Pool</span>
                    <span class="stat-value" style="color: var(--neon-green);">${item.prize}</span>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn-secondary">Detail</button>
                <button class="btn-action">Daftar</button>
            </div>
        `;
        grid.appendChild(card);
    });
}

// Render Klub Cards
function renderKlub() {
    const grid = document.getElementById('klub-grid');
    grid.innerHTML = '';

    klubData.forEach(item => {
        const card = document.createElement('div');
        card.className = 'card club-card';
        card.innerHTML = `
            <div class="card-header">
                <div class="club-logo">
                    <i class="fa-solid ${item.icon}"></i>
                </div>
                <div class="club-info">
                    <h3 class="card-title">${item.name}</h3>
                    <div class="card-subtitle"><i class="fa-solid fa-location-dot"></i> ${item.city}</div>
                </div>
                <span class="badge" style="background: transparent; color: #fbbf24; font-size: 1rem;">${item.rating}</span>
            </div>
            <div class="card-body">
                <div class="stat-row">
                    <span class="stat-label">Berdiri Sejak</span>
                    <span class="stat-value">${item.founded}</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Jumlah Pemain</span>
                    <span class="stat-value">${item.players} Anggota</span>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn-secondary">Profil</button>
                <button class="btn-action">Ajak Sparring</button>
            </div>
        `;
        grid.appendChild(card);
    });
}

// Tab Navigation Logic
function setupTabs() {
    const navBtns = document.querySelectorAll('.nav-btn');
    const sections = document.querySelectorAll('.content-section');

    navBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove active class from all buttons and sections
            navBtns.forEach(b => b.classList.remove('active'));
            sections.forEach(s => s.classList.remove('active'));

            // Add active class to clicked button
            btn.classList.add('active');

            // Show corresponding section
            const targetId = btn.getAttribute('data-target');
            document.getElementById(targetId).classList.add('active');
        });
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    renderTurnamen();
    renderKlub();
    setupTabs();
});
