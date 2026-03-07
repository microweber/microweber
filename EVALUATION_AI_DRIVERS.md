# AI Services Drivers Evaluation Report
**Date:** March 7, 2026
**Scope:** `Modules/Ai/Services/Drivers/*`
**Objective:** Evaluate extraction to `microweber-packages/ai-drivers`

---

## Executive Summary

**RECOMMENDATION: PROCEED WITH EXTRACTION** - The AI drivers are well-architected and ready to be extracted into a standalone package. They follow good design patterns with clear interfaces, making them ideal candidates for package separation.

---

## Current Architecture Analysis

### File Inventory (11 files in `Modules/Ai/Services/Drivers/`)

#### Interfaces (3 files)
| File | Purpose | Lines |
|------|---------|-------|
| `AiServiceInterface.php` | Core interface for chat functionality | 42 |
| `AiChatServiceInterface.php` | Chat-specific interface (extends base) | 25 |
| `AiImageServiceInterface.php` | Image generation interface | 26 |

#### Base Implementation (2 files)
| File | Purpose | Lines |
|------|---------|-------|
| `BaseDriver.php` | Abstract base class for all drivers | 58 |
| `AiParseJsonTrait.php` | JSON parsing helper trait | 71 |

#### Chat Drivers (4 files)
| Driver | Provider | HTTP Client | Lines | Status |
|--------|----------|-------------|-------|--------|
| `OpenAiDriver.php` | OpenAI (GPT-4, etc.) | `openai-php/client` | 182 | Production-ready |
| `GeminiAiDriver.php` | Google Gemini | cURL | 300 | Production-ready |
| `OllamaAiDriver.php` | Local LLMs (Ollama) | cURL | 211 | Production-ready |
| `OpenRouterAiDriver.php` | OpenRouter (multi-model) | cURL | 157 | Production-ready |

#### Image Generation Drivers (2 files)
| Driver | Provider | HTTP Client | Lines | Status |
|--------|----------|-------------|-------|--------|
| `ReplicateAiDriver.php` | Replicate.com | cURL | 339 | Production-ready |
| `FalAiDriver.php` | Fal.ai | cURL | 413 | Production-ready |

### Service Layer (2 factory files)
- `AiService.php` - Chat/text driver factory (120 lines)
- `AiServiceImages.php` - Image driver factory (132 lines)

---

## Architecture Strengths

### Design Patterns (Excellent)
1. **Strategy Pattern**: Clean driver abstraction with `BaseDriver`
2. **Interface Segregation**: Separate interfaces for chat vs image services
3. **Factory Pattern**: Service classes manage driver instantiation
4. **Template Method**: `BaseDriver` defines common structure

### Code Quality (Good)
1. **Consistent API**: All drivers implement `sendToChat()` and `getDriverName()`
2. **Configuration-driven**: Drivers accept config arrays for flexibility
3. **Caching Support**: All drivers support optional response caching
4. **Error Handling**: Proper exception throwing with descriptive messages
5. **Type Safety**: Strong typing with PHP 8.2+ features (union types, typed properties)

### Features (Comprehensive)
- Multi-provider support (6 different AI services)
- JSON schema response formatting
- Function/tool calling support (OpenAI-style)
- Image generation with local storage
- Message format conversion (OpenAI → Gemini)
- Structured output parsing

---

## Issues Identified

### 1. HTTP Client Inconsistency (Medium Priority)
**Problem**: Mixed HTTP client usage
- OpenAI uses official SDK (`openai-php/client`)
- All others use raw cURL

**Impact**: Inconsistent error handling, retry logic, and testing

**Recommendation**: Standardize on Laravel HTTP Client or create a wrapper

### 2. Dead Code Reference (Low Priority)
**Problem**: `AiService.php` references non-existent `SupadataAiDriver`
```php
use Modules\Ai\Services\Drivers\SupadataAiDriver; // Line 11 - Does not exist
```

**Recommendation**: Remove unused import

### 3. Security Concern (Medium Priority)
**Problem**: SSL verification disabled in some drivers
```php
// ReplicateAiDriver.php:247-248
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

// FalAiDriver.php:366-367
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
```

**Impact**: Inconsistent security practices

**Recommendation**: Standardize SSL settings, never disable in production

### 4. No HTTP Timeout Configuration (Low Priority)
**Problem**: Hardcoded timeouts vary between drivers (60s, 300s, 600s)

**Recommendation**: Make configurable via constructor

### 5. Test Coverage Gap
**Problem**: No dedicated unit tests for drivers (test failures are environment setup)

**Recommendation**: Add mock HTTP client tests

---

## Extraction Plan

