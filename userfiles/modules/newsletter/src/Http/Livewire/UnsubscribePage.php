<?php
namespace MicroweberPackages\Modules\Newsletter\Http\Livewire;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Filament\Pages\Page;

class UnsubscribePage extends Component
{

    public function unsubscribe()
    {
        $email = request()->get('email');
        if (empty($email)) {
            return redirect('/');
        }
        $findSubscriber = \MicroweberPackages\Modules\Newsletter\Models\NewsletterSubscriber::where('email', $email)->first();
        if (!$findSubscriber) {
            return redirect('/');
        }

        $findSubscriber->is_subscribed = 0;
        $findSubscriber->save();

        return redirect('/');
    }

    public function render()
    {
        return view('microweber-module-newsletter::livewire.unsubscribe');
    }

}
