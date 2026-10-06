<?php
require_once __DIR__ . '/../config/bootstrap.php';

$pageTitle = 'Daftar Produk';
$search = trim($_GET['search'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 5;
$offset = ($page - 1) * $perPage;

$where = '';
$params = [];

if ($search !== '') {
    $where = "WHERE p.kode_produk LIKE :search OR p.nama_produk LIKE :search
              OR k.nama_kategori LIKE :search OR s.nama_supplier LIKE :search";
    $params['search'] = "%{$search}%";
}

$countStmt = $pdo->prepare("
    SELECT COUNT(*) FROM produk p
    INNER JOIN kategori k ON p.id_kategori = k.id_kategori
    INNER JOIN supplier s ON p.id_supplier = s.id_supplier
    $where
");
$countStmt->execute($params);
$totalData = (int)$countStmt->fetchColumn();
$totalPages = max(1, (int)ceil($totalData / $perPage));

if ($page > $totalPages) {
    $page = $totalPages;
    $offset = ($page - 1) * $perPage;
}

$stmt = $pdo->prepare("
    SELECT p.id_produk,p.kode_produk,p.nama_produk,p.jumlah,p.harga,
           k.nama_kategori,s.nama_supplier
    FROM produk p
    INNER JOIN kategori k ON p.id_kategori = k.id_kategori
    INNER JOIN supplier s ON p.id_supplier = s.id_supplier
    $where
    ORDER BY p.id_produk DESC
    LIMIT :limit OFFSET :offset
");
foreach ($params as $key => $value) {
    $stmt->bindValue(":$key", $value, PDO::PARAM_STR);
}
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll();

require __DIR__ . '/../partials/header.php';
?>
<section class="hero">
    <div>
        <p class="eyebrow">Tugas Rutin 8</p>
        <h1>Inventaris Produk</h1>
        <p>Kelola data produk, kategori, dan supplier dengan CRUD PHP PDO.</p>
    </div>
    <a class="button button--primary" href="tambah.php">+ Tambah Produk</a>
</section>

<section class="card">
    <div class="section-head">
        <div>
            <h2>Daftar Produk</h2>
            <p>Total data: <?= $totalData ?></p>
        </div>
        <form class="search" method="get">
            <input type="search" name="search" placeholder="Cari produk..." value="<?= e($search) ?>">
            <button class="button" type="submit">Cari</button>
            <?php if ($search !== ''): ?>
                <a class="button button--light" href="index.php">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No</th><th>Kode</th><th>Produk</th><th>Kategori</th>
                    <th>Supplier</th><th>Jumlah</th><th>Harga</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!$products): ?>
                <tr><td colspan="8" class="empty">Data produk tidak ditemukan.</td></tr>
            <?php else: ?>
                <?php foreach ($products as $index => $product): ?>
                    <tr>
                        <td><?= $offset + $index + 1 ?></td>
                        <td><?= e($product['kode_produk']) ?></td>
                        <td><?= e($product['nama_produk']) ?></td>
                        <td><?= e($product['nama_kategori']) ?></td>
                        <td><?= e($product['nama_supplier']) ?></td>
                        <td><?= e((string)$product['jumlah']) ?></td>
                        <td>Rp <?= number_format((float)$product['harga'],0,',','.') ?></td>
                        <td class="actions">
                            <a class="button button--small" href="edit.php?id=<?= (int)$product['id_produk'] ?>">Edit</a>
                            <a class="button button--small button--danger"
                               href="hapus.php?id=<?= (int)$product['id_produk'] ?>"
                               onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php for ($i=1; $i<=$totalPages; $i++): ?>
                <a class="<?= $i === $page ? 'active' : '' ?>"
                   href="?search=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/../partials/footer.php'; ?>
