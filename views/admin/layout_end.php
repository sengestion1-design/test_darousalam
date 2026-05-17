  </div><!-- /admin-content -->
</div><!-- /admin-main -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Search filter
function filterTable(inputId, tableId) {
  const q = document.getElementById(inputId).value.toLowerCase();
  document.querySelectorAll('#'+tableId+' tbody tr').forEach(row => {
    row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
  });
}

// ── Cloche notifications ─────────────────────────────────────────────────────
(function() {
  const BASE = '<?= BASE_URL ?>';
  const pendingCmds = {}; // id → cmd
  let lastId = 0;
  let panelOpen = false;

  // ── Toggle dropdown ──────────────────────────────────────────────────────────
  window.toggleNotifPanel = function() {
    const panel = document.getElementById('notif-panel');
    panelOpen = !panelOpen;
    panel.style.display = panelOpen ? 'block' : 'none';
    if (panelOpen) panel.style.animation = 'dropIn .18s ease';
  };

  document.addEventListener('click', (e) => {
    if (!document.getElementById('notif-wrap')?.contains(e.target)) {
      const panel = document.getElementById('notif-panel');
      if (panel) panel.style.display = 'none';
      panelOpen = false;
    }
  });

  // ── Badge cloche ─────────────────────────────────────────────────────────────
  function updateBadge() {
    const n = Object.keys(pendingCmds).length;
    const badge = document.getElementById('notif-count');
    const sidebarBadge = document.getElementById('cmd-notif-badge');
    const link = document.querySelector('.sidebar-link[href*="commandes"]');

    if (badge) {
      badge.style.display = n > 0 ? 'block' : 'none';
      badge.textContent = n;
    }
    if (link) {
      if (n > 0) {
        if (!sidebarBadge) {
          const b = document.createElement('span');
          b.id = 'cmd-notif-badge'; b.className = 'badge-count';
          b.textContent = n; link.appendChild(b);
        } else { sidebarBadge.textContent = n; }
      } else if (sidebarBadge) { sidebarBadge.remove(); }
    }
  }

  // ── Rendu du panel ───────────────────────────────────────────────────────────
  function renderPanel() {
    const list   = document.getElementById('notif-list');
    const empty  = document.getElementById('notif-empty');
    const pcount = document.getElementById('notif-panel-count');
    if (!list) return;

    list.querySelectorAll('.notif-item').forEach(el => el.remove());
    const ids = Object.keys(pendingCmds);

    if (pcount) pcount.textContent = ids.length > 0 ? ids.length + ' en attente' : '';
    if (empty)  empty.style.display = ids.length === 0 ? 'block' : 'none';

    ids.forEach(id => {
      const cmd    = pendingCmds[id];
      const nom    = [cmd.client_prenom, cmd.client_nom].filter(Boolean).join(' ') || 'Client';
      const total  = parseInt(cmd.total).toLocaleString('fr-FR') + ' FCFA';
      const d      = new Date(cmd.created_at);
      const heure  = d.toLocaleTimeString('fr-FR', {hour:'2-digit', minute:'2-digit'});
      const jour   = d.toLocaleDateString('fr-FR', {day:'2-digit', month:'2-digit'});

      const item = document.createElement('a');
      item.className = 'notif-item';
      item.href = BASE + '?page=admin_commande_detail&id=' + encodeURIComponent(id);
      item.style.cssText = 'display:flex;align-items:center;gap:12px;padding:13px 18px;border-bottom:1px solid #f3f4f6;text-decoration:none;background:#fff;transition:background .12s;';
      item.addEventListener('mouseenter', () => item.style.background = '#fafafa');
      item.addEventListener('mouseleave', () => item.style.background = '#fff');

      // Avatar initiale
      const av = document.createElement('div');
      av.style.cssText = 'width:36px;height:36px;border-radius:10px;background:#fff7ed;border:1.5px solid #fed7aa;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.82rem;font-weight:800;color:#f97316;';
      av.textContent = nom.charAt(0).toUpperCase();

      // Contenu
      const content = document.createElement('div');
      content.style.cssText = 'flex:1;min-width:0;';

      const top = document.createElement('div');
      top.style.cssText = 'display:flex;justify-content:space-between;align-items:baseline;';
      const nomEl = document.createElement('span');
      nomEl.style.cssText = 'font-size:.82rem;font-weight:700;color:#111827;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:150px;display:block;';
      nomEl.textContent = nom;
      const timeEl = document.createElement('span');
      timeEl.style.cssText = 'font-size:.65rem;color:#9ca3af;white-space:nowrap;margin-left:6px;';
      timeEl.textContent = jour + ' ' + heure;
      top.appendChild(nomEl); top.appendChild(timeEl);

      const bot = document.createElement('div');
      bot.style.cssText = 'display:flex;justify-content:space-between;align-items:center;margin-top:3px;';
      const refEl = document.createElement('span');
      refEl.style.cssText = 'font-size:.67rem;color:#9ca3af;font-family:monospace;';
      refEl.textContent = cmd.reference;
      const amtEl = document.createElement('span');
      amtEl.style.cssText = 'font-size:.8rem;font-weight:800;color:#f97316;';
      amtEl.textContent = total;
      bot.appendChild(refEl); bot.appendChild(amtEl);

      content.appendChild(top); content.appendChild(bot);

      // Flèche
      const arr = document.createElement('i');
      arr.className = 'bi bi-chevron-right';
      arr.style.cssText = 'color:#e5e7eb;font-size:.7rem;flex-shrink:0;';

      item.appendChild(av); item.appendChild(content); item.appendChild(arr);
      list.appendChild(item);
    });

    updateBadge();
  }

  // ── Ajouter une commande en attente ──────────────────────────────────────────
  function addCmd(cmd) {
    const id = parseInt(cmd.id);
    if (pendingCmds[id]) return;
    pendingCmds[id] = cmd;
    renderPanel();

    // Faire "vibrer" la cloche visuellement
    const btn = document.getElementById('notif-btn');
    if (btn) { btn.style.animation = 'none'; void btn.offsetWidth; btn.style.animation = 'bellRing .5s ease'; }

    // Son discret
    try {
      const ctx = new (window.AudioContext || window.webkitAudioContext)();
      const o = ctx.createOscillator(), g = ctx.createGain();
      o.connect(g); g.connect(ctx.destination);
      o.frequency.value = 740; o.type = 'sine';
      g.gain.setValueAtTime(0, ctx.currentTime);
      g.gain.linearRampToValueAtTime(0.08, ctx.currentTime + 0.03);
      g.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.5);
      o.start(); o.stop(ctx.currentTime + 0.5);
    } catch(e) {}
  }

  function removeCmd(id) {
    if (!pendingCmds[id]) return;
    delete pendingCmds[id];
    renderPanel();
  }

  // ── Polling ──────────────────────────────────────────────────────────────────
  async function poll() {
    const watchIds = Object.keys(pendingCmds).join(',');
    const url = BASE + '?page=admin_check_commandes&since_id=' + lastId
              + (watchIds ? '&watch=' + encodeURIComponent(watchIds) : '');
    try {
      const res = await fetch(url);
      if (!res.ok) return;
      const data = await res.json();

      (data.nouvelles || []).forEach(cmd => {
        const cid = parseInt(cmd.id);
        if (cid > lastId) lastId = cid;
        if (cmd.statut === 'en_attente') addCmd(cmd);
      });

      Object.entries(data.statuts || {}).forEach(([id, statut]) => {
        if (statut !== 'en_attente') removeCmd(parseInt(id));
      });
    } catch(e) {}
  }

  // Chargement initial + polling
  setTimeout(() => { lastId = 0; poll(); }, 800);
  setInterval(poll, 20000);
})();
</script>

<style>
@keyframes dropIn { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:translateY(0); } }
@keyframes bellRing {
  0%,100% { transform: rotate(0); }
  20%      { transform: rotate(-18deg); }
  40%      { transform: rotate(16deg); }
  60%      { transform: rotate(-10deg); }
  80%      { transform: rotate(7deg); }
}
</style>
</body>
</html>
