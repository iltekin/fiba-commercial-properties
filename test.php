<?php
$data = [
    [
        'kategori' => 'avm',
        'ulke' => 'romanya',
        'gelecek' => 'hayir',
        'id' => 1,
        'title' => 'BUCURESTI MALL',
        'link' => true
    ],
    [
        'kategori' => 'avm',
        'ulke' => 'moldova',
        'gelecek' => 'hayir',
        'id' => 2,
        'title' => 'SHOPPING MALLDOVA',
        'link' => true
    ],
    [
        'kategori' => 'avm',
        'ulke' => 'romanya',
        'gelecek' => 'hayir',
        'id' => 3,
        'title' => 'PLAZA ROMANIA',
        'link' => true
    ],
    [
        'kategori' => 'avm',
        'ulke' => 'cin',
        'gelecek' => 'hayir',
        'id' => 4,
        'title' => 'STARMALL',
        'link' => true
    ],
    [
        'kategori' => 'avm',
        'ulke' => 'turkiye',
        'gelecek' => 'hayir',
        'id' => 5,
        'title' => 'M1 GAZİANTEP AVM',
        'link' => true
    ],
    [
        'kategori' => 'avm',
        'ulke' => 'turkiye',
        'gelecek' => 'hayir',
        'id' => 6,
        'title' => 'M1 KONYA AVM',
        'link' => true
    ],
    [
        'kategori' => 'avm',
        'ulke' => 'turkiye',
        'gelecek' => 'hayir',
        'id' => 7,
        'title' => 'GEBZE CENTER',
        'link' => true
    ],
    [
        'kategori' => 'avm',
        'ulke' => 'turkiye',
        'gelecek' => 'hayir',
        'id' => 8,
        'title' => 'İNEGÖL AVM',
        'link' => true
    ],
    [
        'kategori' => 'avm',
        'ulke' => 'turkiye',
        'gelecek' => 'hayir',
        'id' => 9,
        'title' => 'M1 ADANA AVM',
        'link' => true
    ],
    [
        'kategori' => 'avm',
        'ulke' => 'turkiye',
        'gelecek' => 'hayir',
        'id' => 10,
        'title' => 'DOWNTOWN BURSA',
        'link' => true
    ],
    [
        'kategori' => 'avm',
        'ulke' => 'turkiye',
        'gelecek' => 'evet',
        'id' => 11,
        'title' => 'SKYTOWER BURSA',
        'link' => true
    ],
    [
        'kategori' => 'avm',
        'ulke' => 'turkiye',
        'gelecek' => 'evet',
        'id' => 12,
        'title' => 'FENIX CENTER',
        'link' => false
    ],
    [
        'kategori' => 'rezidans',
        'ulke' => 'romanya',
        'gelecek' => 'hayir',
        'id' => 13,
        'title' => 'INCITY RESIDENCE',
        'link' => true
    ],
    [
        'kategori' => 'rezidans',
        'ulke' => 'romanya',
        'gelecek' => 'hayir',
        'id' => 14,
        'title' => 'SELECT REZİDANS',
        'link' => true
    ],
    [
        'kategori' => 'rezidans',
        'ulke' => 'romanya',
        'gelecek' => 'evet',
        'id' => 15,
        'title' => 'DRUMUL TABEREİ REZİDANS',
        'link' => false
    ],
    [
        'kategori' => 'rezidans',
        'ulke' => 'romanya',
        'gelecek' => 'hayir',
        'id' => 16,
        'title' => 'PALLADY TOWER REZİDANS',
        'link' => true
    ],
    [
        'kategori' => 'ofis',
        'ulke' => 'turkiye',
        'gelecek' => 'hayir',
        'id' => 17,
        'title' => 'OFİSHANE',
        'link' => true
    ],
    [
        'kategori' => 'ofis',
        'ulke' => 'romanya',
        'gelecek' => 'hayir',
        'id' => 18,
        'title' => 'ANCHOR PLAZA OFİS',
        'link' => true
    ],
    [
        'kategori' => 'ofis',
        'ulke' => 'romanya',
        'gelecek' => 'hayir',
        'id' => 19,
        'title' => 'PLAZA ROMANIA OFİS',
        'link' => true
    ],
    [
        'kategori' => 'ofis',
        'ulke' => 'romanya',
        'gelecek' => 'evet',
        'id' => 20,
        'title' => 'METROPOL PLAZA OFİS',
        'link' => false
    ],
    [
        'kategori' => 'ofis',
        'ulke' => 'moldova',
        'gelecek' => 'hayir',
        'id' => 21,
        'title' => 'MALLDOVA CENTER OFİS',
        'link' => true
    ],
    [
        'kategori' => 'otel',
        'ulke' => 'moldova',
        'gelecek' => 'hayir',
        'id' => 22,
        'title' => 'COURTYARD BY MARRIOTT',
        'link' => true
    ],
    [
        'kategori' => 'sinema',
        'ulke' => 'turkiye',
        'gelecek' => 'hayir',
        'id' => 23,
        'title' => 'SİNEPARK GAZİANTEP',
        'link' => true
    ],
    [
        'kategori' => 'sinema',
        'ulke' => 'romanya',
        'gelecek' => 'hayir',
        'id' => 24,
        'title' => 'MOVIEPLEX SİNEMA ROMANYA',
        'link' => true
    ],
    [
        'kategori' => 'sinema',
        'ulke' => 'romanya',
        'gelecek' => 'hayir',
        'id' => 25,
        'title' => 'HOLLYWOOD MULTIPLEX ROMANYA',
        'link' => true
    ],
];

foreach ($data as $item) { ?>
    <div class="col-12 col-sm-6 col-lg-4 col-xl-3" data-kategori="<?=$item['kategori']?>" data-ulke="<?=$item['ulke']?>" data-gelecek="<?=$item['gelecek']?>">
        <?php if($item['link'] === true) { ?><a href="projects/<?=$item['id']?>/index.html"><?php } echo "\r\n"; ?>
            <div class="project" id="project<?=$item['id']?>">
                <div class="project-overlay">
                    <h5 class="project-top-left-info d-none"><i class="fa fa-gem mr-1"></i> Gelecek Proje</h5>
                    <h5 class="project-title"><?=$item['title']?></h5>
                </div>
            </div>
        <?php if($item['link'] === true) { ?></a><?php } echo "\r\n"; ?>
    </div>
<?php echo "\r\n"; }  ?>
