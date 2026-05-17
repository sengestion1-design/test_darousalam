<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion — Darou Salam Business Company</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    :root{--vert:#1a5c2a;--vert-l:#2d8a42;--vert-xs:#e8f5eb;--orange:#f97316;--sable:#fdf6ec;--texte:#1c1917;--gris:#78716c;}
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
    body{font-family:'Poppins',sans-serif;background:var(--sable);min-height:100vh;display:flex;align-items:stretch;}
    .side-panel{width:440px;min-width:440px;background:var(--vert);position:relative;overflow:hidden;display:flex;flex-direction:column;justify-content:center;align-items:center;padding:3rem 2.5rem;text-align:center;}
    .side-panel::before{content:'';position:absolute;width:600px;height:600px;border-radius:50%;background:rgba(249,115,22,.12);top:-200px;left:-200px;}
    .side-panel::after{content:'';position:absolute;width:400px;height:400px;border-radius:50%;background:rgba(255,255,255,.05);bottom:-150px;right:-150px;}
    .side-content{position:relative;z-index:2;}
    .brand{font-family:'Playfair Display',serif;font-size:2rem;color:#fff;line-height:1.2;margin-bottom:.5rem;}
    .tagline{font-size:.8rem;color:rgba(255,255,255,.6);letter-spacing:.1em;text-transform:uppercase;margin-bottom:3rem;}
    .fruit-big{font-size:6rem;line-height:1;margin-bottom:1.5rem;filter:drop-shadow(0 8px 24px rgba(0,0,0,.3));}
    .side-quote{font-size:1.1rem;color:rgba(255,255,255,.9);line-height:1.7;font-style:italic;margin-bottom:2rem;}
    .side-quote span{color:var(--orange);font-style:normal;font-weight:700;}
    .trust-badges{display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;}
    .badge-item{background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);border-radius:10px;padding:.5rem .9rem;font-size:.72rem;color:rgba(255,255,255,.85);display:flex;align-items:center;gap:.4rem;}
    .form-col{flex:1;display:flex;align-items:center;justify-content:center;padding:3rem 2rem;overflow-y:auto;}
    .form-wrap{width:100%;max-width:440px;}
    .form-header{margin-bottom:2.5rem;}
    .form-title{font-size:2rem;font-weight:800;color:var(--texte);margin-bottom:.4rem;}
    .form-sub{font-size:.88rem;color:var(--gris);}
    .field-group{margin-bottom:1.2rem;}
    .field-label{font-size:.78rem;font-weight:600;color:var(--texte);margin-bottom:.4rem;display:block;}
    .field-input{width:100%;padding:.78rem 1rem .78rem 2.8rem;border:2px solid #e5e7eb;border-radius:12px;font-family:'Poppins',sans-serif;font-size:.9rem;color:var(--texte);background:#fff;transition:border-color .2s,box-shadow .2s;outline:none;}
    .field-input:focus{border-color:var(--vert);box-shadow:0 0 0 4px rgba(26,92,42,.08);}
    .field-input.error{border-color:#ef4444;box-shadow:0 0 0 3px rgba(239,68,68,.08);}
    .input-wrap{position:relative;}
    .input-ico{position:absolute;left:.85rem;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:1rem;}
    .input-ico-r{position:absolute;right:.85rem;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:1rem;cursor:pointer;}
    .field-error{font-size:.72rem;color:#ef4444;margin-top:.35rem;display:none;}
    .remember-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;}
    .remember-box{display:flex;align-items:center;gap:.5rem;font-size:.82rem;color:var(--gris);cursor:pointer;}
    .remember-box input{accent-color:var(--vert);width:15px;height:15px;}
    .forgot-link{font-size:.82rem;color:var(--orange);text-decoration:none;font-weight:600;}
    .forgot-link:hover{color:var(--vert);}
    .btn-login{width:100%;padding:.92rem;background:linear-gradient(135deg,var(--vert),var(--vert-l));color:#fff;border:none;border-radius:14px;font-family:'Poppins',sans-serif;font-size:1rem;font-weight:700;cursor:pointer;transition:all .3s;position:relative;overflow:hidden;}
    .btn-login::after{content:'';position:absolute;inset:0;background:rgba(255,255,255,0);transition:background .2s;}
    .btn-login:hover{transform:translateY(-2px);box-shadow:0 10px 28px rgba(26,92,42,.35);}
    .btn-login:hover::after{background:rgba(255,255,255,.05);}
    .divider{display:flex;align-items:center;gap:1rem;margin:1.5rem 0;}
    .divider::before,.divider::after{content:'';flex:1;height:1px;background:#e5e7eb;}
    .divider span{font-size:.75rem;color:var(--gris);white-space:nowrap;}
    .social-row{display:grid;grid-template-columns:1fr 1fr;gap:.8rem;margin-bottom:1.5rem;}
    .btn-social{padding:.7rem;border:2px solid #e5e7eb;border-radius:12px;background:#fff;font-family:'Poppins',sans-serif;font-size:.82rem;font-weight:600;color:var(--texte);cursor:pointer;transition:all .2s;display:flex;align-items:center;justify-content:center;gap:.5rem;}
    .btn-social:hover{border-color:var(--vert);background:var(--vert-xs);}
    .register-link{text-align:center;font-size:.84rem;color:var(--gris);}
    .register-link a{color:var(--vert);font-weight:700;text-decoration:none;}
    .register-link a:hover{color:var(--orange);}
    .alert-custom{padding:.9rem 1rem;border-radius:12px;font-size:.84rem;margin-bottom:1.2rem;display:none;align-items:center;gap:.6rem;}
    .alert-danger{background:#fef2f2;border:1px solid #fecaca;color:#dc2626;}
    @media(max-width:900px){.side-panel{display:none;}body{display:block;}.form-col{padding:2rem 1.2rem;min-height:100vh;}}
  </style>
</head>
<body>
<div class="side-panel">
  <div class="side-content">
    <div class="brand">Darou Salam<br>Business Co.</div>
    <div class="tagline">Fruits frais du Sénégal</div>
    <div class="fruit-big">&#x1F96D;</div>
    <p class="side-quote">"Des fruits cueillis ce matin, <span>dans votre assiette ce soir</span> — partout à Dakar."</p>
    <div class="trust-badges">
      <div class="badge-item"><i class="bi bi-shield-check"></i> Paiement sécurisé</div>
      <div class="badge-item"><i class="bi bi-truck"></i> Livraison 24h</div>
      <div class="badge-item"><i class="bi bi-star-fill"></i> 4.9/5 avis</div>
      <div class="badge-item"><i class="bi bi-leaf"></i> Fruits frais</div>
    </div>
  </div>
</div>

<div class="form-col">
  <div class="form-wrap">
    <div class="form-header">
      <div style="font-size:.72rem;letter-spacing:.12em;text-transform:uppercase;color:var(--orange);font-weight:600;margin-bottom:.5rem;">Bon retour !</div>
      <div class="form-title">Connexion</div>
      <div class="form-sub">Accédez à votre espace personnel Darou Salam.</div>
    </div>

    <div class="alert-custom alert-danger" id="login-error">
      <i class="bi bi-exclamation-triangle-fill"></i>
      <span id="login-error-msg">Email ou mot de passe incorrect.</span>
    </div>

    <form id="login-form" novalidate action="/darousalam/auth/connexion" method="POST">
      <?php if (isset($_SESSION['csrf_token'])): ?>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
      <?php endif; ?>

      <div class="field-group">
        <label class="field-label" for="email">Adresse email</label>
        <div class="input-wrap">
          <i class="bi bi-envelope input-ico"></i>
          <input type="email" id="email" name="email" class="field-input" placeholder="votre@email.com" autocomplete="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        <div class="field-error" id="err-email">Veuillez saisir un email valide.</div>
      </div>

      <div class="field-group">
        <label class="field-label" for="password">Mot de passe</label>
        <div class="input-wrap">
          <i class="bi bi-lock input-ico"></i>
          <input type="password" id="password" name="password" class="field-input" placeholder="Votre mot de passe" autocomplete="current-password">
          <i class="bi bi-eye input-ico-r" id="toggle-pw" onclick="togglePw()"></i>
        </div>
        <div class="field-error" id="err-password">Le mot de passe est requis.</div>
      </div>

      <div class="remember-row">
        <label class="remember-box">
          <input type="checkbox" name="remember" id="remember"> Se souvenir de moi
        </label>
        <a href="mot-de-passe-oublie.php" class="forgot-link">Mot de passe oublié ?</a>
      </div>

      <button type="submit" class="btn-login">
        <i class="bi bi-box-arrow-in-right" style="margin-right:.5rem;"></i> Se connecter
      </button>
    </form>

    <div class="divider"><span>ou continuer avec</span></div>

    <div class="social-row">
      <button class="btn-social" onclick="alert('Connexion Google à configurer')">
        <svg width="18" height="18" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
        Google
      </button>
      <button class="btn-social" onclick="alert('Connexion Facebook à configurer')">
        <svg width="18" height="18" viewBox="0 0 24 24"><path fill="#1877F2" d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
        Facebook
      </button>
    </div>

    <div class="register-link">
      Pas encore de compte ? <a href="inscription.php">Créer un compte</a>
    </div>

    <div style="margin-top:2rem;padding:1rem;background:#f0fdf4;border-radius:12px;border:1px solid #bbf7d0;font-size:.75rem;color:#166534;text-align:center;">
      <i class="bi bi-shield-lock-fill" style="margin-right:.3rem;"></i>
      Connexion chiffrée SSL &bull; Vos données sont protégées
    </div>
  </div>
</div>

<script>
  function togglePw() {
    var inp = document.getElementById('password');
    var ico = document.getElementById('toggle-pw');
    if (inp.type === 'password') { inp.type = 'text'; ico.className = 'bi bi-eye-slash input-ico-r'; }
    else { inp.type = 'password'; ico.className = 'bi bi-eye input-ico-r'; }
  }
  document.getElementById('login-form').addEventListener('submit', function(e) {
    var ok = true;
    var email = document.getElementById('email');
    var pw = document.getElementById('password');
    var errEmail = document.getElementById('err-email');
    var errPw = document.getElementById('err-password');
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
      email.classList.add('error'); errEmail.style.display = 'block'; ok = false;
    } else { email.classList.remove('error'); errEmail.style.display = 'none'; }
    if (!pw.value.trim()) {
      pw.classList.add('error'); errPw.style.display = 'block'; ok = false;
    } else { pw.classList.remove('error'); errPw.style.display = 'none'; }
    if (!ok) e.preventDefault();
  });
  <?php if (isset($erreur) && $erreur): ?>
    var errBox = document.getElementById('login-error');
    errBox.style.display = 'flex';
    document.getElementById('login-error-msg').textContent = "<?= htmlspecialchars($erreur) ?>";
  <?php endif; ?>
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
