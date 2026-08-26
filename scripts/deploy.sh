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
DEPLOY_DIR="$HOME/public_html"
LOG_FILE="$HOME/deploy.log"

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
read -p "      Jalankan migration? (y/n): " RUN_MIGRATE
if [ "$RUN_MIGRATE" = "y" ] || [ "$RUN_MIGRATE" = "Y" ]; then
    php artisan migrate --force 2>&1 | tee -a "$LOG_FILE"
    log "${GREEN}      OK${NC}"
else
    log "${YELLOW}      SKIP: Migration dilewati${NC}"
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
