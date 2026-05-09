<?php
header('Content-Type: application/json');

$action = $_POST['action'] ?? '';
$dataFile = '../data.json';
$projects = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];

function saveProjects($data) {
    global $dataFile;
    file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function recursiveRemoveDirectory($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
                    recursiveRemoveDirectory($dir. DIRECTORY_SEPARATOR .$object);
                else
                    unlink($dir. DIRECTORY_SEPARATOR .$object);
            }
        }
        rmdir($dir);
    }
}

function handleUploads($id) {
    $baseDir = "../projects/$id";
    
    // Create folders if they don't exist
    if(!is_dir($baseDir)) {
        mkdir($baseDir, 0777, true);
        
        // Copy templates
        if(file_exists("../templates/project/index.html")) copy("../templates/project/index.html", "$baseDir/index.html");
        if(file_exists("../templates/project/katplani.html")) copy("../templates/project/katplani.html", "$baseDir/katplani.html");
        if(file_exists("../templates/project/yenileme.html")) copy("../templates/project/yenileme.html", "$baseDir/yenileme.html");
    }
    
    foreach(['img', 'slide', 'katplani', 'yenileme'] as $d) {
        if(!is_dir("$baseDir/$d")) mkdir("$baseDir/$d", 0777, true);
    }

    $counts = [];

    // 1. Kapak Image
    if(isset($_FILES['kapak_image']) && $_FILES['kapak_image']['error'] == UPLOAD_ERR_OK) {
        move_uploaded_file($_FILES['kapak_image']['tmp_name'], "$baseDir/img/kapak.jpg");
    }

    // 2. Slide Images
    if(isset($_FILES['slide_images']) && $_FILES['slide_images']['error'][0] == UPLOAD_ERR_OK) {
        $existing = glob("$baseDir/slide/*.jpg");
        $startIdx = count($existing) + 1;
        $total = count($_FILES['slide_images']['name']);
        for($i=0; $i<$total; $i++) {
            $j = $startIdx + $i;
            move_uploaded_file($_FILES['slide_images']['tmp_name'][$i], "$baseDir/slide/$j.jpg");
        }
        $counts['slideSayisi'] = $startIdx + $total - 1;
    } else {
        // Just count existing
        $counts['slideSayisi'] = count(glob("$baseDir/slide/*.jpg"));
    }

    // 3. Kat Plani
    if(isset($_FILES['katplani_images']) && $_FILES['katplani_images']['error'][0] == UPLOAD_ERR_OK) {
        $existing = glob("$baseDir/katplani/*.jpg");
        $startIdx = count($existing) + 1;
        $total = count($_FILES['katplani_images']['name']);
        for($i=0; $i<$total; $i++) {
            $j = $startIdx + $i;
            move_uploaded_file($_FILES['katplani_images']['tmp_name'][$i], "$baseDir/katplani/$j.jpg");
        }
        $counts['plansayisi'] = $startIdx + $total - 1;
    } else {
        $counts['plansayisi'] = count(glob("$baseDir/katplani/*.jpg"));
    }

    // 4. Yenileme
    if(isset($_FILES['yenileme_images']) && $_FILES['yenileme_images']['error'][0] == UPLOAD_ERR_OK) {
        $existing = glob("$baseDir/yenileme/*.jpg");
        $startIdx = count($existing) + 1;
        $total = count($_FILES['yenileme_images']['name']);
        for($i=0; $i<$total; $i++) {
            $j = $startIdx + $i;
            move_uploaded_file($_FILES['yenileme_images']['tmp_name'][$i], "$baseDir/yenileme/$j.jpg");
        }
    }

    return $counts;
}

