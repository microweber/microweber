#!/bin/bash

# Filament v5 Migration Analysis Script
# Usage: ./analyze-filament-migration.sh [path]

PATH_TO_CHECK="${1:-.}"

echo "========================================"
echo "Filament v5 Migration Analysis"
echo "========================================"
echo ""
echo "Analyzing: $PATH_TO_CHECK"
echo ""

# Define patterns to check
declare -a PATTERNS=(
    "Filament\\\\\\\\Forms\\\\\\\\Components\\\\\\\\Section:Old Section imports:Filament\\\\\\\\Schemas\\\\\\\\Components\\\\\\\\Section"
    "Filament\\\\\\\\Tables\\\\\\\\Actions\\\\\\\\EditAction:Old EditAction imports:Filament\\\\\\\\Actions\\\\\\\\EditAction"
    "Filament\\\\\\\\Tables\\\\\\\\Actions\\\\\\\\DeleteAction:Old DeleteAction imports:Filament\\\\\\\\Actions\\\\\\\\DeleteAction"
    "Filament\\\\\\\\Tables\\\\\\\\Actions\\\\\\\\ViewAction:Old ViewAction imports:Filament\\\\\\\\Actions\\\\\\\\ViewAction"
    "Filament\\\\\\\\Resources\\\\\\\\Components\\\\\\\\Tab:Old Tab imports:Filament\\\\\\\\Schemas\\\\\\\\Components\\\\\\\\Tabs\\\\\\\\Tab"
    "filament-forms:::mw-filament::"
    '\$emit:Old Livewire events:\$dispatch'
    "wire:model\\\\.defer:Old wire:model.defer:wire:model"
    "HeroiconS:Solid icons (check):HeroiconO"
)

echo "Pattern Analysis:"
echo "-----------------"
echo ""

total_issues=0
results=()

for pattern_info in "${PATTERNS[@]}"; do
    IFS=':' read -r pattern description fix <<< "$pattern_info"
    
    # Count occurrences
    count=$(grep -r "$pattern" "$PATH_TO_CHECK" --include="*.php" --include="*.blade.php" 2>/dev/null | wc -l)
    
    if [ "$count" -gt 0 ]; then
        total_issues=$((total_issues + count))
        printf "%-45s: %4d occurrences\n" "$description" "$count"
        results+=("$description:$count:$fix")
    fi
done

echo ""
echo "========================================"
echo "Summary"
echo "========================================"
echo ""
echo "Total issues found: $total_issues"
echo ""

if [ "$total_issues" -eq 0 ]; then
    echo "✓ No migration issues detected!"
    echo "Your codebase is already Filament v5 compatible."
elif [ "$total_issues" -lt 50 ]; then
    echo "✓ Low migration effort required"
    echo "Run: vendor/bin/rector process --config=rector-filament.php"
elif [ "$total_issues" -lt 200 ]; then
    echo "⚠ Moderate migration effort required"
    echo "Recommended: Review each file manually after running Rector"
else
    echo "✗ High migration effort required"
    echo "Consider running module by module:"
    echo "  vendor/bin/rector process Modules/YourModule --config=rector-filament.php --dry-run"
fi

echo ""
echo "For detailed migration guide, see: docs/filament-migration.md"
echo ""
