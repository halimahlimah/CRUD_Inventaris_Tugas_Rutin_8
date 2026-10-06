<?php
require_once __DIR__ . '/../config/bootstrap.php';
$pageTitle = 'Edit Produk';
$id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);

if (!$id) { flash('error','ID produk tidak valid.'); redirect('index.php'); }

$stmt = $pdo->prepare("SELECT * FROM produk WHERE id_produk=:id");
$stmt->execute(['id'=>$id]);
$product = $stmt->fetch();

if (!$product) { flash('error','Produk tidak ditemukan.'); redirect('index.php'); }

$categories = $pdo->query("SELECT id_kategori,nama_kategori FROM kategori ORDER BY nama_kategori")->fetchAll();
$suppliers = $pdo->query("SELECT id_supplier,nama_supplier FROM supplier ORDER BY nama_supplier")->fetchAll();
$errors = [];

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $idKategori=(int)($_POST['id_kategori']??0);
    $idSupplier=(int)($_POST['id_supplier']??0);
    $kode=trim($_POST['kode_produk']??'');
    $nama=trim($_POST['nama_produk']??'');
    $jumlah=filter_var($_POST['jumlah']??null,FILTER_VALIDATE_INT);
    $harga=filter_var($_POST['harga']??null,FILTER_VALIDATE_FLOAT);

    if ($idKategori<=0) $errors[]='Kategori wajib dipilih.';
    if ($idSupplier<=0) $errors[]='Supplier wajib dipilih.';
    if ($kode==='') $errors[]='Kode produk wajib diisi.';
    if ($nama==='') $errors[]='Nama produk wajib diisi.';
    if ($jumlah===false || $jumlah<0) $errors[]='Jumlah harus 0 atau lebih.';
    if ($harga===false || $harga<0) $errors[]='Harga harus 0 atau lebih.';

    if (!$errors) {
        try {
            $stmt=$pdo->prepare("
                UPDATE produk SET id_kategori=:id_kategori,id_supplier=:id_supplier,
                kode_produk=:kode,nama_produk=:nama,jumlah=:jumlah,harga=:harga
                WHERE id_produk=:id
            ");
            $stmt->execute([
                'id_kategori'=>$idKategori,'id_supplier'=>$idSupplier,'kode'=>$kode,
                'nama'=>$nama,'jumlah'=>$jumlah,'harga'=>$harga,'id'=>$id
            ]);
            flash('success','Produk berhasil diperbarui.');
            redirect('index.php');
        } catch (PDOException $e) {
            $errors[]='Gagal memperbarui. Pastikan kode produk belum digunakan.';
        }
    }

    $product=array_merge($product,[
        'id_kategori'=>$idKategori,'id_supplier'=>$idSupplier,'kode_produk'=>$kode,
        'nama_produk'=>$nama,'jumlah'=>$jumlah,'harga'=>$harga
    ]);
}

require __DIR__ . '/../partials/header.php';
?>
<section class="card form-card">
    <p class="eyebrow">Update</p><h1>Edit Produk</h1>
    <?php if ($errors): ?>
        <div class="alert alert--error"><ul>
        <?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?>
        </ul></div>
    <?php endif; ?>

    <form method="post">
        <div class="form-grid">
            <div class="form-group"><label for="kode_produk">Kode Produk</label>
                <input id="kode_produk" name="kode_produk" value="<?= e($product['kode_produk']) ?>" required></div>
            <div class="form-group"><label for="nama_produk">Nama Produk</label>
                <input id="nama_produk" name="nama_produk" value="<?= e($product['nama_produk']) ?>" required></div>
            <div class="form-group"><label for="id_kategori">Kategori</label>
                <select id="id_kategori" name="id_kategori" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= (int)$category['id_kategori'] ?>"
                        <?= ((int)$product['id_kategori']===(int)$category['id_kategori'])?'selected':'' ?>>
                        <?= e($category['nama_kategori']) ?>
                    </option>
                <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group"><label for="id_supplier">Supplier</label>
                <select id="id_supplier" name="id_supplier" required>
                <?php foreach ($suppliers as $supplier): ?>
                    <option value="<?= (int)$supplier['id_supplier'] ?>"
                        <?= ((int)$product['id_supplier']===(int)$supplier['id_supplier'])?'selected':'' ?>>
                        <?= e($supplier['nama_supplier']) ?>
                    </option>
                <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group"><label for="jumlah">Jumlah</label>
                <input id="jumlah" type="number" name="jumlah" min="0" value="<?= e((string)$product['jumlah']) ?>" required></div>
            <div class="form-group"><label for="harga">Harga</label>
                <input id="harga" type="number" name="harga" min="0" step="0.01" value="<?= e((string)$product['harga']) ?>" required></div>
        </div>
        <div class="form-actions">
            <a class="button button--light" href="index.php">Batal</a>
            <button class="button button--primary" type="submit">Update</button>
        </div>
    </form>
</section>
<?php require __DIR__ . '/../partials/footer.php'; ?>
