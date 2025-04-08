<?php

namespace Modules\Audio\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use MicroweberPackages\Option\Models\ModuleOption;
use MicroweberPackages\Option\Models\Option;
use Modules\Audio\Filament\AudioModuleSettings;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class AudioModuleSettingsFilamentTest extends TestCase
{

    #[Test]
    public function testAudioModuleSettingsForm(): void
    {
        $moduleId = 'module-id-test-' . uniqid();
        $moduleType = 'audio';

        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

        $params = [
            'params' => [
                'id' => $moduleId,
                'type' => $moduleType
            ]
        ];

        Livewire::test(AudioModuleSettings::class)
            ->set($params)
            ->assertFormFieldExists('options.data-audio-source')
            ->assertFormFieldExists('options.data-audio-upload')
            ->assertFormFieldExists('options.data-audio-url');

        // Test valid file upload
        $testFile = UploadedFile::fake()->create('test-audio.mp3', 100, 'audio/mpeg');
        
        Livewire::test(AudioModuleSettings::class)
            ->set($params)
            ->fillForm([
                'options.data-audio-source' => 'file',
                'options.data-audio-upload' => $testFile
            ])
            ->call('save')
            ->assertHasNoErrors()
            ->assertNotified();

        $this->assertDatabaseHas('options', [
            'option_group' => $moduleId,
            'module' => $moduleType,
            'option_key' => 'data-audio-source',
            'option_value' => 'file'
        ]);

        // Test invalid file upload
        $invalidFile = UploadedFile::fake()->create('invalid-file.txt', 100, 'text/plain');
        
        Livewire::test(AudioModuleSettings::class)
            ->set($params)
            ->fillForm([
                'options.data-audio-source' => 'file',
                'options.data-audio-upload' => $invalidFile
            ])
            ->call('save')
            ->assertHasErrors(['options.data-audio-upload']);

        // Test valid form submission with URL source
        $urlData = [
            'options.data-audio-source' => 'url',
            'options.data-audio-url' => 'https://example.com/audio.mp3'
        ];

        Livewire::test(AudioModuleSettings::class)
            ->set($params)
            ->fillForm($urlData)
            ->assertFormSet($urlData)
            ->call('save')
            ->assertHasNoActionErrors()
            ->assertHasNoFormErrors()
            ->assertNotified();

        $this->assertDatabaseHas('options', [
            'option_group' => $moduleId,
            'module' => $moduleType,
            'option_key' => 'data-audio-source',
            'option_value' => 'url'
        ]);
        $this->assertDatabaseHas('options', [
            'option_group' => $moduleId,
            'module' => $moduleType,
            'option_key' => 'data-audio-url',
            'option_value' => 'https://example.com/audio.mp3'
        ]);

        // Verify invalid uploads aren't saved
        $invalidUploads = Option::where('option_group', $moduleId)
            ->where('module', $moduleType)
            ->where('option_key', 'data-audio-upload')
            ->whereNotIn('option_value', ['', null])
            ->count();
        $this->assertEquals(0, $invalidUploads, 'Invalid upload data was saved to database');

        // Verify only source type was saved, not file content
        $savedUpload = Option::where('option_group', $moduleId)
            ->where('module', $moduleType)
            ->where('option_key', 'data-audio-upload')
            ->first();
            
        $this->assertTrue(
            empty($savedUpload->option_value) || 
            !file_exists($savedUpload->option_value),
            'Actual file content was saved'
        );

        // Verify temporary files are cleaned up
        if ($savedUpload && $savedUpload->option_value) {
            $this->assertFalse(
                file_exists($savedUpload->option_value),
                'Temporary file was not cleaned up'
            );
        }


        // Test form submission with URL
        $data = [
            'options.data-audio-source' => 'url',
            'options.data-audio-url' => 'https://www.example.com/audio.mp3',
        ];
        Livewire::test(AudioModuleSettings::class)
            ->set($params)
            ->fillForm($data)
            ->assertFormSet([
                'options.data-audio-source' => 'url',
                'options.data-audio-url' => 'https://www.example.com/audio.mp3',
            ])
            ->call('save')
            ->assertHasNoActionErrors()
            ->assertHasNoFormErrors()
            ->assertNotified();

        $this->assertDatabaseHas('options', ['option_group' => $moduleId, 'module' => $moduleType, 'option_key' => 'data-audio-source', 'option_value' => 'url']);


        //clean up
        ModuleOption::where('option_group', $moduleId)->where('module', $moduleType)->delete();
        $this->assertDatabaseMissing('options', ['option_group' => $moduleId, 'module' => $moduleType]);

    }
}
