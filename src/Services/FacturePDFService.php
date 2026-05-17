<?php

require_once __DIR__ . '/../../vendor/autoload.php';

class FacturePDFService
{
    private function mpdfConfig(): array
    {
        return [
            'margin_top'    => 14,
            'margin_bottom' => 14,
            'margin_left'   => 14,
            'margin_right'  => 14,
            'format'        => 'A4',
            'default_font'  => 'dejavusans',
            'tempDir'       => __DIR__ . '/../../tmp/mpdf',
        ];
    }

    public function generer(array $commande, array $lignes): void
    {
        $html = $this->buildHtml($commande, $lignes);
        $mpdf = new \Mpdf\Mpdf($this->mpdfConfig());
        $mpdf->SetTitle('Facture ' . $commande['reference']);
        $mpdf->SetAuthor('Darou Salam Business Company');
        $mpdf->WriteHTML($html);
        $mpdf->Output('Facture-' . $commande['reference'] . '.pdf', 'D');
        exit;
    }

    public function genererEnMemoire(array $commande, array $lignes): string
    {
        ob_start();
        // On capture via Output('S') — retourne string
        ob_end_clean();

        $html = $this->buildHtml($commande, $lignes);

        $mpdf = new \Mpdf\Mpdf($this->mpdfConfig());
        $mpdf->SetTitle('Facture ' . $commande['reference']);
        $mpdf->SetAuthor('Darou Salam Business Company');
        $mpdf->WriteHTML($html);
        return $mpdf->Output('', 'S');
    }

