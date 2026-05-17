<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/mail.php';

class EmailService
{
    private bool $logoEmbedded = false;

    private function logoPath(): string
    {
        return __DIR__ . '/../../logo.jpg';
    }

    private function header(): string
    {
        $logoPath = $this->logoPath();
        if (file_exists($logoPath)) {
            $imgTag = '<img src="cid:logo_darousalam" alt="Darou Salam" width="70" height="70" style="border-radius:50%;border:3px solid #d4a017;display:block;margin:0 auto 12px;">';
        } else {
            $imgTag = '<div style="width:70px;height:70px;border-radius:50%;background:#d4a017;margin:0 auto 12px;line-height:70px;text-align:center;font-size:1.8rem;">🌿</div>';
        }

        return '
      <tr><td style="background:linear-gradient(135deg,#050d07 0%,#0f2d16 50%,#1a5c2a 100%);border-radius:20px 20px 0 0;padding:28px 40px;text-align:center;">
        ' . $imgTag . '
        <div style="font-family:Georgia,serif;font-size:1.4rem;font-weight:900;color:#fff;letter-spacing:-.01em;">Darou Salam</div>
        <div style="font-size:.62rem;color:#d4a017;text-transform:uppercase;letter-spacing:.2em;margin-top:3px;">Business Company</div>
      </td></tr>';
    }

    private function footer(): string
    {
        return '
      <tr><td style="background:linear-gradient(135deg,#050d07,#0f2d16);border-radius:0 0 20px 20px;padding:24px 40px;">
        <table width="100%" cellpadding="0" cellspacing="0">
          <tr>
            <td style="text-align:center;padding-bottom:14px;">
              <a href="https://wa.me/221774715353" style="display:inline-block;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);color:#fff;padding:8px 18px;border-radius:30px;font-size:.75rem;font-weight:600;text-decoration:none;margin:0 4px;">
                WhatsApp
              </a>
              <a href="tel:+221774715353" style="display:inline-block;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);color:#fff;padding:8px 18px;border-radius:30px;font-size:.75rem;font-weight:600;text-decoration:none;margin:0 4px;">
                +221 77 471 53 53
              </a>
            </td>
          </tr>
          <tr>
            <td style="text-align:center;border-top:1px solid rgba(255,255,255,.08);padding-top:14px;">
              <div style="font-size:.72rem;color:rgba(255,255,255,.4);line-height:1.8;">
                Dakar Médina, Rue 47×37, Sénégal<br>
                © 2026 Darou Salam Business Company · Tous droits réservés
              </div>
            </td>
          </tr>
        </table>
      </td></tr>';
    }

    private function wrapper(string $content): string
    {
        return '<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="color-scheme" content="light">
</head>
<body style="margin:0;padding:0;background:#f0ede8;font-family:Arial,Helvetica,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0ede8;padding:32px 16px;">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;border-radius:20px;overflow:hidden;box-shadow:0 8px 40px rgba(0,0,0,.12);">
      ' . $content . '
    </table>
  </td></tr>
</table>
</body>
</html>';
    }

    /** @param array<string,string> $embedImages ['cid' => '/absolute/path/to/image.jpg'] */
    public function send(string $to, string $subject, string $htmlBody, array $embedImages = []): bool
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = MAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = MAIL_USERNAME;
            $mail->Password   = MAIL_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = MAIL_PORT;
            $mail->CharSet    = 'UTF-8';
            $mail->Timeout    = 10;
            $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
            $mail->addReplyTo(MAIL_REPLY_TO, MAIL_FROM_NAME);
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $htmlBody;
            $mail->AltBody = strip_tags(str_replace(['<br>','<br/>','</p>','</div>','</tr>'], "\n", $htmlBody));

            // Logo embed
            $logoPath = $this->logoPath();
            if (file_exists($logoPath)) {
                $mail->addEmbeddedImage($logoPath, 'logo_darousalam', 'logo.jpg', 'base64', 'image/jpeg');
            }