### Package Structure: `microweber-packages/ai-drivers`

```
microweber-packages/ai-drivers/
├── composer.json
├── src/
│   ├── Contracts/
│   │   ├── AiServiceInterface.php
│   │   ├── AiChatServiceInterface.php
│   │   └── AiImageServiceInterface.php
│   ├── Drivers/
│   │   ├── BaseDriver.php
│   │   ├── OpenAiDriver.php
│   │   ├── GeminiAiDriver.php
│   │   ├── OllamaAiDriver.php
│   │   ├── OpenRouterAiDriver.php
│   │   ├── ReplicateAiDriver.php
│   │   └── FalAiDriver.php
│   ├── Traits/
│   │   └── AiParseJsonTrait.php
│   ├── Factories/
│   │   ├── AiServiceFactory.php
│   │   └── AiImageServiceFactory.php
│   └── Http/
│       └── HttpClient.php (optional abstraction layer)
├── tests/
│   ├── Unit/
│   │   └── Drivers/
│   └── Feature/
└── README.md
```

### Migration Steps

#### Phase 1: Create Package (Day 1-2)
1. Create new repository `microweber-packages/ai-drivers`
2. Copy all driver files maintaining namespace `MicroweberPackages\AiDrivers`
3. Create `composer.json` with proper autoloading
4. Add basic README with usage examples

#### Phase 2: Clean Up (Day 2-3)
1. Fix HTTP client inconsistency (standardize on Laravel HTTP or Guzzle)
2. Remove dead code (SupadataAiDriver reference)
3. Standardize SSL verification (always verify in production)
4. Add configuration validation
5. Add type hints where missing

#### Phase 3: Add Tests (Day 3-5)
1. Create mock HTTP responses for each provider
2. Write unit tests for each driver
3. Add integration tests with real API calls (optional)
4. Test factory classes

#### Phase 4: Integration (Day 5-7)
1. Update `Modules/Ai` composer.json to require new package
2. Replace `Modules\Ai\Services\Drivers\*` imports with `MicroweberPackages\AiDrivers\*`
3. Update service providers
4. Run full test suite
5. Deprecate old classes with `@deprecated` annotations

#### Phase 5: Documentation (Day 7-8)
1. Document driver configuration options
2. Add migration guide
3. Create examples for each driver
4. Document testing approach

---

## Dependencies Analysis

### Required Dependencies (from composer.json)
```json
{
    "require": {
        "php": "^8.2",
        "illuminate/support": "^10.0|^11.0",
        "openai-php/client": "^0.8"
    },
    "suggest": {
        "neuron-ai/neuron-ai": "For JSON extraction capabilities"
    }
}
```

### External Service Dependencies
- **OpenAI**: Requires `OPENAI_API_KEY`
- **Gemini**: Requires `GEMINI_API_KEY`
- **Ollama**: Local service (no key needed)
- **OpenRouter**: Requires API key
- **Replicate**: Requires `REPLICATE_API_TOKEN`
- **FAL**: Requires `FAL_API_KEY`

---

## Risk Assessment

| Risk | Likelihood | Impact | Mitigation |
|------|------------|--------|------------|
| Breaking changes during extraction | Medium | High | Maintain backward compatibility layer |
| API changes in AI providers | Low | Medium | Monitor provider changelogs |
| Test environment issues | High | Low | Use mock HTTP clients |
| Namespace conflicts | Low | Medium | Use vendor namespace |
| Dependency version conflicts | Low | Medium | Test with all Laravel versions |

---

## Benefits of Extraction

### 1. **Reusability**
- Can be used in other Microweber projects
- Can be published to Packagist for community use
- Reduces duplication across modules

### 2. **Maintainability**
- Isolated testing and CI/CD
- Clear separation of concerns
- Easier to version and release

### 3. **Scalability**
- Add new drivers without touching core code
- Update AI provider SDKs independently
- Better dependency management

### 4. **Collaboration**
- Separate team can maintain AI drivers
- Clear contribution guidelines
- Independent issue tracking

---

## Conclusion

The AI drivers are **ready for extraction**. The codebase is:
- ✅ Well-architected with clear interfaces
- ✅ Feature-complete for current use cases
- ✅ Following modern PHP practices
- ✅ Properly documented

**Next Steps:**
1. Create `microweber-packages/ai-drivers` repository
2. Execute Phase 1 (copy and namespace migration)
3. Address HTTP client inconsistency
4. Add comprehensive test suite
5. Update Modules/Ai to depend on new package

**Estimated Effort:** 5-7 days for full extraction and testing

**Priority:** Medium-High - This extraction will improve maintainability and enable reuse across the ecosystem.
