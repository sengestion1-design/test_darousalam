<?php
$pageTitle = 'Commande confirmée — ' . APP_NAME;
require_once __DIR__ . '/../layouts/header.php';

$adresseData = json_decode($commande['adresse_livraison'] ?? '{}', true);
$dateLivraison = date('d/m/Y', strtotime('+2 days'));
$reference = $commande['reference'] ?? ('CMD-' . $commande['id']);

$currentStatut = $commande['statut'] ?? 'en_attente';
$statutIndex = [
    'en_attente'     => 0,
    'confirmee'      => 1,
    'en_preparation' => 2,
    'en_cours'       => 2,
    'expediee'       => 3,
    'livree'         => 4,
    'annulee'        => -1,
];
$currentIdx = $statutIndex[$currentStatut] ?? 0;
$isAnnulee  = ($currentStatut === 'annulee');
?>

<style>
:root{--vert:#1a5c2a;--vert-l:#2d8a42;--orange:#f97316;--or:#d4a017;--sable:#fdf8ee;}
*{box-sizing:border-box;}

/* HERO */
.conf-hero{
  background:linear-gradient(135deg,#050d07 0%,#0f2d16 50%,#1a5c2a 100%);
  border-radius:22px;padding:52px 32px 44px;margin-bottom:32px;
  text-align:center;position:relative;overflow:hidden;
}
.conf-hero::before{content:'';position:absolute;width:500px;height:500px;border-radius:50%;background:rgba(255,255,255,.04);top:-200px;right:-180px;pointer-events:none;}
.conf-hero::after{content:'';position:absolute;width:300px;height:300px;border-radius:50%;background:rgba(249,115,22,.07);bottom:-120px;left:-100px;pointer-events:none;}

.conf-ring{
  width:96px;height:96px;border-radius:50%;
  background:rgba(255,255,255,.12);
  display:flex;align-items:center;justify-content:center;
  margin:0 auto 20px;position:relative;z-index:2;
  animation:cfPulse 2s infinite;
}
.conf-ring::before{
  content:'';position:absolute;
  width:116px;height:116px;border-radius:50%;
  border:2px solid rgba(255,255,255,.2);
  animation:cfRing 2s infinite;
}
@keyframes cfPulse{0%,100%{transform:scale(1);}50%{transform:scale(1.04);}}
@keyframes cfRing{0%{transform:scale(1);opacity:1;}100%{transform:scale(1.55);opacity:0;}}

.conf-hero-icon{font-size:2.6rem;position:relative;z-index:3;color:#4ade80;}
.conf-title{
  font-family:'Playfair Display',Georgia,serif;
  font-size:2.2rem;font-weight:900;color:#fff;margin:0 0 8px;
  position:relative;z-index:2;
}
.conf-sub{font-size:.9rem;color:rgba(255,255,255,.6);position:relative;z-index:2;margin-bottom:20px;}
.conf-ref{
  display:inline-flex;align-items:center;gap:8px;
  background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.25);
  border-radius:40px;padding:10px 22px;
  font-size:1rem;font-weight:800;color:#fff;
  position:relative;z-index:2;letter-spacing:.06em;
}
.conf-ref i{color:var(--or);}

/* INFO STRIP */
.info-strip{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:28px;}
@media(max-width:640px){.info-strip{grid-template-columns:1fr 1fr;}}
.is-card{
  background:#fff;border-radius:18px;padding:20px 16px;text-align:center;
  border:1.5px solid #f0f0f0;box-shadow:0 2px 12px rgba(0,0,0,.05);
}
.is-icon{
  width:50px;height:50px;border-radius:14px;margin:0 auto 10px;
  display:flex;align-items:center;justify-content:center;font-size:1.3rem;
}
.is-icon-green{background:linear-gradient(135deg,#f0fdf4,#dcfce7);color:#16a34a;}
.is-icon-orange{background:linear-gradient(135deg,#fff7ed,#fed7aa);color:#ea580c;}
.is-icon-blue{background:linear-gradient(135deg,#eff6ff,#dbeafe);color:#2563eb;}
.is-label{font-size:.68rem;color:#9ca3af;text-transform:uppercase;letter-spacing:.07em;margin-bottom:4px;}
.is-val{font-size:.9rem;font-weight:800;color:#1c1917;}

/* CARDS */
.ds-card{
  background:#fff;border-radius:20px;
  border:1.5px solid #f0f0f0;
  box-shadow:0 2px 16px rgba(0,0,0,.05);
  margin-bottom:20px;overflow:hidden;
}
.ds-card-hd{
  padding:18px 22px;border-bottom:1.5px solid #f5f5f5;
  display:flex;align-items:center;gap:10px;
}
.ds-card-hd-icon{
  width:38px;height:38px;border-radius:11px;
  display:flex;align-items:center;justify-content:center;font-size:1rem;
}
.ds-card-hd-icon-green{background:#f0fdf4;color:#16a34a;}
.ds-card-hd-icon-orange{background:#fff7ed;color:#ea580c;}
.ds-card-title{font-size:.95rem;font-weight:800;color:#1c1917;margin:0;}
.ds-card-bd{padding:22px;}

/* TIMELINE */
.timeline{position:relative;padding-left:32px;}
.timeline::before{content:'';position:absolute;left:11px;top:4px;bottom:4px;width:2px;background:#e5e7eb;}
.tl-step{position:relative;margin-bottom:22px;}
.tl-step:last-child{margin-bottom:0;}
.tl-dot{
  position:absolute;left:-32px;
  width:24px;height:24px;border-radius:50%;
  display:flex;align-items:center;justify-content:center;
  font-size:.7rem;font-weight:700;flex-shrink:0;
}
.tl-dot.done{background:var(--vert);color:#fff;}
.tl-dot.active{background:var(--orange);color:#fff;animation:cfPulse 1.5s infinite;}
.tl-dot.pending{background:#e5e7eb;color:#9ca3af;}
.tl-name{font-size:.88rem;font-weight:700;color:#1c1917;margin-bottom:3px;}
.tl-desc{font-size:.76rem;color:#6b7280;}
.tl-time{font-size:.7rem;color:var(--orange);font-weight:600;margin-top:3px;display:flex;align-items:center;gap:4px;}

/* RECAP ITEMS */
.r-item{
  display:flex;align-items:center;gap:14px;
  padding:12px 0;border-bottom:1px dashed #f3f4f6;
}
.r-item:last-child{border:none;}
.r-item-img{
  width:52px;height:52px;border-radius:12px;
  object-fit:cover;border:1.5px solid #f0fdf4;flex-shrink:0;
}
.r-item-img-ph{
  width:52px;height:52px;border-radius:12px;flex-shrink:0;
  background:linear-gradient(135deg,#f0fdf4,#dcfce7);
  display:flex;align-items:center;justify-content:center;
  color:#16a34a;font-size:1.3rem;
}
.r-item-name{font-size:.84rem;font-weight:700;color:#1c1917;}
.r-item-qty{font-size:.72rem;color:#9ca3af;margin-top:2px;}
.r-item-price{font-size:.9rem;font-weight:800;color:var(--vert);flex-shrink:0;margin-left:auto;}

.r-sep{height:1px;background:linear-gradient(90deg,transparent,#e5e7eb 30%,#e5e7eb 70%,transparent);margin:14px 0;}
.r-row{display:flex;justify-content:space-between;font-size:.82rem;color:#6b7280;margin-bottom:8px;}
.r-total{
  display:flex;justify-content:space-between;align-items:center;
  padding:14px 16px;margin-top:12px;
  background:linear-gradient(135deg,#f0fdf4,#dcfce7);
  border-radius:12px;border-top:2px solid #bbf7d0;
}
.r-total-label{font-size:.95rem;font-weight:800;color:#1c1917;}
.r-total-val{font-family:'Playfair Display',serif;font-size:1.4rem;font-weight:900;color:var(--vert);}

/* BUTTONS */
.btn-wa-conf{
  display:flex;align-items:center;justify-content:center;gap:8px;
  width:100%;padding:15px;background:#25d366;color:#fff;
  border:none;border-radius:14px;font-size:.92rem;font-weight:700;
  text-decoration:none;transition:all .2s;margin-bottom:10px;
  font-family:'DM Sans',sans-serif;
}
.btn-wa-conf:hover{background:#1da851;color:#fff;transform:translateY(-2px);}
.btn-cmds{
  display:flex;align-items:center;justify-content:center;gap:8px;
  width:100%;padding:13px;
  background:#f0fdf4;color:var(--vert);
  border:2px solid #bbf7d0;border-radius:14px;
  font-size:.88rem;font-weight:700;text-decoration:none;
  transition:all .2s;font-family:'DM Sans',sans-serif;
}
.btn-cmds:hover{background:var(--vert);color:#fff;border-color:var(--vert);}
.btn-cont{
  display:flex;align-items:center;justify-content:center;gap:6px;
  margin-top:10px;color:#9ca3af;font-size:.82rem;
  text-decoration:none;transition:color .2s;
}
.btn-cont:hover{color:var(--vert);}

/* INFO LIST */
.info-list{list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:10px;}
.info-list li{display:flex;gap:10px;font-size:.83rem;color:#6b7280;align-items:flex-start;}
.info-list li i{color:var(--vert);flex-shrink:0;margin-top:2px;font-size:.9rem;}
</style>

<div class="container py-4">

<?php
$heroConfig = [
    'en_attente'     => ['icon'=>'bi-hourglass-split', 'title'=>'Commande reçue !',       'sub'=>'Votre commande est en attente de traitement.',           'color'=>'#f59e0b'],
    'confirmee'      => ['icon'=>'bi-check-lg',        'title'=>'Commande confirmée !',    'sub'=>'Notre équipe a validé votre commande.',                  'color'=>'#16a34a'],
    'en_preparation' => ['icon'=>'bi-box-seam-fill',   'title'=>'En préparation',          'sub'=>'Nos équipes sélectionnent vos fruits avec soin.',         'color'=>'#7c3aed'],
    'en_cours'       => ['icon'=>'bi-box-seam-fill',   'title'=>'En préparation',          'sub'=>'Nos équipes sélectionnent vos fruits avec soin.',         'color'=>'#7c3aed'],
    'expediee'       => ['icon'=>'bi-truck',            'title'=>'Commande expédiée !',     'sub'=>'Votre commande est en route vers vous.',                  'color'=>'#0891b2'],
    'livree'         => ['icon'=>'bi-house-check-fill', 'title'=>'Commande livrée !',      'sub'=>'Votre commande a bien été livrée. Merci pour votre confiance !', 'color'=>'#16a34a'],
    'annulee'        => ['icon'=>'bi-x-circle-fill',   'title'=>'Commande annulée',        'sub'=>'Cette commande a été annulée.',                           'color'=>'#dc2626'],
];
$hc = $heroConfig[$currentStatut] ?? $heroConfig['en_attente'];
?>
<!-- HERO -->
<div class="conf-hero" style="<?= $currentStatut === 'annulee' ? 'background:linear-gradient(135deg,#1c0505 0%,#450a0a 50%,#7f1d1d 100%);' : '' ?>">
  <div class="conf-ring" style="border-color:<?= $hc['color'] ?>40;">
    <i class="bi <?= $hc['icon'] ?> conf-hero-icon" style="color:<?= $hc['color'] ?>;"></i>
  </div>
  <div class="conf-title"><?= $hc['title'] ?></div>
  <div class="conf-sub"><?= $hc['sub'] ?></div>
  <div style="display:flex;align-items:center;gap:12px;justify-content:center;flex-wrap:wrap;margin-top:16px;">
    <div class="conf-ref">
      <i class="bi bi-receipt-cutoff"></i>
      N° <?= htmlspecialchars($reference) ?>
    </div>
    <div style="background:<?= $hc['color'] ?>22;border:1px solid <?= $hc['color'] ?>55;border-radius:20px;padding:4px 14px;font-size:.75rem;font-weight:700;color:<?= $hc['color'] ?>;letter-spacing:.04em;text-transform:uppercase;">
      <?= $hc['title'] ?>
    </div>
  </div>
</div>

<!-- INFO STRIP -->
<div class="info-strip">
  <div class="is-card">
    <div class="is-icon is-icon-green"><i class="bi bi-calendar2-check-fill"></i></div>
    <div class="is-label">Livraison estimée</div>
    <div class="is-val"><?= $dateLivraison ?></div>
  </div>
  <div class="is-card">
    <div class="is-icon is-icon-orange"><i class="bi bi-currency-exchange"></i></div>
    <div class="is-label">Total payé</div>
    <div class="is-val" style="color:var(--orange);"><?= formatPrice((float)$commande['total']) ?></div>
  </div>
  <div class="is-card">
    <div class="is-icon is-icon-blue"><i class="bi bi-headset"></i></div>
    <div class="is-label">Support</div>
    <div class="is-val">+221 77 471 53 53</div>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-7">

    <!-- Timeline -->
    <div class="ds-card">
      <div class="ds-card-hd">
        <div class="ds-card-hd-icon ds-card-hd-icon-green"><i class="bi bi-geo-alt-fill"></i></div>
        <div class="ds-card-title">Suivi de votre commande</div>
      </div>
      <div class="ds-card-bd">
        <?php
        $steps = [
            ['label' => 'Commande reçue',      'desc' => 'Votre commande a été enregistrée.',           'icon' => 'bi-bag-check-fill'],
            ['label' => 'Confirmée',            'desc' => 'Notre équipe a validé votre commande.',       'icon' => 'bi-check-circle-fill'],
            ['label' => 'En préparation',       'desc' => 'Nos équipes sélectionnent vos fruits.',       'icon' => 'bi-box-seam-fill'],
            ['label' => 'Expédiée',             'desc' => 'Votre commande est en route.',                'icon' => 'bi-truck'],
            ['label' => 'Livrée',               'desc' => 'Commande livrée. Merci pour votre confiance !', 'icon' => 'bi-house-check-fill'],
        ];
        ?>
        <?php if ($isAnnulee): ?>
        <div style="text-align:center;padding:24px 16px;">
          <div style="width:56px;height:56px;border-radius:50%;background:#fef2f2;border:2px solid #fecaca;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:1.4rem;color:#dc2626;">✕</div>
          <div style="font-weight:700;color:#dc2626;font-size:.95rem;margin-bottom:6px;">Commande annulée</div>
          <div style="font-size:.82rem;color:#6b7280;">Cette commande a été annulée. Contactez-nous pour plus d'informations.</div>
        </div>
        <?php else: ?>
        <div class="timeline">
          <?php foreach ($steps as $i => $step):
            // livree = tout done, sinon: done si dépassé, active si égal, pending si avant
            $isLastAndDone = ($currentStatut === 'livree');
            $done   = $isLastAndDone ? true : ($i < $currentIdx);
            $active = $isLastAndDone ? false : ($i === $currentIdx);
            $dotClass   = $done ? 'done' : ($active ? 'active' : 'pending');
            $dotContent = $done ? '<i class="bi bi-check2"></i>' : ($active ? '<i class="bi '.$step['icon'].'"></i>' : ($i+1));
          ?>
          <div class="tl-step">
            <div class="tl-dot <?= $dotClass ?>"><?= $dotContent ?></div>
            <div class="tl-name" style="<?= $done ? 'color:var(--vert);' : ($active ? 'color:var(--orange);' : '') ?>"><?= $step['label'] ?></div>
            <div class="tl-desc"><?= $step['desc'] ?></div>
            <?php if ($i === 0): ?>
              <div class="tl-time"><i class="bi bi-clock-fill"></i> <?= date('d/m/Y H:i', strtotime($commande['created_at'])) ?></div>
            <?php elseif ($active): ?>
              <div class="tl-time"><i class="bi bi-hourglass-split"></i> En cours…</div>
            <?php elseif ($done && $i === 4 && !empty($commande['updated_at'])): ?>
              <div class="tl-time"><i class="bi bi-check-circle-fill"></i> <?= date('d/m/Y H:i', strtotime($commande['updated_at'])) ?></div>
            <?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Infos utiles -->
    <div class="ds-card">
      <div class="ds-card-hd">
        <div class="ds-card-hd-icon ds-card-hd-icon-orange"><i class="bi bi-info-circle-fill"></i></div>
        <div class="ds-card-title">Informations importantes</div>
      </div>
      <div class="ds-card-bd">
        <ul class="info-list">
          <li><i class="bi bi-check-circle-fill"></i> Un SMS de confirmation vous sera envoyé sous peu.</li>
          <li><i class="bi bi-check-circle-fill"></i> Vous recevrez un SMS quand votre commande sera expédiée.</li>
          <li><i class="bi bi-check-circle-fill"></i> Pour toute modification, contactez-nous sous 2h via WhatsApp.</li>
          <li><i class="bi bi-check-circle-fill"></i> Conservation recommandée : réfrigérateur 3–4°C pour les mangues.</li>
        </ul>
      </div>
    </div>

  </div>

  <div class="col-lg-5">

    <!-- Récapitulatif avec vraies photos -->
    <div class="ds-card">
      <div class="ds-card-hd">
        <div class="ds-card-hd-icon ds-card-hd-icon-green"><i class="bi bi-bag-check-fill"></i></div>
        <div class="ds-card-title">Récapitulatif</div>
      </div>
      <div class="ds-card-bd">

        <?php foreach ($lignes as $ligne):
          $imgUrl = !empty($ligne['image']) ? '/darousalam/' . $ligne['image'] : null;
        ?>
        <div class="r-item">
          <?php if ($imgUrl): ?>
          <img src="<?= htmlspecialchars($imgUrl) ?>" class="r-item-img" alt=""
               onerror="this.outerHTML='<div class=\'r-item-img-ph\'><i class=\'bi bi-basket2-fill\'></i></div>'">
          <?php else: ?>
          <div class="r-item-img-ph"><i class="bi bi-basket2-fill"></i></div>
          <?php endif; ?>
          <div style="flex:1;min-width:0;">
            <div class="r-item-name"><?= htmlspecialchars($ligne['nom_produit']) ?></div>
            <?php $isCarton = ($ligne['unite'] ?? 'kg') === 'carton'; ?>
            <div class="r-item-qty">
                <?= (int)$ligne['quantite'] ?> <?= $isCarton ? 'carton'.($ligne['quantite']>1?'s':'') : 'kg' ?>
                &bull; <?= formatPrice((float)$ligne['prix_unitaire']) ?> / <?= $isCarton ? 'carton' : 'kg' ?>
                <?php if ($isCarton && !empty($ligne['poids_carton_kg'])): ?>
                <span style="color:#0284c7;"><?= number_format((float)$ligne['poids_carton_kg'],0) ?> kg net / carton</span>
                <?php endif; ?>
            </div>
          </div>
          <div class="r-item-price"><?= formatPrice((float)$ligne['total_ligne']) ?></div>
        </div>
        <?php endforeach; ?>

        <div class="r-sep"></div>
        <div class="r-row"><span>Sous-total</span><span><?= formatPrice((float)$commande['sous_total']) ?></span></div>
        <?php if (!empty($commande['remise']) && (float)$commande['remise'] > 0): ?>
        <div class="r-row" style="color:#16a34a;">
          <span style="display:flex;align-items:center;gap:6px;">
            <i class="bi bi-scissors"></i> Remise
            <?php if (!empty($commande['code_promo'])): ?>
            <span style="background:#dcfce7;border:1px solid #bbf7d0;border-radius:5px;padding:1px 8px;font-family:monospace;font-size:.72rem;font-weight:700;color:#15803d;letter-spacing:1px;"><?= htmlspecialchars($commande['code_promo']) ?></span>
            <?php endif; ?>
          </span>
          <span style="font-weight:700;">-<?= formatPrice((float)$commande['remise']) ?></span>
        </div>
        <?php endif; ?>
        <?php $frais = (float)($commande['frais_livraison'] ?? 0); ?>
        <div class="r-row">
          <span>Livraison</span>
          <?php if ($frais > 0): ?>
          <span style="font-weight:700;"><?= formatPrice($frais) ?></span>
          <?php else: ?>
          <span style="color:#16a34a;font-weight:700;">Gratuite</span>
          <?php endif; ?>
        </div>
        <div class="r-total">
          <span class="r-total-label">Total</span>
          <span class="r-total-val"><?= formatPrice((float)$commande['total']) ?></span>
        </div>

      </div>
    </div>

    <!-- Actions -->
    <div class="ds-card">
      <div class="ds-card-hd">
        <div class="ds-card-hd-icon ds-card-hd-icon-green"><i class="bi bi-send-fill"></i></div>
        <div class="ds-card-title">Suivre &amp; contacter</div>
      </div>
      <div class="ds-card-bd">
        <?php
          $waMsg = "Bonjour Darou Salam, je souhaite suivre ma commande N° " . $reference . ". Total : " . number_format((float)$commande['total'],0,',',' ') . " FCFA. Merci !";
        ?>
        <a href="https://wa.me/221774715353?text=<?= urlencode($waMsg) ?>" target="_blank" class="btn-wa-conf">
          <i class="bi bi-whatsapp fs-5"></i> Suivre via WhatsApp
        </a>
        <a href="<?= BASE_URL ?>?page=historique" class="btn-cmds">
          <i class="bi bi-grid-1x2"></i> Mes commandes
        </a>
        <?php if (in_array($currentStatut, ['confirmee','en_preparation','en_cours','expediee','livree'])): ?>
        <a href="<?= BASE_URL ?>?page=commande_facture&id=<?= $commande['id'] ?>" target="_blank"
           style="display:flex;align-items:center;justify-content:center;gap:7px;padding:11px 20px;background:#eff6ff;color:#2563eb;border:1.5px solid #bfdbfe;border-radius:12px;font-size:.84rem;font-weight:700;text-decoration:none;transition:all .2s;"
           onmouseover="this.style.background='#2563eb';this.style.color='#fff'" onmouseout="this.style.background='#eff6ff';this.style.color='#2563eb'">
          <i class="bi bi-file-earmark-pdf-fill"></i> Télécharger la facture
        </a>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>?page=catalogue" class="btn-cont">
          <i class="bi bi-arrow-left"></i> Continuer mes achats
        </a>
      </div>
    </div>

  </div>
</div>

</div><!-- /container -->

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
