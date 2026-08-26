# ============================================================
# DEPLOY LOCAL - Web Edukasi
# Jalankan di local untuk build & pack file deployment
# ============================================================

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "  WEB EDUKASI - DEPLOY LOCAL" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

$ProjectDir = "C:\xampp\htdocs\web-edukasi"
$DeployDir  = "C:\xampp\htdocs\web-edukasi-deploy"

# --- STEP 1: Bersihkan folder deploy lama ---
Write-Host "[1/7] Bersihkan folder deploy lama..." -ForegroundColor Yellow
if (Test-Path $DeployDir) {
    Remove-Item -Recurse -Force $DeployDir
}
New-Item -ItemType Directory -Path $DeployDir | Out-Null
Write-Host "      OK" -ForegroundColor Green

# --- STEP 2: Copy file project ---
Write-Host "[2/7] Copy file project..." -ForegroundColor Yellow
$excludeDirs = @("node_modules", ".git", "vendor", "tests")
$excludeFiles = @(".env", ".env.backup", ".env.production", ".gitignore", ".gitattributes",
                   ".editorconfig", ".phpunit.result.cache", "phpunit.xml",
                   "package-lock.json", ".npmrc", "*.md")

# Copy directories
Get-ChildItem -Path $ProjectDir -Directory | Where-Object {
    $_.Name -notin $excludeDirs
} | ForEach-Object {
    Copy-Item -Path $_.FullName -Destination "$DeployDir\$($_.Name)" -Recurse -Force
}

# Copy files
Get-ChildItem -Path $ProjectDir -File | Where-Object {
    $_.Name -notin $excludeFiles -and $_.Extension -ne ".cache"
} | ForEach-Object {
    Copy-Item -Path $_.FullName -Destination "$DeployDir\$($_.Name)" -Force
}

Write-Host "      OK" -ForegroundColor Green

# --- STEP 3: Copy .env.production sebagai .env ---
Write-Host "[3/7] Copy .env.production..." -ForegroundColor Yellow
$envProdPath = "$ProjectDir\scripts\.env.production"
if (Test-Path $envProdPath) {
    Copy-Item -Path $envProdPath -Destination "$DeployDir\.env" -Force
    Write-Host "      OK (edit .env di server nanti)" -ForegroundColor Green
} else {
    Write-Host "      WARN: .env.production tidak ditemukan!" -ForegroundColor Red
}

# --- STEP 4: Build assets ---
Write-Host "[4/7] Build assets (npm run build)..." -ForegroundColor Yellow
Set-Location $ProjectDir
npm run build
if ($LASTEXITCODE -ne 0) {
    Write-Host "      ERROR: Build gagal!" -ForegroundColor Red
    exit 1
}
Write-Host "      OK" -ForegroundColor Green

# --- STEP 5: Copy build folder ---
Write-Host "[5/7] Copy build folder..." -ForegroundColor Yellow
if (Test-Path "$ProjectDir\public\build") {
    Copy-Item -Path "$ProjectDir\public\build" -Destination "$DeployDir\public\build" -Recurse -Force
    Write-Host "      OK" -ForegroundColor Green
} else {
    Write-Host "      WARN: public/build tidak ada!" -ForegroundColor Red
}

# --- STEP 6: Buat .htaccess redirect ---
Write-Host "[6/7] Buat .htaccess redirect..." -ForegroundColor Yellow
$htaccess = @"
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/`$1 [L]
</IfModule>
"@
Set-Content -Path "$DeployDir\.htaccess" -Value $htaccess -Encoding UTF8
Write-Host "      OK" -ForegroundColor Green

# --- STEP 7: Compress folder ---
Write-Host "[7/7] Compress folder deployment..." -ForegroundColor Yellow
$zipPath = "C:\xampp\htdocs\web-edukasi-deploy.zip"
if (Test-Path $zipPath) {
    Remove-Item -Force $zipPath
}
Compress-Archive -Path "$DeployDir\*" -DestinationPath $zipPath -Force
Write-Host "      OK: $zipPath" -ForegroundColor Green

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "  SELESAI! File deployment sudah siap" -ForegroundColor Green
Write-Host "  Zip: $zipPath" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "Langkah selanjutnya:" -ForegroundColor Cyan
Write-Host "  1. Upload web-edukasi-deploy.zip ke Hostinger File Manager" -ForegroundColor White
Write-Host "  2. Extract di public_html/" -ForegroundColor White
Write-Host "  3. Buka SSH Terminal, jalankan: bash deploy.sh" -ForegroundColor White
Write-Host ""
