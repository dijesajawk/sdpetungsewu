#!/bin/bash
# ============================================================
# DEPLOY SERVER - Web Edukasi
# Jalankan di Hostinger via SSH Terminal
# ============================================================

set -e

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
NC='\033[0m'

echo -e "${CYAN}========================================${NC}"
echo -e "${CYAN}  WEB EDUKASI - DEPLOY SERVER${NC}"
echo -e "${CYAN}========================================${NC}"
echo ""

# --- Konfigurasi ---
# Deteksi path otomatis untuk Hostinger shared hosting
if [ -d "$HOME/domains/mediumblue-dragonfly-690355.hostingersite.com/public_html" ]; then
    DEPLOY_DIR="$HOME/domains/mediumblue-dragonfly-690355.hostingersite.com/public_html"
elif [ -d "$HOME/public_html" ]; then
    DEPLOY_DIR="$HOME/public_html"
else
    DEPLOY_DIR="$(pwd)"
fi
LOG_FILE="$HOME/deploy.log"

# --- Fix PHP version untuk Hostinger shared hosting ---
# SSH mungkin pakai PHP versi lama, hPanel pakai versi baru
if [ -d "/opt/alt/php83/usr/bin" ]; then
    export PATH="/opt/alt/php83/usr/bin:$PATH"
    echo -e "${GREEN}      PHP PATH fixed: /opt/alt/php83/usr/bin${NC}"
fi

# Coba composer2 jika composer tidak ada atau versi lama
if ! command -v composer &> /dev/null; then
    if command -v composer2 &> /dev/null; then
        alias composer=composer2
    fi
fi

# --- Fungsi helper ---
log() {
    echo -e "$1" | tee -a "$LOG_FILE"
}

# --- STEP 1: Cek folder deployment ---
log "${YELLOW}[1/8] Cek folder deployment...${NC}"
if [ ! -d "$DEPLOY_DIR" ]; then
    log "${RED}ERROR: Folder $DEPLOY_DIR tidak ditemukan!${NC}"
    log "Pastikan file sudah di-extract ke public_html/"
    exit 1
fi
cd "$DEPLOY_DIR"
log "${GREEN}      OK${NC}"

# --- STEP 2: Cek PHP version ---
log "${YELLOW}[2/8] Cek PHP version...${NC}"
PHP_VERSION=$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')
log "      PHP $PHP_VERSION terdeteksi"
if [ "$(php -r 'echo PHP_MAJOR_VERSION;')" -lt 8 ]; then
    log "${RED}      ERROR: PHP harus 8.3+!${NC}"
    log "      Ubah di hPanel → PHP Settings"
    exit 1
fi
log "${GREEN}      OK${NC}"

# --- STEP 3: Install composer dependencies ---
log "${YELLOW}[3/8] Install composer dependencies...${NC}"
if [ -f "composer.json" ]; then
    composer install --no-dev --optimize-autoloader --no-interaction 2>&1 | tee -a "$LOG_FILE"
    log "${GREEN}      OK${NC}"
else
    log "${YELLOW}      SKIP: composer.json tidak ada${NC}"
fi

# --- STEP 4: Setup .env ---
log "${YELLOW}[4/8] Setup .env...${NC}"
if [ ! -f ".env" ]; then
    if [ -f ".env.example" ]; then
        cp .env.example .env
        log "${YELLOW}      .env dibuat dari .env.example${NC}"
        log "${YELLOW}      WARNING: Edit .env dengan data database!${NC}"
    else
        log "${RED}      ERROR: .env tidak ada!${NC}"
        exit 1
    fi
fi
log "${GREEN}      OK${NC}"

# --- STEP 5: Generate APP_KEY ---
log "${YELLOW}[5/8] Generate APP_KEY...${NC}"
if grep -q "APP_KEY=" .env && [ -z "$(grep 'APP_KEY=' .env | cut -d'=' -f2)" ]; then
    php artisan key:generate --force
    log "${GREEN}      OK${NC}"
else
    KEY=$(grep 'APP_KEY=' .env | cut -d'=' -f2)
    if [ -n "$KEY" ]; then
        log "${GREEN}      APP_KEY sudah ada${NC}"
    else
        php artisan key:generate --force
        log "${GREEN}      OK (generated)${NC}"
    fi
fi

# --- STEP 5.5: Pastikan folder storage ada ---
log "${YELLOW}[5.5/8] Cek folder storage...${NC}"
mkdir -p storage/framework/cache/data storage/framework/sessions \
    storage/framework/testing storage/framework/views storage/logs
log "${GREEN}      OK${NC}"

# --- STEP 6: Set permissions ---
log "${YELLOW}[6/8] Set permissions...${NC}"
if [ -d "storage" ]; then
    chmod -R 775 storage
    chmod -R 775 storage/app
    chmod -R 775 storage/framework
    chmod -R 775 storage/logs
    log "${GREEN}      storage: OK${NC}"
fi
if [ -d "bootstrap/cache" ]; then
    chmod -R 775 bootstrap/cache
    log "${GREEN}      bootstrap/cache: OK${NC}"
fi
if [ -d "public" ]; then
    chmod -R 755 public
    log "${GREEN}      public: OK${NC}"
fi
if [ -f ".env" ]; then
    chmod 644 .env
    log "${GREEN}      .env: OK${NC}"
fi

# --- STEP 7: Jalankan migration ---
log "${YELLOW}[7/8] Jalankan migration...${NC}"
# Auto-detect: jalankan migrate jika ini pertama kali (belum ada table users)
TABLE_EXISTS=$(php artisan migrate:status 2>/dev/null | grep -c "users" || echo "0")
if [ "$TABLE_EXISTS" = "0" ]; then
    log "${YELLOW}      Menjalankan migration pertama kali...${NC}"
    php artisan migrate --force 2>&1 | tee -a "$LOG_FILE"
    log "${GREEN}      OK${NC}"
else
    log "${YELLOW}      Migration sudah dijalankan, cek update...${NC}"
    php artisan migrate --force 2>&1 | tee -a "$LOG_FILE"
    log "${GREEN}      OK${NC}"
fi

# --- STEP 8: Clear cache & optimize ---
log "${YELLOW}[8/8] Clear cache & optimize...${NC}"
php artisan config:clear 2>&1 | tee -a "$LOG_FILE"
php artisan route:clear 2>&1 | tee -a "$LOG_FILE"
php artisan view:clear 2>&1 | tee -a "$LOG_FILE"
php artisan cache:clear 2>&1 | tee -a "$LOG_FILE"

# Optimizer untuk production
php artisan config:cache 2>&1 | tee -a "$LOG_FILE"
php artisan route:cache 2>&1 | tee -a "$LOG_FILE"
php artisan view:cache 2>&1 | tee -a "$LOG_FILE"

log "${GREEN}      OK${NC}"

# --- SELESAI ---
echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  DEPLOY SELESAI!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${CYAN}Langkah selanjutnya:${NC}"
echo -e "  1. Buka browser, akses domain kamu"
echo -e "  2. Login dengan akun admin yang sudah dibuat"
echo -e "  3. Cek log error: cat $LOG_FILE"
echo -e "  4. Kalau error 500, set APP_DEBUG=true di .env lalu cek lagi"
echo ""
echo -e "${YELLOW}Catatan:${NC}"
echo -e "  - Edit .env dengan: nano .env"
echo -e "  - Save: Ctrl+O, Enter, Ctrl+X"
echo -e "  - Log deploy: cat $LOG_FILE"
echo ""
