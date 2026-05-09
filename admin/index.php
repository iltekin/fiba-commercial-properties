<?php
if (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) === '/admin') {
    header('Location: /admin/');
    exit;
}
$dataFile = '../data.json';
$projects = [];
if (file_exists($dataFile)) {
    $projects = json_decode(file_get_contents($dataFile), true);
    usort($projects, function($a, $b) {
        return ($a['sira'] ?? 0) <=> ($b['sira'] ?? 0);
    });
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Fiba Commercial - Yönetim Paneli</title>
    <link rel="stylesheet" href="../assets/bootstrap-4.6.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/fontawesome-6.1.1/css/all.min.css">
    <script src="../assets/jquery-3.6.0/jquery-3.6.0.min.js"></script>
    <script src="../assets/bootstrap-4.6.1/js/bootstrap.bundle.min.js"></script>
    <!-- SortableJS for drag and drop -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <style>
        body { background: #f4f6f9; }
        .card { box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2); margin-top: 30px;}
        .handle { cursor: grab; color: #999; }
        .project-row.hidden-project { opacity: 0.6; background: #eee; }
    </style>
</head>
<body>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mt-4">
        <h2>Projeler</h2>
        <div>
            <button id="regenerateSite" class="btn btn-warning mr-2"><i class="fa fa-sync"></i> Siteyi Yenile</button>
            <a href="form.php" class="btn btn-primary"><i class="fa fa-plus"></i> Yeni Proje Ekle</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 40px;"></th>
                        <th>ID</th>
                        <th>Başlık</th>
                        <th>Kategori</th>
                        <th>Ülke</th>
                        <th>Durum</th>
                        <th class="text-right">İşlemler</th>
                    </tr>
                </thead>
                <tbody id="sortableList">
                    <?php foreach($projects as $p): ?>
                        <tr class="project-row <?= ($p['gizli'] ?? false) ? 'hidden-project' : '' ?>" data-id="<?= $p['id'] ?>">
                            <td class="align-middle"><i class="fa fa-bars handle"></i></td>
                            <td class="align-middle"><?= $p['id'] ?></td>
                            <td class="align-middle"><strong><?= htmlspecialchars($p['title']) ?></strong>
                                <?php if($p['gelecek']): ?> <span class="badge badge-info">Gelecek</span> <?php endif; ?>
                            </td>
                            <td class="align-middle"><span class="badge badge-secondary"><?= strtoupper($p['kategori']) ?></span></td>
                            <td class="align-middle"><?= ucfirst($p['ulke']) ?></td>
                            <td class="align-middle">
                                <button class="btn btn-sm toggle-hidden btn-<?= ($p['gizli'] ?? false) ? 'outline-secondary' : 'success' ?>">
                                    <?= ($p['gizli'] ?? false) ? 'Gizli' : 'Aktif' ?>
                                </button>
                            </td>
                            <td class="align-middle text-right">
                                <a href="form.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                <button class="btn btn-sm btn-danger delete-btn"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Sortable
    var el = document.getElementById('sortableList');
    var sortable = Sortable.create(el, {
        handle: '.handle',
        animation: 150,
        onEnd: function () {
            let order = [];
            $('#sortableList tr').each(function(index) {
                order.push({
                    id: $(this).data('id'),
                    sira: index + 1
                });
            });
            $.post('action.php', { action: 'reorder', order: order }, function(res) {
                if(!res.success) alert('Sıralama güncellenemedi.');
            }, 'json');
        }
    });

    // Toggle Hidden
    $('.toggle-hidden').click(function() {
        let btn = $(this);
        let tr = btn.closest('tr');
        let id = tr.data('id');
        let isHidden = tr.hasClass('hidden-project') ? 0 : 1;

        $.post('action.php', { action: 'toggle_hidden', id: id, hidden: isHidden }, function(res) {
            if(res.success) {
                if(isHidden) {
                    tr.addClass('hidden-project');
                    btn.removeClass('btn-success').addClass('btn-outline-secondary').text('Gizli');
                } else {
                    tr.removeClass('hidden-project');
                    btn.removeClass('btn-outline-secondary').addClass('btn-success').text('Aktif');
                }
            }
        }, 'json');
    });

    // Delete
    $('.delete-btn').click(function() {
        if(confirm('Bu projeyi tamamen silmek istediğinize emin misiniz? (Klasörü ve içindeki resimler de silinecektir)')) {
            let tr = $(this).closest('tr');
            let id = tr.data('id');
            $.post('action.php', { action: 'delete', id: id }, function(res) {
                if(res.success) {
                    tr.remove();
                } else {
                    alert('Silinirken bir hata oluştu.');
                }
            }, 'json');
        }
    });

    // Regenerate Site
    $('#regenerateSite').click(function() {
        let btn = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Yenileniyor...');
        $.post('action.php', { action: 'regenerate' }, function(res) {
            btn.prop('disabled', false).html('<i class="fa fa-sync"></i> Siteyi Yenile');
            if(res.success) {
                alert('Site başarıyla yeniden oluşturuldu!');
            } else {
                alert('Bir hata oluştu!');
            }
        }, 'json').fail(function() {
            btn.prop('disabled', false).html('<i class="fa fa-sync"></i> Siteyi Yenile');
            alert('Sunucu hatası.');
        });
    });
});
</script>

</body>
</html>