    private function buildHtml(array $commande, array $lignes): string
    {
        $ref     = htmlspecialchars($commande['reference']);
        $date    = date('d/m/Y', strtotime($commande['created_at']));
        $dateGen = date('d/m/Y');
        $client  = trim(($commande['client_prenom'] ?? '') . ' ' . ($commande['client_nom'] ?? ''));
        if (!$client) $client = 'Client #' . $commande['client_id'];
        $email   = htmlspecialchars($commande['client_email'] ?? '');
        $tel     = htmlspecialchars($commande['client_tel'] ?? '');

        $adresse  = json_decode($commande['adresse_livraison'] ?? '{}', true) ?: [];
        $adrLigne = htmlspecialchars($adresse['adresse'] ?? '');
        $adrVille = htmlspecialchars($adresse['ville'] ?? '');
        $adrTel   = htmlspecialchars($adresse['telephone'] ?? '');

        $modePaie = match ($commande['mode_paiement'] ?? '') {
            'wave'         => 'Wave',
            'orange_money' => 'Orange Money',
            default        => 'Paiement à la livraison',
        };

        $sousTotal = (float)($commande['sous_total'] ?? 0);
        $remise    = (float)($commande['remise'] ?? 0);
        $frais     = (float)($commande['frais_livraison'] ?? 0);
        $total     = (float)($commande['total'] ?? 0);
        $codePromo = htmlspecialchars($commande['code_promo'] ?? '');

        // Logo en base64 (version réduite)
        $logoPath = __DIR__ . '/../../logo_small.jpg';
        if (!file_exists($logoPath)) $logoPath = __DIR__ . '/../../logo.jpg';
        $logoHtml = file_exists($logoPath)
            ? '<img src="data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath)) . '" width="60" height="60" />'
            : '';

        // Lignes articles
        $lignesHtml = '';
        foreach ($lignes as $i => $l) {
            $isCarton = ($l['unite'] ?? 'kg') === 'carton';
            $unite    = $isCarton ? 'carton' . ($l['quantite'] > 1 ? 's' : '') : 'kg';
            $qte      = fmod((float)$l['quantite'], 1) == 0
                        ? number_format($l['quantite'], 0, ',', ' ')
                        : number_format($l['quantite'], 2, ',', ' ');
            $bg = $i % 2 === 0 ? '#ffffff' : '#f9fafb';

            // Photo du produit
            $imgHtml = '';
            if (!empty($l['image_principale'])) {
                $imgPath = __DIR__ . '/../../' . $l['image_principale'];
                if (file_exists($imgPath)) {
                    $ext     = strtolower(pathinfo($imgPath, PATHINFO_EXTENSION));
                    $mime    = $ext === 'png' ? 'image/png' : ($ext === 'webp' ? 'image/webp' : 'image/jpeg');
                    $imgData = base64_encode(file_get_contents($imgPath));
                    $imgHtml = '<img src="data:' . $mime . ';base64,' . $imgData . '" width="48" height="48" style="border-radius:6px;object-fit:cover;vertical-align:middle;margin-right:10px;" />';
                }
            }

            $lignesHtml .= '
            <tr style="background:' . $bg . ';">
              <td style="padding:10px 12px;font-size:9pt;color:#1c1917;border-bottom:1px solid #eee;">
                <table style="border-collapse:collapse;width:100%;">
                  <tr>
                    <td style="width:58px;vertical-align:middle;">' . $imgHtml . '</td>
                    <td style="vertical-align:middle;">
                      <span style="font-weight:bold;">' . htmlspecialchars($l['nom_produit']) . '</span>'
                      . ($isCarton ? '<br><span style="font-size:7pt;color:#0284c7;">Carton</span>' : '') .
                    '</td>
                  </tr>
                </table>
              </td>
              <td style="padding:10px 12px;font-size:9pt;color:#374151;text-align:center;border-bottom:1px solid #eee;vertical-align:middle;">'
                . $qte . '</td>
              <td style="padding:10px 12px;font-size:9pt;color:#374151;text-align:right;border-bottom:1px solid #eee;vertical-align:middle;">'
                . number_format($l['prix_unitaire'], 0, ',', ' ') . ' FCFA</td>
              <td style="padding:10px 12px;font-size:9pt;font-weight:bold;color:#1c1917;text-align:right;border-bottom:1px solid #eee;vertical-align:middle;">'
                . number_format($l['total_ligne'], 0, ',', ' ') . ' FCFA</td>
            </tr>';
        }

        // Lignes totaux
        $totauxHtml = '';
        if ($sousTotal > 0 && $remise > 0) {
            $totauxHtml .= '<tr><td style="padding:7px 14px;font-size:8.5pt;color:#6b7280;border-bottom:1px solid #eee;">Sous-total</td><td style="padding:7px 14px;font-size:8.5pt;color:#6b7280;text-align:right;border-bottom:1px solid #eee;">' . number_format($sousTotal, 0, ',', ' ') . ' FCFA</td></tr>';
            $totauxHtml .= '<tr><td style="padding:7px 14px;font-size:8.5pt;color:#16a34a;border-bottom:1px solid #eee;">Remise' . ($codePromo ? ' (' . $codePromo . ')' : '') . '</td><td style="padding:7px 14px;font-size:8.5pt;font-weight:bold;color:#16a34a;text-align:right;border-bottom:1px solid #eee;">-' . number_format($remise, 0, ',', ' ') . ' FCFA</td></tr>';
        }
        if ($frais > 0) {
            $totauxHtml .= '<tr><td style="padding:7px 14px;font-size:8.5pt;color:#6b7280;border-bottom:1px solid #eee;">Frais de livraison</td><td style="padding:7px 14px;font-size:8.5pt;color:#6b7280;text-align:right;border-bottom:1px solid #eee;">' . number_format($frais, 0, ',', ' ') . ' FCFA</td></tr>';
        } else {
            $totauxHtml .= '<tr><td style="padding:7px 14px;font-size:8.5pt;color:#6b7280;border-bottom:1px solid #eee;">Frais de livraison</td><td style="padding:7px 14px;font-size:8.5pt;color:#16a34a;text-align:right;border-bottom:1px solid #eee;">Offerts</td></tr>';
        }

        // Infos client destinataire
        $destHtml = '<div style="font-size:9pt;font-weight:bold;color:#1c1917;margin-bottom:4px;">' . htmlspecialchars($client) . '</div>';
        if ($email)    $destHtml .= '<div style="font-size:8pt;color:#6b7280;margin-bottom:2px;">' . $email . '</div>';
        if ($tel)      $destHtml .= '<div style="font-size:8pt;color:#6b7280;margin-bottom:2px;">' . $tel . '</div>';
        if ($adrLigne) $destHtml .= '<div style="font-size:8pt;color:#6b7280;">' . $adrLigne . ($adrVille ? ', ' . $adrVille : '') . '</div>';
        if ($adrTel && $adrTel !== $tel) $destHtml .= '<div style="font-size:8pt;color:#6b7280;">Tél livraison : ' . $adrTel . '</div>';

        // Construire infos client sous forme de texte
        $destLines = '<b>' . htmlspecialchars($client) . '</b>';
        if ($email)    $destLines .= '<br>' . $email;
        if ($tel)      $destLines .= '<br>' . $tel;
        if ($adrLigne) $destLines .= '<br>' . $adrLigne . ($adrVille ? ', ' . $adrVille : '');
        if ($adrTel && $adrTel !== $tel) $destLines .= '<br>Tél livraison : ' . $adrTel;

        return '<!DOCTYPE html>
<html>
<head><meta charset="UTF-8">
<style>
body { font-family:"DejaVu Sans",sans-serif; font-size:9pt; color:#1c1917; background:#fff; }
table { border-collapse:collapse; width:100%; }
th, td { padding:0; }
</style>
</head>
<body>

<!-- EN-TÊTE -->
<table style="margin-bottom:16px;">
<tr>
  <td width="50%" style="vertical-align:top;">
    ' . $logoHtml . '
    <br>
    <span style="font-size:12pt;font-weight:bold;color:#1a5c2a;">DAROU SALAM</span><br>
    <span style="font-size:7.5pt;color:#6b7280;">Dakar, Sénégal</span><br>
    <span style="font-size:7.5pt;color:#6b7280;">Tél : +221 77 XXX XX XX</span><br>
    <span style="font-size:7.5pt;color:#6b7280;">contact@darousalam.sn</span>
  </td>
  <td width="50%" style="vertical-align:top;text-align:right;">
    <span style="font-size:7pt;font-weight:bold;color:#1a5c2a;letter-spacing:2px;">NUMÉRO DE FACTURE</span><br>
    <span style="font-size:20pt;font-weight:bold;color:#1a5c2a;">' . $ref . '</span><br>
    <table style="width:auto;margin-left:auto;margin-right:0;margin-top:2px;margin-bottom:4px;border-collapse:collapse;">
      <tr><td style="background:#1a5c2a;height:2px;width:70px;font-size:0;">&nbsp;</td></tr>
    </table>
    <span style="font-size:8pt;color:#374151;">Émis le <b>' . $dateGen . '</b></span><br>
    <span style="font-size:8pt;color:#374151;">Commande du <b>' . $date . '</b></span><br>
    <table style="width:auto;margin-left:auto;margin-right:0;margin-top:6px;border-collapse:collapse;">
      <tr><td style="background:#1a5c2a;color:#fff;font-size:8pt;font-weight:bold;padding:4px 16px;letter-spacing:1px;">PAYÉ</td></tr>
    </table>
  </td>
</tr>
</table>

<!-- SÉPARATEUR -->
<table style="margin-bottom:16px;"><tr><td style="background:#e5e7eb;height:1px;font-size:0;">&nbsp;</td></tr></table>

<!-- TITRE DESTINATAIRE -->
<table style="margin-bottom:2px;"><tr>
  <td style="font-size:7pt;font-weight:bold;color:#1a5c2a;letter-spacing:1.5px;border-bottom:2px solid #1a5c2a;padding-bottom:3px;">DESTINATAIRE</td>
</tr></table>
<table style="margin-bottom:18px;margin-top:10px;">
<tr>
  <td width="50%" style="vertical-align:top;">
    <span style="font-size:7pt;color:#9ca3af;letter-spacing:1px;">CLIENT</span><br>
    <span style="font-size:9pt;">' . $destLines . '</span>
  </td>
  <td width="50%" style="vertical-align:top;text-align:right;">
    <span style="font-size:7pt;color:#9ca3af;letter-spacing:1px;">MODE DE PAIEMENT</span><br>
    <span style="font-size:9pt;color:#374151;">' . $modePaie . '</span><br><br>
    <span style="font-size:7pt;color:#9ca3af;letter-spacing:1px;">RÉFÉRENCE COMMANDE</span><br>
    <span style="font-size:9pt;color:#374151;">' . $ref . '</span>
  </td>
</tr>
</table>

<!-- TITRE ARTICLES -->
<table style="margin-bottom:2px;"><tr>
  <td style="font-size:7pt;font-weight:bold;color:#1a5c2a;letter-spacing:1.5px;border-bottom:2px solid #1a5c2a;padding-bottom:3px;">DÉTAIL DES ARTICLES</td>
</tr></table>
<table style="margin-bottom:20px;margin-top:0;border-collapse:collapse;">
  <thead>
    <tr style="background:#1a5c2a;">
      <th style="padding:9px 12px;font-size:8pt;color:#fff;text-align:left;width:44%;">DESCRIPTION</th>
      <th style="padding:9px 12px;font-size:8pt;color:#fff;text-align:center;width:12%;">QTÉ</th>
      <th style="padding:9px 12px;font-size:8pt;color:#fff;text-align:right;width:22%;">PRIX UNITAIRE</th>
      <th style="padding:9px 12px;font-size:8pt;color:#fff;text-align:right;width:22%;">TOTAL HT</th>
    </tr>
  </thead>
  <tbody>' . $lignesHtml . '</tbody>
</table>

<!-- TOTAUX -->
<table style="margin-bottom:22px;">
<tr>
  <td width="48%">&nbsp;</td>
  <td width="52%">
    <table style="border:1px solid #e5e7eb;border-collapse:collapse;">
      ' . $totauxHtml . '
      <tr style="background:#1a5c2a;">
        <td style="padding:11px 14px;font-size:10pt;font-weight:bold;color:#fff;">TOTAL TTC</td>
        <td style="padding:11px 14px;font-size:10pt;font-weight:bold;color:#fff;text-align:right;">' . number_format($total, 0, ',', ' ') . ' FCFA</td>
      </tr>
    </table>
  </td>
</tr>
</table>

<!-- CONDITIONS -->
<table style="margin-bottom:2px;"><tr>
  <td style="font-size:7pt;font-weight:bold;color:#1a5c2a;letter-spacing:1.5px;border-bottom:2px solid #1a5c2a;padding-bottom:3px;">CONDITIONS</td>
</tr></table>
<table style="margin-bottom:26px;margin-top:10px;">
<tr>
  <td style="border-left:3px solid #1a5c2a;padding:7px 12px;font-size:8.5pt;color:#374151;">
    Paiement à la livraison. Merci pour votre confiance en Darou Salam Business Company.
  </td>
</tr>
</table>

<!-- CACHET & SIGNATURE -->
<table style="margin-bottom:20px;">
<tr>
  <td width="50%">&nbsp;</td>
  <td width="50%">
    <table style="border:1px solid #e5e7eb;border-collapse:collapse;width:100%;">
      <tr>
        <td style="padding:8px 14px;text-align:center;border-bottom:1px solid #e5e7eb;">
          <span style="font-size:7pt;font-weight:bold;color:#6b7280;letter-spacing:1.5px;">CACHET &amp; SIGNATURE</span>
        </td>
      </tr>
      <tr>
        <td style="padding:14px;text-align:center;">
          <table style="border:1.5px solid #1a5c2a;border-collapse:collapse;margin:0 auto;">
            <tr>
              <td style="padding:10px 22px;text-align:center;">
                <span style="font-size:10pt;font-weight:bold;color:#1a5c2a;">DAROU SALAM</span><br>
                <span style="font-size:7pt;color:#6b7280;">Business Company</span><br>
                <span style="font-size:7pt;color:#6b7280;">contact@darousalam.sn</span><br>
                <span style="font-size:7pt;color:#6b7280;">Dakar, Sénégal</span>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </td>
</tr>
</table>

<!-- PIED DE PAGE -->
<table><tr><td style="background:#e5e7eb;height:1px;font-size:0;">&nbsp;</td></tr></table>
<table style="margin-top:8px;">
<tr>
  <td style="font-size:7pt;color:#9ca3af;text-align:center;">
    Darou Salam Business Company &middot; Dakar, Sénégal &middot; contact@darousalam.sn<br>
    Facture générée le ' . $dateGen . ' &middot; ' . $ref . '
  </td>
</tr>
</table>

</body>
</html>';
    }
}
