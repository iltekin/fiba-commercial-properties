<?php
$dataFile = '../data.json';
$projects = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$project = null;

if ($id) {
    foreach ($projects as $p) {
        if ($p['id'] == $id) {
            $project = $p;
            break;
        }
    }
}

// Generate new ID if creating
if (!$project) {
    $maxId = 0;
    foreach ($projects as $p) {
        if ($p['id'] > $maxId) $maxId = $p['id'];
    }
    $newId = $maxId + 1;
}

$bilgiler = $project['bilgiler'] ?? [["", ""], ["", ""], ["", ""], ["", ""], ["", ""], ["", ""]];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title><?= $project ? 'Projeyi Düzenle' : 'Yeni Proje Ekle' ?></title>
    <link rel="stylesheet" href="../assets/bootstrap-4.6.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/fontawesome-6.1.1/css/all.min.css">
    <script src="../assets/jquery-3.6.0/jquery-3.6.0.min.js"></script>
    <script src="../assets/bootstrap-4.6.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <style>
        body { background: #f4f6f9; padding-bottom: 50px; }
        .card { box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2); margin-top: 20px;}
        .form-group label { font-weight: bold; }
        .custom-file-label::after { content: "Gözat"; }
        .img-wrap { position: relative; display: inline-block; margin: 0 5px 5px 0; cursor: grab; }
        .img-wrap img { height: 60px; border-radius: 5px; border: 1px solid #ddd; object-fit: cover; }
        .img-wrap .delete-img { position: absolute; top: -5px; right: -5px; background: red; color: white; border: none; border-radius: 50%; width: 20px; height: 20px; font-size: 10px; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    </style>
</head>
<body>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mt-4">
        <h2><?= $project ? 'Projeyi Düzenle (ID: '.$project['id'].')' : 'Yeni Proje Ekle' ?></h2>
        <a href="index.php" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Geri Dön</a>
    </div>

    <form id="projectForm" enctype="multipart/form-data">
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="id" value="<?= $project ? $project['id'] : $newId ?>">
        <input type="hidden" name="is_new" value="<?= $project ? '0' : '1' ?>">

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">Temel Bilgiler</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Vitrin Başlık</label>
                            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($project['title'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Detay Sayfa Başlığı</label>
                            <input type="text" name="detay_baslik" class="form-control" value="<?= htmlspecialchars($project['detay_baslik'] ?? '') ?>" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Kategori</label>
                                <input list="kategoriList" name="kategori" class="form-control" value="<?= htmlspecialchars($project['kategori'] ?? '') ?>" required>
                                <datalist id="kategoriList">
                                    <option value="avm">
                                    <option value="ofis">
                                    <option value="otel">
                                    <option value="rezidans">
                                    <option value="sinema">
                                </datalist>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Ülke</label>
                                <input list="ulkeList" name="ulke" class="form-control" value="<?= htmlspecialchars($project['ulke'] ?? '') ?>" required>
                                <datalist id="ulkeList">
                                    <option value="turkiye">
                                    <option value="romanya">
                                    <option value="moldova">
                                    <option value="cin">
                                    <option value="kosova">
                                </datalist>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="gelecek" name="gelecek" <?= ($project['gelecek'] ?? false) ? 'checked' : '' ?>>
                                <label class="custom-control-label" for="gelecek">Bu bir Gelecek Projedir</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-success text-white">Detay Bilgileri (6 Alan)</div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">Boş bırakılan satırlar detay sayfasında görünmez.</p>
                        
                        <datalist id="etiketList">
                            <option value="Açılış Tarihi">
                            <option value="Kiralanabilir Alan">
                            <option value="Mağaza Sayısı">
                            <option value="Otopark Kapasitesi">
                            <option value="Yenileme Tarihi">
                            <option value="Daire Sayısı">
                            <option value="Kat Sayısı">
                            <option value="Oda Sayısı">
                            <option value="Salon Sayısı">
                            <option value="İzleyici Kapasitesi">
                        </datalist>

                        <?php for($i=0; $i<6; $i++): ?>
                            <div class="form-row mb-2">
                                <div class="col-5">
                                    <input list="etiketList" type="text" name="bilgiler[<?= $i ?>][0]" class="form-control form-control-sm" placeholder="Etiket (Örn: Açılış Tarihi)" value="<?= htmlspecialchars($bilgiler[$i][0] ?? '') ?>">
                                </div>
                                <div class="col-7">
                                    <input type="text" name="bilgiler[<?= $i ?>][1]" class="form-control form-control-sm" placeholder="Değer (Örn: 2008)" value="<?= htmlspecialchars($bilgiler[$i][1] ?? '') ?>">
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-info text-white">Görseller ve Medya</div>
                    <div class="card-body">
                        
                        <div class="form-group">
                            <label>Kapak Görseli (Vitrin ve Detay İlk Fotoğrafı)</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="kapak_image" id="kapak_image" accept="image/*">
                                <label class="custom-file-label" for="kapak_image">Dosya Seçin...</label>
                            </div>
                            <small class="form-text text-muted">Aynı isimde dosya varsa üzerine yazar. (kapak.jpg)</small>
                            <?php if($id && file_exists("../projects/$id/img/kapak.jpg")): ?>
                                <div class="mt-2"><img src="../projects/<?= $id ?>/img/kapak.jpg" style="height:100px; border-radius:5px; border:1px solid #ddd;"></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mt-4">
                            <label>Slider Görselleri (Çoklu Seçilebilir)</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="slide_images[]" id="slide_images" accept="image/*" multiple>
                                <label class="custom-file-label" for="slide_images">Dosya Seçin...</label>
                            </div>
                            <small class="form-text text-muted">Yeni eklenenler mevcutların sonuna eklenecektir. Sürükleyerek sıralayabilirsiniz.</small>
                            <?php if($id): ?>
                                <div class="mt-2 sortable-gallery" data-folder="slide">
                                <?php foreach(glob("../projects/$id/slide/*.jpg") as $img): ?>
                                    <div class="img-wrap" data-file="<?= basename($img) ?>">
                                        <button type="button" class="delete-img"><i class="fa fa-times"></i></button>
                                        <img src="<?= $img ?>?v=<?= time() ?>">
                                    </div>
                                <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <hr>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch mb-2">
                                <input type="checkbox" class="custom-control-input" id="katplani" name="katplani" <?= ($project['katplani'] ?? false) ? 'checked' : '' ?>>
                                <label class="custom-control-label" for="katplani">Kat Planı Var</label>
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="katplani_images[]" id="katplani_images" accept="image/*" multiple>
                                <label class="custom-file-label" for="katplani_images">Dosya Seçin...</label>
                            </div>
                            <small class="form-text text-muted">Yeni eklenenler mevcutların sonuna eklenecektir. Sürükleyerek sıralayabilirsiniz.</small>
                            <?php if($id): ?>
                                <div class="mt-2 sortable-gallery" data-folder="katplani">
                                <?php foreach(glob("../projects/$id/katplani/*.jpg") as $img): ?>
                                    <div class="img-wrap" data-file="<?= basename($img) ?>">
                                        <button type="button" class="delete-img"><i class="fa fa-times"></i></button>
                                        <img src="<?= $img ?>?v=<?= time() ?>">
                                    </div>
                                <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <hr>

                        <div class="form-group">
                            <div class="custom-control custom-switch mb-2">
                                <input type="checkbox" class="custom-control-input" id="yenileme" name="yenileme" <?= ($project['yenileme'] ?? false) ? 'checked' : '' ?>>
                                <label class="custom-control-label" for="yenileme">Yenileme Var</label>
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" name="yenileme_images[]" id="yenileme_images" accept="image/*" multiple>
                                <label class="custom-file-label" for="yenileme_images">Dosya Seçin...</label>
                            </div>
                            <small class="form-text text-muted">Yeni eklenenler mevcutların sonuna eklenecektir. Sürükleyerek sıralayabilirsiniz.</small>
                            <?php if($id): ?>
                                <div class="mt-2 sortable-gallery" data-folder="yenileme">
                                <?php foreach(glob("../projects/$id/yenileme/*.jpg") as $img): ?>
                                    <div class="img-wrap" data-file="<?= basename($img) ?>">
                                        <button type="button" class="delete-img"><i class="fa fa-times"></i></button>
                                        <img src="<?= $img ?>?v=<?= time() ?>">
                                    </div>
                                <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block btn-lg"><i class="fa fa-save"></i> Kaydet ve Siteyi Yenile</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
$('.custom-file-input').on('change',function(){
    var files = $(this)[0].files;
    var label = $(this).next('.custom-file-label');
    if(files.length > 1) {
        label.html(files.length + " dosya seçildi");
    } else if (files.length == 1) {
        label.html(files[0].name);
    } else {
        label.html("Dosya Seçin...");
    }
});

$('#projectForm').on('submit', function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    let submitBtn = $(this).find('button[type="submit"]');
    submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Kaydediliyor...');

    $.ajax({
        url: 'action.php',
        type: 'POST',
        data: formData,
        success: function (data) {
            submitBtn.prop('disabled', false).html('<i class="fa fa-save"></i> Kaydet ve Siteyi Yenile');
            if (data.success) {
                alert('Başarıyla kaydedildi!');
                window.location.href = 'index.php';
            } else {
                alert('Hata: ' + data.error);
            }
        },
        error: function () {
            submitBtn.prop('disabled', false).html('<i class="fa fa-save"></i> Kaydet ve Siteyi Yenile');
            alert('Sunucu ile iletişim kurulamadı.');
        },
        cache: false,
        contentType: false,
        processData: false
    });
});

// Image Management
$(document).ready(function() {
    let projectId = $('input[name="id"]').val();
    let isNew = $('input[name="is_new"]').val() === '1';

    if (!isNew) {
        $('.sortable-gallery').each(function() {
            var el = this;
            var folder = $(el).data('folder');
            
            Sortable.create(el, {
                animation: 150,
                onEnd: function () {
                    let order = [];
                    $(el).find('.img-wrap').each(function() {
                        order.push($(this).data('file'));
                    });
                    
                    $.post('action.php', {
                        action: 'reorder_images',
                        id: projectId,
                        folder: folder,
                        order: order
                    }, function(res) {
                        if(res.success) {
                            // Optionally reload the page or update filenames in DOM if needed
                            // A reload is safest to ensure DOM reflects exact filenames
                            window.location.reload();
                        } else {
                            alert('Sıralama güncellenemedi.');
                        }
                    }, 'json');
                }
            });
        });

        $('.delete-img').click(function(e) {
            e.preventDefault();
            if(confirm('Bu resmi silmek istediğinize emin misiniz?')) {
                let btn = $(this);
                let wrap = btn.closest('.img-wrap');
                let filename = wrap.data('file');
                let folder = wrap.closest('.sortable-gallery').data('folder');

                $.post('action.php', {
                    action: 'delete_image',
                    id: projectId,
                    folder: folder,
                    filename: filename
                }, function(res) {
                    if(res.success) {
                        window.location.reload();
                    } else {
                        alert('Resim silinemedi.');
                    }
                }, 'json');
            }
        });
    }
});
</script>

</body>
</html>
