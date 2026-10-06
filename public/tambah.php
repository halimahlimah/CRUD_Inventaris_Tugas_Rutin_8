<?php
require_once __DIR__ . '/../config/bootstrap.php';
$pageTitle = 'Tambah Produk';

$categories = $pdo->query("SELECT id_kategori,nama_kategori FROM kategori ORDER BY nama_kategori")->fetchAll();
$suppliers = $pdo->query("SELECT id_supplier,nama_supplier FROM supplier ORDER BY nama_supplier")->fetchAll();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idKategori = (int)($_POST['id_kategori'] ?? 0);
    $idSupplier = (int)($_POST['id_supplier'] ?? 0);
    $kode = trim($_POST['kode_produk'] ?? '');
    $nama = trim($_POST['nama_produk'] ?? '');
    $jumlah = filter_var($_POST['jumlah'] ?? null, FILTER_VALIDATE_INT);
    $harga = filter_var($_POST['harga'] ?? null, FILTER_VALIDATE_FLOAT);

    if ($idKategori <= 0) $errors[] = 'Kategori wajib dipilih.';
    if ($idSupplier <= 0) $errors[] = 'Supplier wajib dipilih.';
    if ($kode === '') $errors[] = 'Kode produk wajib diisi.';
    if ($nama === '') $errors[] = 'Nama produk wajib diisi.';
    if ($jumlah === false || $jumlah < 0) $errors[] = 'Jumlah harus 0 atau lebih.';
    if ($harga === false || $harga < 0) $errors[] = 'Harga harus 0 atau lebih.';

    if (!$errors) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO produk (id_kategori,id_supplier,kode_produk,nama_produk,jumlah,harga)
                VALUES (:id_kategori,:id_supplier,:kode,:nama,:jumlah,:harga)
            ");
            $stmt->execute([
                'id_kategori'=>$idKategori,'id_supplier'=>$idSupplier,
                'kode'=>$kode,'nama'=>$nama,'jumlah'=>$jumlah,'harga'=>$harga
            ]);
            flash('success','Produk berhasil ditambahkan.');
            redirect('index.php');
        } catch (PDOException $e) {
            $errors[] = 'Gagal menyimpan. Pastikan kode produk belum digunakan.';
        }
    }
}

require __DIR__ . '/../partials/header.php';
?>
<section class="card form-card">
    <p class="eyebrow">Create</p><h1>Tambah Produk</h1>
    <?php if ($errors): ?>
        <div class="alert alert--error"><ul>
        <?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?>
        </ul></div>
    <?php endif; ?>

    <form method="post">
        <div class="form-grid">
            <div class="form-group"><label for="kode_produk">Kode Produk</label>
                <input id="kode_produk" name="kode_produk" value="<?= e($_POST['kode_produk'] ?? '') ?>" required></div>
            <div class="form-group"><label for="nama_produk">Nama Produk</label>
                <input id="nama_produk" name="nama_produk" value="<?= e($_POST['nama_produk'] ?? '') ?>" required></div>
            <div class="form-group"><label for="id_kategori">Kategori</label>
                <select id="id_kategori" name="id_kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= (int)$category['id_kategori'] ?>"
                            <?= ((int)($_POST['id_kategori'] ?? 0)===(int)$category['id_kategori'])?'selected':'' ?>>
                            <?= e($category['nama_kategori']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group"><label for="id_supplier">Supplier</label>
                <select id="id_supplier" name="id_supplier" required>
                    <option value="">-- Pilih Supplier --</option>
                    <?php foreach ($suppliers as $supplier): ?>
                        <option value="<?= (int)$supplier['id_supplier'] ?>"
                            <?= ((int)($_POST['id_supplier'] ?? 0)===(int)$supplier['id_supplier'])?'selected':'' ?>>
                            <?= e($supplier['nama_supplier']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group"><label for="jumlah">Jumlah</label>
                <input id="jumlah" type="number" name="jumlah" min="0" value="<?= e($_POST['jumlah'] ?? '0') ?>" required></div>
            <div class="form-group"><label for="harga">Harga</label>
                <input id="harga" type="number" name="harga" min="0" step="0.01" value="<?= e($_POST['harga'] ?? '0') ?>" required></div>
        </div>
        <div class="form-actions">
            <a class="button button--light" href="index.php">Batal</a>
            <button class="button button--primary" type="submit">Simpan</button>
        </div>
    </form>
</section>
<?php require __DIR__ . '/../partials/footer.php'; ?>
