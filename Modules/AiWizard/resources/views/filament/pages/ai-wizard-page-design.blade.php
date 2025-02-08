<x-filament-panels::page>
    <div class="space-y-6">
        {{ $this->form }}

        @php
            $sections = $this->record->content_data['processed_sections'] ?? [];
            if(is_string($sections)) {
                $sections = json_decode($sections, true);
            }
        @endphp



        @if(!empty($sections ?? []))
            <div class="space-y-4">
                <h3 class="text-lg font-medium">Processed Sections</h3>

                @foreach($sections as $index => $section)
                    <div class="p-4 bg-white rounded-lg shadow">
                        <h4 class="font-medium mb-2">Section {{ $index + 1 }}</h4>

                        <div class="space-y-4">
                            <div>
                                <h5 class="text-sm font-medium text-gray-500">Markdown</h5>
                                <pre class="mt-1 p-2 bg-gray-50 rounded text-sm">{{ $section['markdown'] }}</pre>
                            </div>

                            <div>
                                <h5 class="text-sm font-medium text-gray-500">HTML Preview</h5>
                                <div class="mt-1 p-4 border rounded bg-white prose max-w-none">
                                    {!! $section['html'] !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-filament-panels::page>
