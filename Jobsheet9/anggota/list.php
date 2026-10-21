<?php
$page_title = "Daftar Anggota";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$perPage = 5;
$page = max(1, (int) ($_GET['page'] ?? 1));
$keyword = trim($_GET['q'] ?? '');

if ($keyword !== '') {
    $searchTerm = '%' . $keyword . '%';
    $countStmt = $pdo->prepare(
        "SELECT COUNT(*) FROM anggota
         WHERE nama ILIKE :nama_kw OR no_anggota ILIKE :no_anggota_kw"
    );
    $countStmt->execute([
        'nama_kw' => $searchTerm,
        'no_anggota_kw' => $searchTerm,
    ]);
    $totalRows = $countStmt->fetchColumn();

    $stmt = $pdo->prepare(
        "SELECT * FROM anggota
         WHERE nama ILIKE :nama_kw OR no_anggota ILIKE :no_anggota_kw
         ORDER BY id DESC LIMIT :limit OFFSET :offset"
    );
    $stmt->bindValue('nama_kw', $searchTerm);
    $stmt->bindValue('no_anggota_kw', $searchTerm);
} else {
    $totalRows = $pdo->query("SELECT COUNT(*) FROM anggota")->fetchColumn();
    $stmt = $pdo->prepare("SELECT * FROM anggota ORDER BY id DESC LIMIT :limit OFFSET :offset");
}

$totalPages = max(1, (int) ceil($totalRows / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;
$stmt->bindValue('limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue('offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$daftarAnggota = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
        <section>
            <h2>Daftar Anggota</h2>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo htmlspecialchars($flash['type'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($flash['pesan'], ENT_QUOTES, 'UTF-8'); ?>
                </p>
            <?php endif; ?>

            <div class="search-box">
                <form method="get" action="list.php">
                    <label for="search-input">Cari Nama atau No. Anggota</label><br>
                    <input type="text" id="search-input" name="q"
                           value="<?php echo htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8'); ?>"
                           placeholder="Ketik nama atau nomor anggota...">
                    <button type="submit">Cari</button>
                </form>
            </div>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>No. Anggota</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>No. HP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($daftarAnggota)): ?>
                            <tr><td colspan="5">Tidak ada data anggota yang cocok.</td></tr>
                        <?php else: ?>
                            <?php foreach ($daftarAnggota as $anggota): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($anggota['no_anggota'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($anggota['nama'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($anggota['alamat'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars($anggota['no_hp'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>
                                        <a href="edit.php?id=<?php echo urlencode((string) $anggota['id']); ?>" class="btn-edit">Edit</a>
                                        <form class="form-hapus" method="post" action="hapus.php"
                                              data-entity="anggota"
                                              data-confirm-name="<?php echo htmlspecialchars($anggota['nama'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <input type="hidden" name="id" value="<?php echo htmlspecialchars((string) $anggota['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                            <button type="submit" class="btn-hapus">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <nav class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php
                    $paginationParams = ['page' => $i];
                    if ($keyword !== '') {
                        $paginationParams['q'] = $keyword;
                    }
                    ?>
                    <a href="list.php?<?php echo htmlspecialchars(http_build_query($paginationParams), ENT_QUOTES, 'UTF-8'); ?>"
                       class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
            </nav>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
