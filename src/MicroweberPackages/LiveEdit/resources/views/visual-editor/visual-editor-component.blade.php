<div class="mw-visual-editor" x-data="visualEditor()" x-init="init()">
    <div class="mw-visual-editor-container" :class="{ 'is-dragging': isDragging }">
        <!-- Editor Toolbar -->
        <div class="mw-visual-editor-toolbar">
            <div class="toolbar-section">
                <h3 class="toolbar-title">Visual Editor</h3>
            </div>

            <div class="toolbar-section toolbar-actions">
                <button
                    type="button"
                    class="toolbar-btn toolbar-btn-primary"
                    wire:click="toggleBlockLibrary"
                >
                    <span>Add Block</span>
                </button>

                <button
                    type="button"
                    class="toolbar-btn"
                    wire:click="loadBlocks"
                    wire:loading.attr="disabled"
                >
                    <span>Refresh</span>
                </button>
            </div>
        </div>

        <!-- Block Library Panel -->
        @if($showBlockLibrary)
        <div class="mw-block-library">
            <div class="block-library-header">
                <h4>Add Block</h4>
                <button type="button" class="close-btn" wire:click="toggleBlockLibrary">
                    X
                </button>
            </div>

            <div class="block-library-grid">
                @foreach($availableBlockTypes as $blockType)
                <div
                    class="block-type-card"
                    wire:click="addBlock('{{ $blockType['type'] }}')"
                    draggable="true"
                >
                    <div class="block-type-icon">
                        <span>{{ substr($blockType['title'], 0, 2) }}</span>
                    </div>
                    <div class="block-type-info">
                        <h5>{{ $blockType['title'] }}</h5>
                        <p>{{ $blockType['description'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Main Editor Area -->
        <div class="mw-editor-main">
            <!-- Blocks Canvas -->
            <div class="mw-blocks-canvas">
                @if(empty($blocks))
                <div class="empty-state">
                    <h3>No blocks yet</h3>
                    <p>Click "Add Block" to start building your content</p>
                </div>
                @else
                <div class="blocks-list">
                    @foreach($blocks as $index => $block)
                    <div
                        class="mw-block-item {{ $selectedBlockId === $block['id'] ? 'is-selected' : '' }}"
                        data-block-id="{{ $block['id'] }}"
                        data-block-position="{{ $block['position'] }}"
                        wire:click="handleBlockSelected('{{ $block['id'] }}')"
                    >
                        <!-- Block Content Preview -->
                        <div class="block-preview">
                            <div class="block-type-badge">
                                {{ $block['title'] }}
                            </div>
                            <div class="block-content-preview">
                                {{ Str::limit(strip_tags($block['content']), 100) }}
                            </div>
                        </div>

                        <!-- Block Actions -->
                        <div class="block-actions">
                            <button
                                type="button"
                                class="action-btn"
                                wire:click.stop="moveBlockUp('{{ $block['id'] }}')"
                                title="Move Up"
                            >
                                Up
                            </button>
                            <button
                                type="button"
                                class="action-btn"
                                wire:click.stop="moveBlockDown('{{ $block['id'] }}')"
                                title="Move Down"
                            >
                                Down
                            </button>
                            <button
                                type="button"
                                class="action-btn"
                                wire:click.stop="handleBlockDuplicated('{{ $block['id'] }}')"
                                title="Duplicate"
                            >
                                Copy
                            </button>
                            <button
                                type="button"
                                class="action-btn action-btn-danger"
                                wire:click.stop="handleBlockDeleted('{{ $block['id'] }}')"
                                title="Delete"
                            >
                                Del
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Block Settings Panel -->
            @if($selectedBlockId)
            <div class="mw-block-settings-panel">
                @php
                $selectedBlock = collect($blocks)->firstWhere('id', $selectedBlockId);
                @endphp

                @if($selectedBlock)
                <div class="settings-panel-header">
                    <h4>{{ $selectedBlock['title'] }} Settings</h4>
                    <button
                        type="button"
                        class="close-btn"
                        wire:click="handleBlockSelected('')"
                    >
                        X
                    </button>
                </div>

                <div class="settings-panel-content">
                    <!-- Block Type Specific Settings -->
                    @if($selectedBlock['type'] === 'text')
                    <div class="setting-group">
                        <label>Alignment</label>
                        <div class="btn-group">
                            <button type="button" class="btn" wire:click="updateBlockSettings('{{ $selectedBlock['id'] }}', {'align': 'left'})">Left</button>
                            <button type="button" class="btn" wire:click="updateBlockSettings('{{ $selectedBlock['id'] }}', {'align': 'center'})">Center</button>
                            <button type="button" class="btn" wire:click="updateBlockSettings('{{ $selectedBlock['id'] }}', {'align': 'right'})">Right</button>
                        </div>
                    </div>
                    @endif

                    @if($selectedBlock['type'] === 'image')
                    <div class="setting-group">
                        <label>Image Width</label>
                        <input
                            type="text"
                            class="form-input"
                            value="{{ $selectedBlock['settings']['width'] ?? '100%' }}"
                            wire:blur="updateBlockSettings('{{ $selectedBlock['id'] }}', {'width': $event.target.value})"
                        >
                    </div>
                    @endif

                    @if($selectedBlock['type'] === 'heading')
                    <div class="setting-group">
                        <label>Heading Level</label>
                        <select class="form-select" wire:change="updateBlockSettings('{{ $selectedBlock['id'] }}', {'level': $event.target.value})">
                            <option value="h1" @if(($selectedBlock['settings']['level'] ?? 'h2') === 'h1') selected @endif>H1</option>
                            <option value="h2" @if(($selectedBlock['settings']['level'] ?? 'h2') === 'h2') selected @endif>H2</option>
                            <option value="h3" @if(($selectedBlock['settings']['level'] ?? 'h2') === 'h3') selected @endif>H3</option>
                            <option value="h4" @if(($selectedBlock['settings']['level'] ?? 'h2') === 'h4') selected @endif>H4</option>
                        </select>
                    </div>
                    @endif

                    <!-- Common Settings -->
                    <div class="setting-group">
                        <label>Custom CSS Classes</label>
                        <input
                            type="text"
                            class="form-input"
                            placeholder="e.g., my-custom-class"
                            wire:blur="updateBlockSettings('{{ $selectedBlock['id'] }}', {'css_class': $event.target.value})"
                        >
                    </div>

                    <div class="setting-group">
                        <label>Background Color</label>
                        <input
                            type="color"
                            class="form-input"
                            wire:change="updateBlockSettings('{{ $selectedBlock['id'] }}', {'bg_color': $event.target.value})"
                        >
                    </div>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Notifications -->
    @if(session()->has('message'))
    <div class="notification notification-success">
        {{ session('message') }}
    </div>
    @endif

    @if(session()->has('error'))
    <div class="notification notification-error">
        {{ session('error') }}
    </div>
    @endif

    @push('styles')
    <style>
    .mw-visual-editor {
        height: 100%;
        display: flex;
        flex-direction: column;
        background: #f8fafc;
    }

    .mw-visual-editor-container {
        display: flex;
        height: 100%;
        position: relative;
    }

    .mw-visual-editor-container.is-dragging .mw-block-item {
        cursor: grabbing;
    }

    /* Toolbar */
    .mw-visual-editor-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        background: #ffffff;
        border-bottom: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        z-index: 10;
    }

    .toolbar-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .toolbar-actions {
        display: flex;
        gap: 0.5rem;
    }

    .toolbar-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .toolbar-btn:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }

    .toolbar-btn-primary {
        background: #3b82f6;
        color: #ffffff;
        border-color: #3b82f6;
    }

    .toolbar-btn-primary:hover {
        background: #2563eb;
        border-color: #2563eb;
    }

    /* Block Library */
    .mw-block-library {
        width: 320px;
        background: #ffffff;
        border-right: 1px solid #e2e8f0;
        box-shadow: 4px 0 6px -1px rgba(0, 0, 0, 0.1);
        z-index: 20;
        display: flex;
        flex-direction: column;
    }

    .block-library-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .block-library-header h4 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
    }

    .close-btn {
        background: none;
        border: none;
        padding: 0.25rem;
        cursor: pointer;
        color: #64748b;
        border-radius: 0.25rem;
    }

    .close-btn:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .block-library-grid {
        flex: 1;
        overflow-y: auto;
        padding: 1rem;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }

    .block-type-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 0.5rem;
        cursor: grab;
        transition: all 0.15s ease;
        text-align: center;
    }

    .block-type-card:hover {
        border-color: #3b82f6;
        background: #f8fafc;
    }

    .block-type-card:active {
        cursor: grabbing;
    }

    .block-type-icon {
        color: #3b82f6;
        margin-bottom: 0.5rem;
        font-weight: bold;
        font-size: 1.5rem;
    }

    .block-type-info h5 {
        margin: 0 0 0.25rem 0;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .block-type-info p {
        margin: 0;
        font-size: 0.75rem;
        color: #64748b;
    }

    /* Main Editor */
    .mw-editor-main {
        flex: 1;
        display: flex;
        overflow: hidden;
    }

    .mw-blocks-canvas {
        flex: 1;
        padding: 2rem;
        overflow-y: auto;
        background: #f8fafc;
    }

    /* Empty State */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 4rem 2rem;
        text-align: center;
        color: #64748b;
    }

    .empty-state h3 {
        margin: 0 0 0.5rem 0;
        color: #1e293b;
    }

    .empty-state p {
        margin: 0;
    }

    /* Blocks List */
    .blocks-list {
        max-width: 800px;
        margin: 0 auto;
    }

    /* Block Item */
    .mw-block-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 1rem;
        background: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: 0.5rem;
        margin-bottom: 0.75rem;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .mw-block-item:hover {
        border-color: #3b82f6;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .mw-block-item.is-selected {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Block Preview */
    .block-preview {
        flex: 1;
        min-width: 0;
    }

    .block-type-badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        background: #eff6ff;
        color: #3b82f6;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 0.25rem;
        margin-bottom: 0.5rem;
    }

    .block-content-preview {
        color: #64748b;
        font-size: 0.875rem;
        line-height: 1.5;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Block Actions */
    .block-actions {
        display: flex;
        gap: 0.25rem;
        opacity: 0;
        transition: opacity 0.15s ease;
    }

    .mw-block-item:hover .block-actions {
        opacity: 1;
    }

    .action-btn {
        padding: 0.375rem;
        border: none;
        background: transparent;
        color: #94a3b8;
        border-radius: 0.25rem;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .action-btn:hover {
        background: #f1f5f9;
        color: #64748b;
    }

    .action-btn-danger:hover {
        background: #fee2e2;
        color: #dc2626;
    }

    /* Settings Panel */
    .mw-block-settings-panel {
        width: 300px;
        background: #ffffff;
        border-left: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .settings-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .settings-panel-header h4 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
    }

    .settings-panel-content {
        flex: 1;
        overflow-y: auto;
        padding: 1rem;
    }

    .setting-group {
        margin-bottom: 1.5rem;
    }

    .setting-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
    }

    .form-input,
    .form-select {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        transition: border-color 0.15s ease;
    }

    .form-input:focus,
    .form-select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .btn-group {
        display: flex;
        gap: 0.25rem;
    }

    .btn-group .btn {
        flex: 1;
        padding: 0.5rem;
        border: 1px solid #d1d5db;
        background: #ffffff;
        border-radius: 0.375rem;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-group .btn:hover {
        background: #f3f4f6;
    }

    /* Notifications */
    .notification {
        position: fixed;
        bottom: 1rem;
        right: 1rem;
        z-index: 50;
        padding: 0.75rem 1rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 500;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .notification-success {
        background: #10b981;
        color: #ffffff;
    }

    .notification-error {
        background: #dc2626;
        color: #ffffff;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .mw-block-library {
            width: 100%;
        }

        .mw-block-settings-panel {
            width: 100%;
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }

        .mw-block-settings-panel.is-visible {
            transform: translateX(0);
        }
    }
    </style>
    @endpush
</div>
