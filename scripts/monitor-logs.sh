#!/bin/bash

# Log Monitoring Script for Microweber
# Monitors logs for 24 hours after deployment

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Configuration
MONITOR_DURATION="${MONITOR_DURATION:-24h}"
LOG_FILE="${LOG_FILE:-storage/logs/laravel.log}"
ERROR_LOG="storage/logs/monitor-errors-$(date +%Y%m%d-%H%M%S).log"
ALERT_THRESHOLD="${ALERT_THRESHOLD:-10}"
PID_FILE="storage/logs/monitor.pid"

# Error patterns to monitor
ERROR_PATTERNS=(
    "Fatal error"
    "Uncaught Exception"
    "SQLSTATE"
    "ErrorException"
    "syntax error"
    "Memory limit"
    "Class not found"
    "Call to undefined"
    "Route not found"
    "View not found"
    "File not found"
    "Permission denied"
    "Connection refused"
    "Connection timed out"
    "504 Gateway Timeout"
    "502 Bad Gateway"
    "500 Internal Server Error"
)

# Initialize counters
declare -A error_counts
for pattern in "${ERROR_PATTERNS[@]}"; do
    error_counts[$pattern]=0
done

total_errors=0
start_time=$(date +%s)

# Function to log with timestamp
log_with_time() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1"
}

# Function to send alert (customize as needed)
send_alert() {
    local message="$1"
    echo -e "${RED}ALERT: $message${NC}"

    # Log to file
    log_with_time "ALERT: $message" >> "$ERROR_LOG"

    # Optional: Send email notification
    if [ -n "$ALERT_EMAIL" ]; then
        echo "$message" | mail -s "Microweber Staging Alert" "$ALERT_EMAIL" 2>/dev/null || true
    fi

    # Optional: Send Slack notification
    if [ -n "$SLACK_WEBHOOK_URL" ]; then
        curl -s -X POST -H 'Content-type: application/json' \
            --data "{\"text\":\"$message\"}" \
            "$SLACK_WEBHOOK_URL" 2>/dev/null || true
    fi
}

# Function to check if monitoring should continue
should_continue() {
    local current_time=$(date +%s)
    local elapsed=$((current_time - start_time))
    local max_duration=$(echo "$MONITOR_DURATION" | sed 's/h/*3600/' | sed 's/m/*60/' | sed 's/d/*86400/' | bc)

    [ $elapsed -lt $max_duration ]
}

# Function to format elapsed time
format_elapsed() {
    local elapsed="$1"
    local hours=$((elapsed / 3600))
    local minutes=$(((elapsed % 3600) / 60))
    local seconds=$((elapsed % 60))
    printf "%02d:%02d:%02d" $hours $minutes $seconds
}

