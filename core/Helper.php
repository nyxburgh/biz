<?php
class Helper
{
    public static function slug1(string $text): string
    {
        $text = mb_strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9\s\-]/', '', $text);
        $text = preg_replace('/[\s\-]+/', '-', $text);
        return trim($text, '-');
    }

    public static function slug(string $text): string
    {
        // Trim and remove leading/trailing spaces
        $text = trim($text);

        // Remove all non-alphanumeric characters (including hyphens & spaces)
        $text = preg_replace('/[^a-zA-Z0-9]/', '', $text);

        return $text;
    }

    public static function truncate(string $text, int $length = 80): string
    {
        return mb_strlen($text) > $length
            ? mb_substr($text, 0, $length) . '…'
            : $text;
    }

    public static function timeAgo(string $datetime): string
    {
        $diff = time() - strtotime($datetime);
        return match (true) {
            $diff < 60     => 'just now',
            $diff < 3600   => floor($diff / 60) . 'm ago',
            $diff < 86400  => floor($diff / 3600) . 'h ago',
            $diff < 604800 => floor($diff / 86400) . 'd ago',
            default        => date('d M Y', strtotime($datetime)),
        };
    }

    public static function formatDate(string $date, string $format = 'd M Y'): string
    {
        return date($format, strtotime($date));
    }

    // Flash messages
    public static function flash(string $key, string $message): void
    {
        $_SESSION['flash'][$key] = $message;
    }

    public static function getFlash(string $key): ?string
    {
        $msg = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $msg;
    }

    // File upload — returns saved filename or false
    public static function uploadFile(array $file, string $subDir): string|false
    {
        $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'pdf'];

        if (!in_array($ext, $allowed)) {
            return false;
        }
        if ($file['size'] > UPLOAD_MAX_MB * 1024 * 1024) {
            return false;
        }

        $dir = BASE_PATH . '/assets/uploads/' . trim($subDir, '/');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filename = uniqid('bg_', true) . '.' . $ext;
        return move_uploaded_file($file['tmp_name'], "$dir/$filename") ? $filename : false;
    }

    // Pagination HTML
    public static function paginationLinks(array $pg, string $baseUrl): string
    {
        if ($pg['last_page'] <= 1) {
            return '';
        }
        $sep  = str_contains($baseUrl, '?') ? '&' : '?';
        $cur  = $pg['current_page'];
        $last = $pg['last_page'];

        $html = '<nav aria-label="Pagination"><ul class="pagination mb-0">';
        $html .= '<li class="page-item ' . ($cur == 1 ? 'disabled' : '') . '">
                  <a class="page-link" href="' . $baseUrl . $sep . 'page=' . max(1, $cur - 1) . '">‹ Prev</a></li>';

        for ($i = max(1, $cur - 2); $i <= min($last, $cur + 2); $i++) {
            $html .= '<li class="page-item ' . ($i == $cur ? 'active' : '') . '">
                      <a class="page-link" href="' . $baseUrl . $sep . 'page=' . $i . '">' . $i . '</a></li>';
        }

        $html .= '<li class="page-item ' . ($cur == $last ? 'disabled' : '') . '">
                  <a class="page-link" href="' . $baseUrl . $sep . 'page=' . min($last, $cur + 1) . '">Next ›</a></li>';

        return $html . '</ul></nav>';
    }

    // Badge helpers
    public static function planBadge(string $plan): string
    {
        $colors = [
            'free'    => 'secondary',
            'basic'   => 'info',
            'premium' => 'warning',
            'pro'     => 'success',
        ];
        $c = $colors[$plan] ?? 'secondary';
        return '<span class="badge bg-' . $c . '">' . ucfirst($plan) . '</span>';
    }

    public static function statusBadge(string $status): string
    {
        $colors = [
            'active'    => 'success',  'approved'  => 'success',  'confirmed' => 'success',
            'pending'   => 'warning',  'draft'     => 'secondary',
            'rejected'  => 'danger',   'suspended' => 'danger',
            'banned'    => 'danger',   'inactive'  => 'secondary',
            'converted' => 'info',
        ];
        $c = $colors[$status] ?? 'secondary';
        return '<span class="badge bg-' . $c . '">' . ucfirst($status) . '</span>';
    }

    // Extract embed URL from Google Maps iframe or direct URL
    public static function mapEmbedUrl(string $input): string
    {
        $input = trim($input);
        if (empty($input)) return '';

        // Extract src from iframe first (handles full iframe HTML paste)
        if (preg_match('/<iframe[^>]*src=["\']([^"\']+)["\'][^>]*>/i', $input, $matches)) {
            return $matches[1];
        }

        // If it's already a direct embed URL
        if (str_contains($input, 'google.com/maps/embed')) {
            return $input;
        }

        // If it's a share URL, try to convert to embed
        if (str_contains($input, 'google.com/maps/') && !str_contains($input, '/embed')) {
            $embedUrl = preg_replace('/google\.com\/maps\//', 'google.com/maps/embed/', $input);
            return $embedUrl;
        }

        return $input; // Return as-is if can't parse
    }
}
