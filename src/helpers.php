<?php

/**
 * Formate un prix en FCFA
 */
function formatPrice(float $montant): string
{
    return number_format($montant, 0, ',', ' ') . ' FCFA';
}

/**
 * Redirige vers une URL et stoppe l'exécution
 */
function redirect(string $page, array $params = []): void
{
    $url = BASE_URL . '?page=' . $page;
    foreach ($params as $key => $val) {
        $url .= '&' . urlencode($key) . '=' . urlencode($val);
    }
    header('Location: ' . $url);
    exit;
}

/**
 * Nettoie une chaîne pour éviter les injections XSS
 */
function sanitize(string $value): string
{
    return htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
}

/**
 * Vérifie si l'utilisateur est connecté
 */
function isLoggedIn(): bool
{
    return isset($_SESSION['client_id']) && !empty($_SESSION['client_id']);
}

/**
 * Redirige vers login si non connecté
 */
function requireLogin(): void
{
    if (!isLoggedIn()) {
        redirect('login', ['redirect' => $_GET['page'] ?? 'accueil']);
    }
}

/**
 * Retourne le client connecté depuis la session
 */
function getClientSession(): array
{
    return $_SESSION['client'] ?? [];
}

/**
 * Ajoute un message flash en session
 */
function flashMessage(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Affiche et efface le message flash
 */
function getFlashMessage(): array|null
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Valide un email
 */
function isValidEmail(string $email): bool
{
    return (bool)filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Valide un numéro de téléphone sénégalais (7x, 3x, 9x...)
 */
function isValidPhone(string $phone): bool
{
    $phone = preg_replace('/\s+/', '', $phone);
    return (bool)preg_match('/^(\+221)?[0-9]{9}$/', $phone);
}

/**
 * Génère une pagination HTML
 */
function pagination(int $totalItems, int $perPage, int $currentPage, string $pageParam = 'page', array $extraParams = []): string
{
    $totalPages = (int)ceil($totalItems / $perPage);
    if ($totalPages <= 1) return '';

    $html = '<nav aria-label="Pagination"><ul class="pagination justify-content-center">';

    for ($i = 1; $i <= $totalPages; $i++) {
        $params = array_merge($extraParams, [$pageParam => $i]);
        // On réutilise page pour la navigation de pages (num)
        $url = BASE_URL . '?' . http_build_query(array_merge(['page' => $_GET['page'] ?? 'accueil'], ['p' => $i], $extraParams));
        $active = $i === $currentPage ? ' active' : '';
        $html .= "<li class=\"page-item$active\"><a class=\"page-link\" href=\"$url\">$i</a></li>";
    }

    $html .= '</ul></nav>';
    return $html;
}

/**
 * Retourne une image par défaut si le fichier n'existe pas
 */
function produitImage(string $image): string
{
    $path = BASE_URL . 'public/images/produits/' . $image;
    return $image ? $path : BASE_URL . 'public/images/no-image.png';
}

/**
 * Tronque un texte
 */
function truncate(string $text, int $length = 100): string
{
    if (mb_strlen($text) <= $length) return $text;
    return mb_substr($text, 0, $length) . '...';
}

/**
 * Badge HTML pour le statut d'une commande
 */
function statutBadge(string $statut): string
{
    $classes = [
        'en_attente'     => 'warning',
        'confirmee'      => 'info',
        'en_preparation' => 'primary',
        'expediee'       => 'secondary',
        'livree'         => 'success',
        'annulee'        => 'danger',
    ];
    $labels = [
        'en_attente'     => 'En attente',
        'confirmee'      => 'Confirmée',
        'en_preparation' => 'En préparation',
        'expediee'       => 'Expédiée',
        'livree'         => 'Livrée',
        'annulee'        => 'Annulée',
    ];
    $class = $classes[$statut] ?? 'secondary';
    $label = $labels[$statut] ?? $statut;
    return "<span class=\"badge bg-$class\">$label</span>";
}
