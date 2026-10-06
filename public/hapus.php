<?php
require_once __DIR__ . '/../config/bootstrap.php';

$id = filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);

if (!$id) { flash('error','ID produk tidak valid.'); redirect('index.php'); }

try {
    $pdo->beginTransaction();

    $check=$pdo->prepare("SELECT id_produk FROM produk WHERE id_produk=:id FOR UPDATE");
    $check->execute(['id'=>$id]);

    if (!$check->fetch()) {
        $pdo->rollBack();
        flash('error','Produk tidak ditemukan.');
        redirect('index.php');
    }

    $stmt=$pdo->prepare("DELETE FROM produk WHERE id_produk=:id");
    $stmt->execute(['id'=>$id]);

    $pdo->commit();
    flash('success','Produk berhasil dihapus.');
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    flash('error','Produk gagal dihapus.');
}

redirect('index.php');
