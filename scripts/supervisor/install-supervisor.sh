#!/bin/bash

# Supervisor Installation and Configuration Script for Microweber
# This script sets up supervisor for managing queue workers

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Default configuration
PROJECT_PATH="${PROJECT_PATH:-/var/www/microweber}"
PHP_BINARY="${PHP_BINARY:-/usr/bin/php}"
USER="${USER:-www-data}"
ENVIRONMENT="${ENVIRONMENT:-production}"

echo -e "${GREEN}Microweber Queue Worker Supervisor Setup${NC}"
echo "================================================"
echo "Project Path: $PROJECT_PATH"
echo "PHP Binary: $PHP_BINARY"
echo "User: $USER"
echo "Environment: $ENVIRONMENT"
echo ""

# Check if running as root
if [ "$EUID" -ne 0 ]; then
    echo -e "${RED}Error: This script must be run as root (use sudo)${NC}"
    exit 1
fi

# Step 1: Check OS and install supervisor
echo -e "${YELLOW}Step 1: Installing Supervisor...${NC}"

if command -v apt-get &> /dev/null; then
    # Debian/Ubuntu
    apt-get update
    apt-get install -y supervisor
elif command -v yum &> /dev/null; then
    # CentOS/RHEL
    yum install -y supervisor
    systemctl enable supervisord
else
    echo -e "${RED}Error: Unsupported package manager. Please install supervisor manually.${NC}"
    exit 1
fi

echo -e "${GREEN}Supervisor installed successfully${NC}"
echo ""

# Step 2: Create log directory
echo -e "${YELLOW}Step 2: Creating log directory...${NC}"
LOG_DIR="$PROJECT_PATH/storage/logs"
mkdir -p "$LOG_DIR"
chown -R "$USER:$USER" "$LOG_DIR"
chmod -R 755 "$LOG_DIR"
echo -e "${GREEN}Log directory created: $LOG_DIR${NC}"
echo ""

# Step 3: Select configuration file
echo -e "${YELLOW}Step 3: Selecting configuration file...${NC}"

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

if [ "$ENVIRONMENT" == "staging" ]; then
    CONFIG_SOURCE="$SCRIPT_DIR/microweber-worker-staging.conf"
else
    CONFIG_SOURCE="$SCRIPT_DIR/microweber-worker.conf"
fi

# Determine supervisor config directory
if [ -d "/etc/supervisor/conf.d" ]; then
    # Debian/Ubuntu
    SUPERVISOR_DIR="/etc/supervisor/conf.d"
    CONFIG_DEST="$SUPERVISOR_DIR/microweber-worker.conf"
elif [ -d "/etc/supervisord.d" ]; then
    # CentOS/RHEL
    SUPERVISOR_DIR="/etc/supervisord.d"
    CONFIG_DEST="$SUPERVISOR_DIR/microweber-worker.ini"
else
    echo -e "${RED}Error: Cannot find supervisor configuration directory${NC}"
    exit 1
fi

echo -e "${GREEN}Supervisor config directory: $SUPERVISOR_DIR${NC}"
echo ""

# Step 4: Copy and customize configuration
echo -e "${YELLOW}Step 4: Installing supervisor configuration...${NC}"

if [ ! -f "$CONFIG_SOURCE" ]; then
    echo -e "${RED}Error: Configuration file not found: $CONFIG_SOURCE${NC}"
    exit 1
fi

# Read and customize the configuration
sed -e "s|/var/www/microweber|$PROJECT_PATH|g" \
    -e "s|/var/www/staging|$PROJECT_PATH|g" \
    -e "s|/usr/bin/php|$PHP_BINARY|g" \
    -e "s|user=www-data|user=$USER|g" \
    "$CONFIG_SOURCE" > "$CONFIG_DEST"

chown root:root "$CONFIG_DEST"
chmod 644 "$CONFIG_DEST"

echo -e "${GREEN}Configuration installed: $CONFIG_DEST${NC}"
echo ""

# Step 5: Reload supervisor configuration
echo -e "${YELLOW}Step 5: Reloading supervisor configuration...${NC}"

supervisorctl reread
supervisorctl update

echo -e "${GREEN}Supervisor configuration reloaded${NC}"
echo ""

# Step 6: Start workers
echo -e "${YELLOW}Step 6: Starting queue workers...${NC}"

if [ "$ENVIRONMENT" == "staging" ]; then
    supervisorctl start microweber-workers-staging:*
else
    supervisorctl start microweber-workers:*
fi

echo -e "${GREEN}Queue workers started${NC}"
echo ""

# Step 7: Verify status
echo -e "${YELLOW}Step 7: Verifying worker status...${NC}"
echo ""

supervisorctl status | grep microweber

echo ""
echo -e "${GREEN}================================================${NC}"
echo -e "${GREEN}Setup completed successfully!${NC}"
echo -e "${GREEN}================================================${NC}"
echo ""
echo "Useful commands:"
echo "  supervisorctl status              - Check worker status"
echo "  supervisorctl restart microweber-workers:*  - Restart all workers"
echo "  supervisorctl stop microweber-workers:*     - Stop all workers"
echo "  supervisorctl start microweber-workers:*    - Start all workers"
echo "  tail -f $LOG_DIR/worker.log      - View worker logs"
echo ""
echo "To uninstall workers:"
echo "  supervisorctl stop microweber-workers:*"
echo "  rm $CONFIG_DEST"
echo "  supervisorctl update"
