<?php

namespace MicroweberPackages\View;


use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\LivewireTagCompiler;

class ViewServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->registerTagCompiler();

        Blade::directive('module', [MicroweberBladeDirectives::class, 'module']);

        Blade::directive('mwHeaderScripts', function () {


            $header = mw()->template->head(true);

            $template_headers_src_callback = mw()->template->head_callback();
            if (is_array($template_headers_src_callback) and !empty($template_headers_src_callback)) {
                foreach ($template_headers_src_callback as $template_headers_src_callback_str) {
                    if (is_string($template_headers_src_callback_str)) {
                        $header = $header . "\n" . $template_headers_src_callback_str;
                    }
                }
            }

            return $header;

        });

        Blade::directive('mwFooterScripts', function () {
            $footer = mw()->template->foot(true);
            $template_footer_src_callback = mw()->template->foot_callback();
            if (is_array($template_footer_src_callback) and !empty($template_footer_src_callback)) {
                foreach ($template_footer_src_callback as $template_footer_src_callback_str) {
                    if (is_string($template_footer_src_callback_str)) {
                        $footer = $footer . "\n" . $template_footer_src_callback_str;
                    }
                }
            }

            return $footer;

        });
    }

    protected function registerTagCompiler()
    {
        if (method_exists($this->app['blade.compiler'], 'precompiler')) {
            $this->app['blade.compiler']->precompiler(function ($string) {
                return app(MicroweberModuleTagCompiler::class)->compile($string);
            });
        }
    }


}
