#!/usr/bin/env bash
# ============================================================
# BarberFiner - Project Management Script
# Usage: ./barberfiner.sh [command]
# ============================================================
set -euo pipefail

COMPOSE_FILE="docker-compose.yml"

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m'

print_header() {
    echo -e "${BLUE}"
    echo "╔══════════════════════════════════════╗"
    echo "║         سامانه BarberFiner           ║"
    echo "║   آرایشگاه هوشمند - مدیریت پروژه   ║"
    echo "╚══════════════════════════════════════╝"
    echo -e "${NC}"
}

check_env() {
    if [ ! -f ".env" ]; then
        echo -e "${YELLOW}⚠️  File .env not found. Copying from .env.example ...${NC}"
        cp .env.example .env
        echo -e "${GREEN}✅ .env created.${NC}"
    fi
}

cmd_start() {
    print_header
    check_env
    echo -e "${GREEN}🚀 Starting BarberFiner...${NC}"
    docker compose -f "$COMPOSE_FILE" up -d --build
    echo ""
    echo -e "${GREEN}✅ BarberFiner is running!${NC}"
    echo -e "${BLUE}📌 App:        http://localhost:8680${NC}"
    echo -e "${BLUE}📌 Admin:      http://localhost:8680/admin/login.php${NC}"
    echo -e "${BLUE}📌 phpMyAdmin: http://localhost:8682${NC}"
    echo -e "${BLUE}📌 MySQL:      localhost:8681${NC}"
}

cmd_stop() {
    echo -e "${YELLOW}🛑 Stopping BarberFiner...${NC}"
    docker compose -f "$COMPOSE_FILE" down
    echo -e "${GREEN}✅ Stopped.${NC}"
}

cmd_restart() { cmd_stop; cmd_start; }

cmd_logs() {
    docker compose -f "$COMPOSE_FILE" logs -f "${1:-}"
}

cmd_status() {
    echo -e "${BLUE}📊 BarberFiner Container Status:${NC}"
    docker compose -f "$COMPOSE_FILE" ps
}

cmd_shell() {
    docker compose -f "$COMPOSE_FILE" exec "${1:-app}" bash
}

cmd_db_backup() {
    local f="backup_$(date +%Y%m%d_%H%M%S).sql"
    docker compose -f "$COMPOSE_FILE" exec -T db \
        mysqldump -u root -p"$(grep MYSQL_ROOT_PASSWORD .env | cut -d= -f2)" \
        "$(grep DB_NAME .env | cut -d= -f2)" > "$f"
    echo -e "${GREEN}✅ Backup saved to $f${NC}"
}

cmd_db_import() {
    local f="${1:-barber-finer/database/schema.sql}"
    docker compose -f "$COMPOSE_FILE" exec -T db \
        mysql -u root -p"$(grep MYSQL_ROOT_PASSWORD .env | cut -d= -f2)" \
        "$(grep DB_NAME .env | cut -d= -f2)" < "$f"
    echo -e "${GREEN}✅ Database imported from $f${NC}"
}

cmd_reset_db() {
    echo -e "${RED}⚠️  WARNING: This will destroy ALL database data!${NC}"
    read -r -p "Type 'yes' to confirm: " confirm
    if [ "$confirm" = "yes" ]; then
        docker compose -f "$COMPOSE_FILE" down -v
        docker compose -f "$COMPOSE_FILE" up -d
        echo -e "${GREEN}✅ Database reset.${NC}"
    else
        echo -e "${YELLOW}❌ Cancelled.${NC}"
    fi
}

cmd_update() {
    git pull origin main
    docker compose -f "$COMPOSE_FILE" build --no-cache app
    docker compose -f "$COMPOSE_FILE" up -d
    echo -e "${GREEN}✅ Update complete.${NC}"
}

cmd_help() {
    print_header
    echo "Commands:"
    echo -e "  ${GREEN}start${NC}            Start all services"
    echo -e "  ${GREEN}stop${NC}             Stop all services"
    echo -e "  ${GREEN}restart${NC}          Restart all services"
    echo -e "  ${GREEN}status${NC}           Show container status"
    echo -e "  ${GREEN}logs [service]${NC}   Show logs"
    echo -e "  ${GREEN}shell [service]${NC}  Open shell (default: app)"
    echo -e "  ${GREEN}db-backup${NC}        Backup database"
    echo -e "  ${GREEN}db-import [f]${NC}    Import SQL file"
    echo -e "  ${GREEN}reset-db${NC}         Reset database (DESTRUCTIVE!)"
    echo -e "  ${GREEN}update${NC}           Pull and rebuild"
    echo ""
    echo "Ports (localhost only):"
    echo -e "  ${BLUE}App:        http://localhost:8680${NC}"
    echo -e "  ${BLUE}Admin:      http://localhost:8680/admin/login.php${NC}"
    echo -e "  ${BLUE}MySQL:      localhost:8681${NC}"
    echo -e "  ${BLUE}phpMyAdmin: http://localhost:8682${NC}"
    echo ""
    echo "Default Admin: admin / admin1234"
}

case "${1:-help}" in
    start)      cmd_start ;;
    stop)       cmd_stop ;;
    restart)    cmd_restart ;;
    logs)       cmd_logs "${2:-}" ;;
    status)     cmd_status ;;
    shell)      cmd_shell "${2:-app}" ;;
    db-backup)  cmd_db_backup ;;
    db-import)  cmd_db_import "${2:-barber-finer/database/schema.sql}" ;;
    reset-db)   cmd_reset_db ;;
    update)     cmd_update ;;
    help|*)     cmd_help ;;
esac
