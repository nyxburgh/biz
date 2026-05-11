<?php
class CityModel extends Model
{
    protected string $table = 'cities';

    public function getAllWithStats(array $f = [], int $page = 1, int $per = 20): array
    {
        $w = []; $p = [];
        if (!empty($f['search'])) { $w[] = "c.name LIKE ?"; $p[] = "%{$f['search']}%"; }
        if (!empty($f['status'])) { $w[] = "c.status = ?";  $p[] = $f['status']; }

        $where = $w ? 'WHERE ' . implode(' AND ', $w) : '';
        $sql = "SELECT c.*,
                       COUNT(DISTINCT u.id)  AS user_count,
                       COUNT(DISTINCT bl.id) AS listing_count
                FROM cities c
                LEFT JOIN users u   ON u.city_id = c.id
                LEFT JOIN business_listings bl ON bl.city_id = c.id
                $where
                GROUP BY c.id
                ORDER BY c.sort_order, c.name";

        return Database::paginate($sql, $p, $page, $per);
    }

    /**
     * Clone the _template folder into cities/{slug}
     */
    public function cloneTemplate(string $slug): bool
    {
        $src  = BASE_PATH . '/cities/_template';
        $dest = BASE_PATH . '/cities/' . $slug;

        if (!is_dir($src)) {
            return false;
        }
        if (is_dir($dest)) {
            return true; // already exists
        }
        return self::copyDir($src, $dest);
    }

    private static function copyDir(string $src, string $dst): bool
    {
        mkdir($dst, 0755, true);
        $dir = opendir($src);
        while (($file = readdir($dir)) !== false) {
            if ($file === '.' || $file === '..') continue;
            is_dir("$src/$file")
                ? self::copyDir("$src/$file", "$dst/$file")
                : copy("$src/$file", "$dst/$file");
        }
        closedir($dir);
        return true;
    }
}
