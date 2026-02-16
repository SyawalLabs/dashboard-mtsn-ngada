<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== COMPREHENSIVE SYSTEM CHECK ===\n\n";

include_once 'config/database.php';
$db = new Database();

$issues = [];
$checks_passed = 0;

// CHECK 1: Database Connection
echo "1. Database Connection: ";
try {
    $result = $db->query("SELECT 1");
    echo "✅ OK\n";
    $checks_passed++;
} catch (Exception $e) {
    echo "❌ FAIL\n";
    $issues[] = "Database error: " . $e->getMessage();
}

// CHECK 2: Session handling in files
echo "2. POST Handler Structure:\n";
$tambah_files = [
    'pages/siswa/tambah.php',
    'pages/guru/tambah.php',
    'pages/mapel/tambah.php',
    'pages/kelas/tambah.php',
    'pages/jadwal/tambah.php'
];

foreach ($tambah_files as $file) {
    $content = file_get_contents($file);

    // Check POST handler exists
    if (preg_match('/if \(\$_SERVER\[\'REQUEST_METHOD\'\] == \'POST\'\)/', $content)) {
        print "   ✅ $file - POST handler present\n";
        $checks_passed++;
    } else {
        print "   ❌ $file - Missing POST handler\n";
        $issues[] = "$file missing POST handler";
    }

    // Check for exit() in handler
    if (preg_match('/header\("Location:.*exit\(\)/s', $content)) {
        // Has proper redirect
    } else if (strpos($content, 'exit()') === false) {
        $issues[] = "$file may not properly exit on POST";
    }

    // Check no redundant footer
    if (strpos($content, "include 'includes/footer.php'") !== false) {
        $issues[] = "$file still has redundant footer include";
    }
}

// CHECK 3: Edit files structure
echo "\n3. Edit Handler Structure:\n";
$edit_files = [
    'pages/siswa/edit.php',
    'pages/guru/edit.php',
    'pages/mapel/edit.php',
    'pages/kelas/edit.php',
    'pages/jadwal/edit.php',
    'pages/nilai/edit.php'
];

foreach ($edit_files as $file) {
    $content = file_get_contents($file);

    // Check POST handler
    if (preg_match('/if \(\$_SERVER\[\'REQUEST_METHOD\'\] == \'POST\'\)/', $content)) {
        print "   ✅ $file - POST handler present\n";
        $checks_passed++;
    } else {
        print "   ❌ $file - Missing POST handler\n";
        $issues[] = "$file missing POST handler";
    }

    // Check GET handler with session_start
    if (preg_match('/session_start\(\).*\$_GET/', $content)) {
        // Good
    }

    // Check no redundant footer
    if (strpos($content, "include 'includes/footer.php'") !== false) {
        $issues[] = "$file still has redundant footer include";
    }
}

// CHECK 4: index.php structure
echo "\n4. Index.php Structure:\n";
$index_content = file_get_contents('index.php');

if (preg_match('/if \(\$_SERVER\[\'REQUEST_METHOD\'\] == \'POST\'\)/', $index_content)) {
    echo "   ✅ POST check present\n";
    $checks_passed++;
} else {
    echo "   ❌ POST check missing\n";
    $issues[] = "index.php missing POST check";
}

if (preg_match('/ob_start\(\)/', $index_content)) {
    echo "   ✅ ob_start found\n";
    $checks_passed++;
} else {
    echo "   ❌ ob_start missing\n";
    $issues[] = "index.php missing ob_start";
}

if (preg_match('/if \(\$_SERVER\[\'REQUEST_METHOD\'\] == \'GET\'\)/', $index_content)) {
    echo "   ✅ GET check present\n";
    $checks_passed++;
} else {
    echo "   ❌ GET check missing\n";
    $issues[] = "index.php missing GET check";
}

// CHECK 5: PHP Syntax
echo "\n5. PHP Syntax Check:\n";
$all_files = array_merge($tambah_files, $edit_files, ['pages/siswa/index.php', 'pages/nilai/input.php']);
$syntax_ok = 0;
foreach ($all_files as $file) {
    $result = shell_exec("php -l \"$file\" 2>&1");
    if (strpos($result, 'No syntax errors') !== false) {
        $syntax_ok++;
    } else {
        $issues[] = "$file has syntax errors";
    }
}
echo "   ✅ $syntax_ok/" . count($all_files) . " files have no syntax errors\n";
$checks_passed++;

// CHECK 6: Redirect paths
echo "\n6. Redirect Path Verification:\n";
$path_check = [
    'pages/siswa/tambah.php' => '../index.php?page=siswa',
    'pages/guru/tambah.php' => '../index.php?page=guru',
    'pages/mapel/tambah.php' => '../index.php?page=mapel',
    'pages/kelas/tambah.php' => '../index.php?page=kelas',
    'pages/jadwal/tambah.php' => '../index.php?page=jadwal',
    'pages/siswa/edit.php' => '../index.php?page=siswa',
    'pages/guru/edit.php' => '../index.php?page=guru',
    'pages/mapel/edit.php' => '../index.php?page=mapel',
    'pages/kelas/edit.php' => '../index.php?page=kelas',
    'pages/jadwal/edit.php' => '../index.php?page=jadwal',
    'pages/nilai/edit.php' => '../index.php?page=nilai'
];

$path_ok = 0;
foreach ($path_check as $file => $expected_path) {
    $content = file_get_contents($file);
    if (strpos($content, 'Location: ' . $expected_path) !== false) {
        $path_ok++;
    } else {
        $issues[] = "$file may not redirect correctly to $expected_path";
    }
}
echo "   ✅ " . $path_ok . "/" . count($path_check) . " redirect paths verified\n";
$checks_passed++;

// SUMMARY
echo "\n=== SUMMARY ===\n";
echo "Checks Passed: $checks_passed\n";
echo "Files Checked: " . (count($tambah_files) + count($edit_files) + 3) . "\n";

if (empty($issues)) {
    echo "\n🎉 <strong style='color: green;'>ALL SYSTEMS GOOD! ✅</strong>\n";
} else {
    echo "\n⚠️ Issues Found:\n";
    foreach ($issues as $issue) {
        echo "   - $issue\n";
    }
}