# Main monitoring function
monitor_logs() {
    echo -e "${GREEN}================================${NC}"
    echo -e "${GREEN}Log Monitoring Started${NC}"
    echo -e "${GREEN}================================${NC}"
    echo "Started at: $(date)"
    echo "Duration: $MONITOR_DURATION"
    echo "Log file: $LOG_FILE"
    echo "Error log: $ERROR_LOG"
    echo "Alert threshold: $ALERT_THRESHOLD errors"
    echo ""
    echo -e "${YELLOW}Monitoring for errors...${NC}"
    echo -e "${BLUE}Press Ctrl+C to stop monitoring${NC}"
    echo ""

    # Create error log file
    touch "$ERROR_LOG"
    echo "Log Monitoring Session - Started: $(date)" > "$ERROR_LOG"
    echo "=======================================" >> "$ERROR_LOG"

    # Save PID for external control
    echo $$ > "$PID_FILE"

    while should_continue; do
        if [ -f "$LOG_FILE" ]; then
            # Check for new errors since last check
            for pattern in "${ERROR_PATTERNS[@]}"; do
                # Count new occurrences
                new_errors=$(tail -n 100 "$LOG_FILE" 2>/dev/null | grep -c "$pattern" || echo "0")

                if [ "$new_errors" -gt "${error_counts[$pattern]}" ]; then
                    delta=$((new_errors - error_counts[$pattern]))
                    error_counts[$pattern]=$new_errors
                    total_errors=$((total_errors + delta))

                    # Log the error
                    log_with_time "Found $delta new '$pattern' error(s). Total: $new_errors" >> "$ERROR_LOG"

                    # Show error details
                    echo -e "${RED}[$pattern] Found $delta new occurrence(s)${NC}"

                    # Alert if threshold exceeded
                    if [ $total_errors -ge $ALERT_THRESHOLD ]; then
                        send_alert "Error threshold exceeded! Total errors: $total_errors. Pattern: $pattern"
                    fi
                fi
            done
        fi

        # Show progress every minute
        current_time=$(date +%s)
        elapsed=$((current_time - start_time))
        if [ $((elapsed % 60)) -eq 0 ] && [ $elapsed -gt 0 ]; then
            echo -e "${YELLOW}Progress: $(format_elapsed $elapsed) elapsed | Total errors: $total_errors${NC}"
        fi

        # Sleep for 5 seconds
        sleep 5
    done

    # Generate summary report
    echo ""
    echo -e "${GREEN}================================${NC}"
    echo -e "${GREEN}Monitoring Complete${NC}"
    echo -e "${GREEN}================================${NC}"
    echo "Ended at: $(date)"
    echo "Total duration: $(format_elapsed $(( $(date +%s) - start_time )))"
    echo "Total errors found: $total_errors"
    echo ""
    echo "Error breakdown:"
    for pattern in "${ERROR_PATTERNS[@]}"; do
        if [ "${error_counts[$pattern]}" -gt 0 ]; then
            echo "  - $pattern: ${error_counts[$pattern]}"
        fi
    done
    echo ""
    echo "Detailed log: $ERROR_LOG"

    # Save summary to error log
    echo "" >> "$ERROR_LOG"
    echo "=======================================" >> "$ERROR_LOG"
    echo "Monitoring Complete - Ended: $(date)" >> "$ERROR_LOG"
    echo "Total Errors: $total_errors" >> "$ERROR_LOG"

    # Remove PID file
    rm -f "$PID_FILE"

    # Return appropriate exit code
    if [ $total_errors -eq 0 ]; then
        echo -e "${GREEN}No errors found during monitoring period!${NC}"
        exit 0
    else
        echo -e "${YELLOW}$total_errors error(s) found during monitoring period${NC}"
        exit 1
    fi
}

# Function to show current status
show_status() {
    if [ -f "$PID_FILE" ]; then
        local pid=$(cat "$PID_FILE")
        if ps -p "$pid" > /dev/null 2>&1; then
            echo -e "${GREEN}Monitoring is running (PID: $pid)${NC}"
            echo "Start time: $(stat -c %y "$PID_FILE" 2>/dev/null || echo "Unknown")"
            if [ -f "$ERROR_LOG" ]; then
                echo "Error log: $ERROR_LOG"
                echo "Current error count: $(grep -c "Found" "$ERROR_LOG" 2>/dev/null || echo "0")"
            fi
        else
            echo -e "${YELLOW}Monitoring is not running (stale PID file)${NC}"
            rm -f "$PID_FILE"
        fi
    else
        echo -e "${RED}Monitoring is not running${NC}"
    fi
}

# Function to stop monitoring
stop_monitoring() {
    if [ -f "$PID_FILE" ]; then
        local pid=$(cat "$PID_FILE")
        if ps -p "$pid" > /dev/null 2>&1; then
            echo -e "${YELLOW}Stopping monitoring (PID: $pid)...${NC}"
            kill "$pid" 2>/dev/null || true
            rm -f "$PID_FILE"
            echo -e "${GREEN}Monitoring stopped${NC}"
        else
            echo -e "${YELLOW}Monitoring process not found${NC}"
            rm -f "$PID_FILE"
        fi
    else
        echo -e "${RED}Monitoring is not running${NC}"
    fi
}

# Handle command line arguments
case "${1:-monitor}" in
    status)
        show_status
        ;;
    stop)
        stop_monitoring
        ;;
    monitor|start)
        monitor_logs
        ;;
    *)
        echo "Usage: $0 {monitor|start|status|stop}"
        echo ""
        echo "Commands:"
        echo "  monitor/start - Start monitoring logs for 24 hours"
        echo "  status        - Show current monitoring status"
        echo "  stop          - Stop monitoring"
        echo ""
        echo "Environment variables:"
        echo "  MONITOR_DURATION  - Monitoring duration (default: 24h)"
        echo "  LOG_FILE         - Path to log file (default: storage/logs/laravel.log)"
        echo "  ALERT_THRESHOLD  - Error threshold for alerts (default: 10)"
        echo "  ALERT_EMAIL      - Email for alerts (optional)"
        echo "  SLACK_WEBHOOK_URL - Slack webhook for alerts (optional)"
        exit 1
        ;;
esac