function generateSite($projects) {
    // Generate projects.js
    $jsObj = [];
    foreach($projects as $p) {
        $id = $p['id'];
        $jsObj[$id] = [
            'baslik' => $p['detay_baslik'],
            'yenileme' => $p['yenileme'],
            'katplani' => $p['katplani'],
            'slideSayisi' => $p['slideSayisi'],
            'plansayisi' => $p['plansayisi'],
            'bilgiler' => $p['bilgiler']
        ];
    }
    
    $jsContent = "const projects = " . json_encode($jsObj, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . ";\n\n";
    $jsContent .= "const path = window.location.pathname.split(\"/\");\n";
    $jsContent .= "const pathKey = path.length - 2;\n";
    $jsContent .= "const id = path[pathKey];\n\n";
    $jsContent .= "const { baslik, yenileme, katplani, slideSayisi, plansayisi, bilgiler } = projects[id];\n";
    
    file_put_contents('../projects.js', $jsContent);

    // Generate index.html
    $template = file_get_contents('../templates/index.html');
    
    $htmlList = "";
    
    // Sort before rendering
    usort($projects, function($a, $b) {
        return ($a['sira'] ?? 0) <=> ($b['sira'] ?? 0);
    });

    foreach($projects as $p) {
        if ($p['gizli']) continue;

        $gelecekStr = $p['gelecek'] ? "evet" : "hayir";
        $dNone = $p['gelecek'] ? "" : "d-none";

        $htmlList .= "    <div class=\"col-12 col-sm-6 col-lg-4 col-xl-3\" data-kategori=\"{$p['kategori']}\" data-ulke=\"{$p['ulke']}\" data-gelecek=\"{$gelecekStr}\"\">\n";
        $htmlList .= "        <a href=\"projects/{$p['id']}/index.html\">\n";
        $htmlList .= "            <div class=\"project\" id=\"project{$p['id']}\" data-id=\"{$p['id']}\">\n";
        $htmlList .= "                <div class=\"project-overlay\">\n";
        $htmlList .= "                    <h5 class=\"project-top-left-info {$dNone}\"><i class=\"fa fa-gem mr-1\"></i> Gelecek Proje</h5>\n";
        $htmlList .= "                    <h5 class=\"project-title\">{$p['title']}</h5>\n";
        $htmlList .= "                </div>\n";
        $htmlList .= "            </div>\n";
        $htmlList .= "        </a>\n";
        $htmlList .= "    </div>\n\n";
    }

    $startMarker = "<!-- PROJECTS_START -->";
    $endMarker = "<!-- PROJECTS_END -->";

    $startPos = strpos($template, $startMarker);
    $endPos = strpos($template, $endMarker);

    if ($startPos !== false && $endPos !== false) {
        $endPos += strlen($endMarker);
        $newTemplate = substr($template, 0, $startPos) . $startMarker . "\n" . $htmlList . "    " . $endMarker . substr($template, $endPos);
        file_put_contents('../index.html', $newTemplate);
    }
}

if ($action == 'reorder') {
    $order = $_POST['order'] ?? [];
    $orderMap = [];
    foreach($order as $o) {
        $orderMap[$o['id']] = (int)$o['sira'];
    }
    
    foreach($projects as &$p) {
        if(isset($orderMap[$p['id']])) {
            $p['sira'] = $orderMap[$p['id']];
        }
    }
    saveProjects($projects);
    generateSite($projects);
    echo json_encode(['success' => true]);
    exit;
}

if ($action == 'toggle_hidden') {
    $id = (int)$_POST['id'];
    $hidden = (bool)$_POST['hidden'];
    foreach($projects as &$p) {
        if($p['id'] == $id) {
            $p['gizli'] = $hidden;
            break;
        }
    }
    saveProjects($projects);
    generateSite($projects);
    echo json_encode(['success' => true]);
    exit;
}

if ($action == 'delete') {
    $id = (int)$_POST['id'];
    $newProjects = [];
    foreach($projects as $p) {
        if($p['id'] != $id) {
            $newProjects[] = $p;
        }
    }
    saveProjects($newProjects);
    recursiveRemoveDirectory("../projects/$id");
    generateSite($newProjects);
    echo json_encode(['success' => true]);
    exit;
}

if ($action == 'save') {
    $id = (int)$_POST['id'];
    $isNew = $_POST['is_new'] === '1';

    $counts = handleUploads($id);

    $newData = [
        'id' => $id,
        'title' => $_POST['title'],
        'detay_baslik' => $_POST['detay_baslik'],
        'kategori' => $_POST['kategori'],
        'ulke' => $_POST['ulke'],
        'gelecek' => isset($_POST['gelecek']),
        'yenileme' => isset($_POST['yenileme']),
        'katplani' => isset($_POST['katplani']),
        'slideSayisi' => $counts['slideSayisi'] ?? 0,
        'plansayisi' => $counts['plansayisi'] ?? 0,
        'bilgiler' => $_POST['bilgiler'] ?? []
    ];

    if ($isNew) {
        $newData['sira'] = count($projects) + 1;
        $newData['gizli'] = false;
        $projects[] = $newData;
    } else {
        foreach($projects as &$p) {
            if($p['id'] == $id) {
                // keep sira and gizli
                $newData['sira'] = $p['sira'] ?? 0;
                $newData['gizli'] = $p['gizli'] ?? false;
                $p = $newData;
                break;
            }
        }
    }

    saveProjects($projects);
    generateSite($projects);

    echo json_encode(['success' => true]);
    exit;
}

if ($action == 'delete_image') {
    $id = (int)$_POST['id'];
    $folder = $_POST['folder']; // slide, katplani, yenileme
    $filename = basename($_POST['filename']);
    
    $path = "../projects/$id/$folder/$filename";
    if (file_exists($path)) {
        unlink($path);
        // Rename remaining to close the gap
        $files = glob("../projects/$id/$folder/*.jpg");
        usort($files, function($a, $b) {
            return (int)basename($a) <=> (int)basename($b);
        });
        
        $newCount = count($files);
        for($i=0; $i<$newCount; $i++) {
            $expected = $i + 1;
            $current = (int)basename($files[$i]);
            if ($current !== $expected) {
                rename($files[$i], "../projects/$id/$folder/$expected.jpg");
            }
        }
        
        // Update counts in data.json
        if ($folder === 'slide' || $folder === 'katplani') {
            foreach($projects as &$p) {
                if($p['id'] == $id) {
                    if ($folder === 'slide') $p['slideSayisi'] = $newCount;
                    if ($folder === 'katplani') $p['plansayisi'] = $newCount;
                    break;
                }
            }
            saveProjects($projects);
            generateSite($projects);
        }
        
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'File not found']);
    }
    exit;
}

if ($action == 'reorder_images') {
    $id = (int)$_POST['id'];
    $folder = $_POST['folder'];
    $order = $_POST['order']; // array of current filenames in new order
    
    // First, rename them to temp names to avoid collisions
    $tempMap = [];
    foreach($order as $index => $filename) {
        $filename = basename($filename);
        $oldPath = "../projects/$id/$folder/$filename";
        $tempPath = "../projects/$id/$folder/temp_" . ($index + 1) . ".jpg";
        if (file_exists($oldPath)) {
            rename($oldPath, $tempPath);
            $tempMap[] = $tempPath;
        }
    }
    
    // Rename temp to final
    foreach($tempMap as $index => $tempPath) {
        $finalPath = "../projects/$id/$folder/" . ($index + 1) . ".jpg";
        rename($tempPath, $finalPath);
    }
    
    echo json_encode(['success' => true]);
    exit;
}

if ($action == 'regenerate') {
    generateSite($projects);
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['success' => false, 'error' => 'Geçersiz işlem']);