            // Product images embed
            foreach ($embedImages as $cid => $path) {
                if (file_exists($path)) {
                    $ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                    $mime = ($ext === 'png') ? 'image/png' : (($ext === 'webp') ? 'image/webp' : 'image/jpeg');
                    $mail->addEmbeddedImage($path, $cid, basename($path), 'base64', $mime);
                }
            }

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('EmailService error: ' . $mail->ErrorInfo);
            return false;
        }
    }

    public function envoyerConfirmationCommande(array $commande, array $lignes): bool
    {
        $clientEmail = $commande['client_email'] ?? '';
        if (empty($clientEmail)) return false;

        $clientNom = trim(($commande['client_prenom'] ?? '') . ' ' . ($commande['client_nom'] ?? ''));
        if (!$clientNom) $clientNom = 'Cher client';

        $adresse = json_decode($commande['adresse_livraison'] ?? '{}', true);

        $paiementsLib = [
            'cash'         => 'Paiement à la livraison',
            'wave'         => 'Wave',
            'orange_money' => 'Orange Money',
        ];
        $paiementLib = $paiementsLib[$commande['mode_paiement']] ?? $commande['mode_paiement'];

        // Articles
        $lignesHtml  = '';
        $embedImages = [];
        $uploadBase  = __DIR__ . '/../../';
        foreach ($lignes as $i => $ligne) {
            $imgPath = !empty($ligne['image_principale']) ? $uploadBase . $ligne['image_principale'] : '';
            if ($imgPath && file_exists($imgPath)) {
                $cid = 'produit_img_' . $i;
                $embedImages[$cid] = $imgPath;
                $imgHtml = '<img src="cid:' . $cid . '" alt="" width="52" height="52" style="width:52px;height:52px;object-fit:cover;border-radius:10px;border:1px solid #e5e7eb;display:block;">';
            } else {
                $imgHtml = '<div style="width:52px;height:52px;background:#f5f3ee;border-radius:10px;border:1px solid #e5e7eb;text-align:center;line-height:52px;font-size:.7rem;color:#9ca3af;font-weight:600;">Photo</div>';
            }

            $lignesHtml .= '
            <tr>
              <td style="padding:14px 0;border-bottom:1px solid #f3f4f6;vertical-align:middle;">
                <table cellpadding="0" cellspacing="0"><tr>
                  <td style="padding-right:14px;vertical-align:middle;">' . $imgHtml . '</td>
                  <td style="vertical-align:middle;">
                    <div style="font-weight:700;font-size:.88rem;color:#1c1917;">' . htmlspecialchars($ligne['nom_produit']) . '</div>
                    <div style="font-size:.76rem;color:#9ca3af;margin-top:4px;">'
                        . number_format($ligne['quantite'], 1, ',', ' ') . ' ' . ($ligne['unite'] ?? 'kg')
                        . ' &times; ' . number_format($ligne['prix_unitaire'], 0, ',', ' ') . ' FCFA'
                    . '</div>
                  </td>
                </tr></table>
              </td>
              <td style="padding:14px 0;border-bottom:1px solid #f3f4f6;text-align:right;font-weight:800;color:#f97316;white-space:nowrap;font-size:.92rem;">'
                . number_format($ligne['total_ligne'], 0, ',', ' ') . ' FCFA'
              . '</td>
            </tr>';
        }

        $subject = 'Commande ' . $commande['reference'] . ' confirmée — Darou Salam';

        $content = $this->header() . '

      <!-- Bandeau confirmation -->
      <tr><td style="background:#f8fdf9;border-bottom:3px solid #1a5c2a;padding:36px 40px;text-align:center;">
        <div style="width:64px;height:64px;background:#1a5c2a;border-radius:50%;margin:0 auto 18px;line-height:64px;text-align:center;color:#fff;font-size:1.6rem;">✓</div>
        <div style="font-size:1.35rem;font-weight:900;color:#0f2d16;margin-bottom:10px;font-family:Georgia,serif;">Commande reçue</div>
        <div style="font-size:.9rem;color:#4b5563;line-height:1.75;">
          Bonjour <strong>' . htmlspecialchars($clientNom) . '</strong>, merci pour votre commande.<br>
          Notre équipe va la prendre en charge sous peu.
        </div>
        <div style="margin-top:22px;display:inline-block;background:#fff;border:1.5px solid #d1d5db;padding:10px 28px;border-radius:8px;">
          <span style="font-size:.65rem;color:#9ca3af;text-transform:uppercase;letter-spacing:.12em;display:block;margin-bottom:4px;">Référence</span>
          <span style="font-size:1rem;font-weight:900;color:#1a5c2a;letter-spacing:.06em;">' . htmlspecialchars($commande['reference']) . '</span>
        </div>
      </td></tr>

      <!-- Articles -->
      <tr><td style="background:#fff;padding:28px 40px 20px;">
        <div style="font-size:.65rem;font-weight:800;color:#9ca3af;text-transform:uppercase;letter-spacing:.12em;margin-bottom:16px;">Votre commande</div>
        <table width="100%" cellpadding="0" cellspacing="0">
          ' . $lignesHtml . '
          <tr>
            <td style="padding:12px 0 4px;font-size:.82rem;color:#9ca3af;">Sous-total</td>
            <td style="padding:12px 0 4px;text-align:right;font-size:.82rem;color:#374151;">' . number_format($commande['sous_total'], 0, ',', ' ') . ' FCFA</td>
          </tr>
          <tr>
            <td style="padding:4px 0;font-size:.82rem;color:#9ca3af;">Livraison</td>
            <td style="padding:4px 0;text-align:right;font-size:.82rem;color:#16a34a;font-weight:700;">' . ($commande['frais_livraison'] > 0 ? number_format($commande['frais_livraison'], 0, ',', ' ') . ' FCFA' : 'Offerte') . '</td>
          </tr>
          <tr>
            <td colspan="2"><div style="height:1px;background:#e5e7eb;margin:12px 0;"></div></td>
          </tr>
          <tr>
            <td style="font-size:1rem;font-weight:800;color:#1c1917;">Total</td>
            <td style="text-align:right;font-size:1.1rem;font-weight:900;color:#f97316;">' . number_format($commande['total'], 0, ',', ' ') . ' FCFA</td>
          </tr>
        </table>
      </td></tr>

      <!-- Livraison + Paiement -->
      <tr><td style="background:#fff;padding:0 40px 28px;">
        <table width="100%" cellpadding="0" cellspacing="0">
          <tr>
            <td width="48%" style="vertical-align:top;">
              <div style="background:#f9fafb;border-radius:12px;padding:16px 18px;border:1px solid #e5e7eb;">
                <div style="font-size:.65rem;font-weight:800;color:#9ca3af;text-transform:uppercase;letter-spacing:.1em;margin-bottom:10px;">Livraison</div>
                ' . (!empty($adresse['adresse']) ? '<div style="font-size:.82rem;color:#374151;margin-bottom:5px;">' . htmlspecialchars($adresse['adresse']) . '</div>' : '') . '
                ' . (!empty($adresse['ville'])   ? '<div style="font-size:.82rem;color:#374151;margin-bottom:5px;">' . htmlspecialchars($adresse['ville'])   . '</div>' : '') . '
                ' . (!empty($adresse['telephone'])? '<div style="font-size:.82rem;color:#374151;">' . htmlspecialchars($adresse['telephone']) . '</div>' : '') . '
              </div>
            </td>
            <td width="4%"></td>
            <td width="48%" style="vertical-align:top;">
              <div style="background:#f9fafb;border-radius:12px;padding:16px 18px;border:1px solid #e5e7eb;">
                <div style="font-size:.65rem;font-weight:800;color:#9ca3af;text-transform:uppercase;letter-spacing:.1em;margin-bottom:10px;">Paiement</div>
                <div style="font-size:.85rem;font-weight:700;color:#374151;margin-bottom:8px;">' . $paiementLib . '</div>
                <div style="font-size:.7rem;color:#b45309;font-weight:600;background:#fffbeb;padding:4px 10px;border-radius:6px;display:inline-block;border:1px solid #fde68a;">En attente</div>
              </div>
            </td>
          </tr>
        </table>
      </td></tr>

      <!-- Suivi étapes -->
      <tr><td style="background:#fff;padding:0 40px 28px;">
        <div style="background:#f9fafb;border-radius:12px;padding:20px 24px;border:1px solid #e5e7eb;">
          <div style="font-size:.65rem;font-weight:800;color:#9ca3af;text-transform:uppercase;letter-spacing:.1em;margin-bottom:16px;text-align:center;">Suivi de votre commande</div>
          <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
              <td style="text-align:center;padding:0 4px;">
                <div style="width:34px;height:34px;background:#1a5c2a;border-radius:50%;margin:0 auto 6px;line-height:34px;text-align:center;color:#fff;font-size:.82rem;font-weight:700;">✓</div>
                <div style="font-size:.63rem;color:#1a5c2a;font-weight:700;">Reçue</div>
              </td>
              <td><div style="height:1px;background:#d1fae5;"></div></td>
              <td style="text-align:center;padding:0 4px;">
                <div style="width:34px;height:34px;background:#e5e7eb;border-radius:50%;margin:0 auto 6px;line-height:34px;text-align:center;color:#9ca3af;font-size:.72rem;">2</div>
                <div style="font-size:.63rem;color:#9ca3af;font-weight:500;">Préparation</div>
              </td>
              <td><div style="height:1px;background:#e5e7eb;"></div></td>
              <td style="text-align:center;padding:0 4px;">
                <div style="width:34px;height:34px;background:#e5e7eb;border-radius:50%;margin:0 auto 6px;line-height:34px;text-align:center;color:#9ca3af;font-size:.72rem;">3</div>
                <div style="font-size:.63rem;color:#9ca3af;font-weight:500;">Livraison</div>
              </td>
              <td><div style="height:1px;background:#e5e7eb;"></div></td>
              <td style="text-align:center;padding:0 4px;">
                <div style="width:34px;height:34px;background:#e5e7eb;border-radius:50%;margin:0 auto 6px;line-height:34px;text-align:center;color:#9ca3af;font-size:.72rem;">4</div>
                <div style="font-size:.63rem;color:#9ca3af;font-weight:500;">Livrée</div>
              </td>
            </tr>
          </table>
        </div>
      </td></tr>

      <!-- CTA -->
      <tr><td style="background:#fff;padding:0 40px 36px;text-align:center;">
        <a href="' . BASE_URL . '?page=historique"
           style="display:inline-block;background:#1a5c2a;color:#fff;padding:14px 40px;border-radius:8px;font-size:.88rem;font-weight:800;text-decoration:none;letter-spacing:.03em;">
          Suivre ma commande
        </a>
        <div style="margin-top:16px;font-size:.76rem;color:#9ca3af;">
          Une question ? Contactez-nous sur
          <a href="https://wa.me/221774715353" style="color:#1a5c2a;font-weight:700;text-decoration:none;">WhatsApp</a>
          ou au <strong>+221 77 471 53 53</strong>
        </div>
      </td></tr>

      ' . $this->footer();

        return $this->send($clientEmail, $subject, $this->wrapper($content), $embedImages);
    }

    public function envoyerChangementStatut(array $commande, string $ancienStatut, string $nouveauStatut): bool
    {
        $clientEmail = $commande['client_email'] ?? '';
        if (empty($clientEmail)) return false;

        $clientNom = trim(($commande['client_prenom'] ?? '') . ' ' . ($commande['client_nom'] ?? ''));
        if (!$clientNom) $clientNom = 'Cher client';

        $statutsInfo = [
            'en_attente'     => ['lib'=>'En attente',     'color'=>'#1d4ed8','bg'=>'#eff6ff','icon'=>'⏸','msg'=>'Votre commande est en attente de traitement.'],
            'confirmee'      => ['lib'=>'Confirmée',      'color'=>'#0e7490','bg'=>'#f0fdfa','icon'=>'✓', 'msg'=>'Votre commande a été confirmée par notre équipe.'],
            'en_preparation' => ['lib'=>'En préparation', 'color'=>'#92400e','bg'=>'#fffbeb','icon'=>'◎', 'msg'=>'Notre équipe sélectionne et prépare vos fruits avec soin.'],
            'en_livraison'   => ['lib'=>'En livraison',   'color'=>'#6d28d9','bg'=>'#f5f3ff','icon'=>'→', 'msg'=>'Votre commande est en route vers vous.'],
            'livree'         => ['lib'=>'Livrée',         'color'=>'#166534','bg'=>'#f0fdf4','icon'=>'✓', 'msg'=>'Votre commande a été livrée. Merci de votre confiance.'],
            'annulee'        => ['lib'=>'Annulée',        'color'=>'#991b1b','bg'=>'#fef2f2','icon'=>'×', 'msg'=>'Votre commande a été annulée. Contactez-nous pour toute information.'],
        ];

        $info    = $statutsInfo[$nouveauStatut] ?? ['lib'=>$nouveauStatut,'color'=>'#374151','bg'=>'#f9fafb','icon'=>'·','msg'=>'Le statut de votre commande a été mis à jour.'];
        $adresse = json_decode($commande['adresse_livraison'] ?? '{}', true);
        $ville   = $adresse['ville'] ?? '';

        $steps = ['en_attente','confirmee','en_preparation','en_livraison','livree'];
        $stepLabels = ['En attente','Confirmée','Préparation','Livraison','Livrée'];
        $currentStep = array_search($nouveauStatut, $steps);
        $stepsHtml = '';
        foreach ($steps as $i => $step) {
            $done = ($currentStep !== false && $i <= $currentStep && $nouveauStatut !== 'annulee');
            $bg   = $done ? '#1a5c2a' : '#e5e7eb';
            $tc   = $done ? '#1a5c2a' : '#9ca3af';
            $num  = $done ? '✓' : ($i + 1);
            $stepsHtml .= '<td style="text-align:center;padding:0 4px;">
              <div style="width:34px;height:34px;border-radius:50%;background:' . $bg . ';margin:0 auto 6px;line-height:34px;text-align:center;color:#fff;font-size:.78rem;font-weight:700;">' . $num . '</div>
              <div style="font-size:.62rem;color:' . $tc . ';font-weight:' . ($done ? '700' : '500') . ';">' . $stepLabels[$i] . '</div>
            </td>';
            if ($i < count($steps) - 1) {
                $lc = ($currentStep !== false && $i < $currentStep && $nouveauStatut !== 'annulee') ? '#d1fae5' : '#e5e7eb';
                $stepsHtml .= '<td style="padding-bottom:16px;"><div style="height:1px;background:' . $lc . ';"></div></td>';
            }
        }

        $subject = 'Commande ' . $commande['reference'] . ' — ' . $info['lib'] . ' · Darou Salam';

        $content = $this->header() . '

      <!-- Statut -->
      <tr><td style="background:' . $info['bg'] . ';padding:36px 40px;text-align:center;border-bottom:3px solid ' . $info['color'] . ';">
        <div style="width:60px;height:60px;border-radius:50%;background:' . $info['color'] . ';margin:0 auto 16px;line-height:60px;text-align:center;color:#fff;font-size:1.4rem;font-weight:900;">' . $info['icon'] . '</div>
        <div style="font-size:1.2rem;font-weight:900;color:#0f2d16;margin-bottom:8px;font-family:Georgia,serif;">Mise à jour de votre commande</div>
        <div style="display:inline-block;background:' . $info['color'] . ';color:#fff;padding:6px 20px;border-radius:6px;font-size:.82rem;font-weight:700;margin-bottom:16px;">' . $info['lib'] . '</div>
        <div style="font-size:.9rem;color:#4b5563;line-height:1.75;">
          Bonjour <strong>' . htmlspecialchars($clientNom) . '</strong>,<br>' . $info['msg'] . '
        </div>
      </td></tr>

      ' . ($nouveauStatut !== 'annulee' ? '
      <!-- Progression -->
      <tr><td style="background:#fff;padding:24px 40px;">
        <div style="background:#f9fafb;border-radius:12px;padding:20px 16px;border:1px solid #e5e7eb;">
          <div style="font-size:.65rem;font-weight:800;color:#9ca3af;text-transform:uppercase;letter-spacing:.12em;margin-bottom:14px;text-align:center;">Avancement</div>
          <table width="100%" cellpadding="0" cellspacing="0"><tr>' . $stepsHtml . '</tr></table>
        </div>
      </td></tr>' : '') . '

      <!-- Détails commande -->
      <tr><td style="background:#fff;padding:' . ($nouveauStatut !== 'annulee' ? '0' : '24px') . ' 40px 24px;">
        <div style="border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;">
          <div style="background:#f9fafb;padding:12px 20px;font-size:.65rem;font-weight:800;color:#374151;text-transform:uppercase;letter-spacing:.1em;">Détails</div>
          <div style="padding:14px 20px;">
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td style="padding:5px 0;font-size:.82rem;color:#9ca3af;">Référence</td>
                <td style="padding:5px 0;font-size:.82rem;font-weight:700;color:#1c1917;text-align:right;">' . htmlspecialchars($commande['reference']) . '</td>
              </tr>
              <tr>
                <td style="padding:5px 0;font-size:.82rem;color:#9ca3af;">Total</td>
                <td style="padding:5px 0;font-size:.9rem;font-weight:900;color:#f97316;text-align:right;">' . number_format($commande['total'], 0, ',', ' ') . ' FCFA</td>
              </tr>
              ' . ($ville ? '<tr><td style="padding:5px 0;font-size:.82rem;color:#9ca3af;">Ville</td><td style="padding:5px 0;font-size:.82rem;font-weight:700;color:#1c1917;text-align:right;">' . htmlspecialchars($ville) . '</td></tr>' : '') . '
            </table>
          </div>
        </div>
      </td></tr>

      <!-- CTA -->
      <tr><td style="background:#fff;padding:0 40px 36px;text-align:center;">
        <a href="' . BASE_URL . '?page=historique"
           style="display:inline-block;background:#1a5c2a;color:#fff;padding:14px 40px;border-radius:8px;font-size:.88rem;font-weight:800;text-decoration:none;letter-spacing:.03em;">
          Voir mes commandes
        </a>
        <div style="margin-top:14px;font-size:.75rem;color:#9ca3af;">
          Une question ?
          <a href="https://wa.me/221774715353" style="color:#1a5c2a;font-weight:700;text-decoration:none;">WhatsApp</a>
          · <strong>+221 77 471 53 53</strong>
        </div>
      </td></tr>

      ' . $this->footer();

        return $this->send($clientEmail, $subject, $this->wrapper($content));
    }

    public function envoyerReinitialisationMdp(string $email, string $resetLink): bool
    {
        $subject = 'Réinitialisation de votre mot de passe — Darou Salam';

        $content = $this->header() . '

      <!-- Corps -->
      <tr><td style="background:#fff;padding:44px 40px 32px;text-align:center;">
        <div style="width:64px;height:64px;background:#fef2f2;border-radius:50%;margin:0 auto 18px;line-height:64px;text-align:center;font-size:1.5rem;border:2px solid #fecaca;color:#dc2626;font-weight:900;">⊙</div>
        <div style="font-size:1.2rem;font-weight:900;color:#0f2d16;margin-bottom:10px;font-family:Georgia,serif;">Réinitialisation du mot de passe</div>
        <div style="font-size:.9rem;color:#6b7280;line-height:1.8;margin-bottom:28px;">
          Vous avez demandé à réinitialiser votre mot de passe.<br>
          Cliquez sur le bouton ci-dessous pour en choisir un nouveau.<br>
          <span style="background:#fef2f2;color:#dc2626;font-weight:700;padding:3px 10px;border-radius:6px;font-size:.78rem;border:1px solid #fecaca;display:inline-block;margin-top:8px;">Lien valable 1 heure</span>
        </div>
        <a href="' . $resetLink . '"
           style="display:inline-block;background:#1a5c2a;color:#fff;padding:16px 44px;border-radius:8px;font-size:.92rem;font-weight:800;text-decoration:none;letter-spacing:.03em;">
          Réinitialiser mon mot de passe
        </a>
      </td></tr>

      <!-- Lien secours -->
      <tr><td style="background:#fff;padding:0 40px 32px;">
        <div style="background:#f9fafb;border-radius:10px;padding:16px 20px;border:1px solid #e5e7eb;">
          <div style="font-size:.7rem;font-weight:700;color:#9ca3af;margin-bottom:6px;">Lien de secours (si le bouton ne fonctionne pas) :</div>
          <div style="font-size:.7rem;color:#1a5c2a;font-weight:600;word-break:break-all;">' . $resetLink . '</div>
        </div>
      </td></tr>

      <!-- Sécurité -->
      <tr><td style="background:#fff;padding:0 40px 36px;text-align:center;">
        <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:14px 20px;font-size:.78rem;color:#92400e;line-height:1.6;">
          Si vous n\'avez pas demandé cette réinitialisation, ignorez cet email.<br>
          Votre mot de passe restera <strong>inchangé</strong>.
        </div>
      </td></tr>

      ' . $this->footer();

        return $this->send($email, $subject, $this->wrapper($content));
    }

    public function envoyerFacture(array $commande, array $lignes): bool
    {
        $clientEmail = $commande['client_email'] ?? '';
        if (!$clientEmail) return false;

        $clientNom = trim(($commande['client_prenom'] ?? '') . ' ' . ($commande['client_nom'] ?? ''));
        $ref       = htmlspecialchars($commande['reference']);
        $total     = number_format((float)$commande['total'], 0, ',', ' ');

        // Générer le PDF en mémoire
        require_once __DIR__ . '/FacturePDFService.php';
        require_once __DIR__ . '/../../vendor/autoload.php';

        $pdfService = new FacturePDFService();
        $pdfContent = $pdfService->genererEnMemoire($commande, $lignes);

        $subject = 'Votre facture ' . $ref . ' — Darou Salam';

        $content = $this->header() . '
      <tr><td style="background:#fff;padding:44px 40px 32px;text-align:center;">
        <div style="width:64px;height:64px;background:#dcfce7;border-radius:50%;margin:0 auto 18px;line-height:64px;text-align:center;font-size:1.5rem;border:2px solid #bbf7d0;">✓</div>
        <div style="font-size:1.2rem;font-weight:900;color:#0f2d16;margin-bottom:10px;font-family:Georgia,serif;">
          Votre paiement a été confirmé !
        </div>
        <div style="font-size:.9rem;color:#6b7280;line-height:1.8;margin-bottom:22px;">
          Bonjour <strong>' . htmlspecialchars($clientNom) . '</strong>,<br>
          Nous confirmons la réception de votre paiement pour la commande<br>
          <strong>' . $ref . '</strong> d\'un montant de <strong>' . $total . ' FCFA</strong>.<br><br>
          Votre facture est disponible en pièce jointe de cet email.
        </div>
        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:18px 24px;display:inline-block;margin-bottom:24px;">
          <div style="font-size:.75rem;font-weight:700;color:#15803d;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Montant payé</div>
          <div style="font-size:1.6rem;font-weight:900;color:#1a5c2a;">' . $total . ' FCFA</div>
          <div style="font-size:.75rem;color:#6b7280;margin-top:2px;">Réf : ' . $ref . '</div>
        </div>
      </td></tr>
      ' . $this->footer();

        // Envoyer avec pièce jointe PDF via PHPMailer directement
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = MAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = MAIL_USERNAME;
            $mail->Password   = MAIL_PASSWORD;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = MAIL_PORT;
            $mail->CharSet    = 'UTF-8';
            $mail->Timeout    = 10;
            $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
            $mail->addAddress($clientEmail, $clientNom);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $this->wrapper($content);
            $mail->AltBody = 'Votre paiement a été confirmé. Votre facture ' . $ref . ' est disponible en pièce jointe.';
            $mail->addStringAttachment($pdfContent, 'Facture-' . $commande['reference'] . '.pdf', 'base64', 'application/pdf');
            $logoPath = $this->logoPath();
            if (file_exists($logoPath)) {
                $mail->addEmbeddedImage($logoPath, 'logo_darousalam', 'logo.jpg', 'base64', 'image/jpeg');
            }
            $mail->send();
            return true;
        } catch (\Exception $e) {
            error_log('EmailService facture error: ' . $e->getMessage());
            return false;
        }
    }
}
