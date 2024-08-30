<div class="ui_section">
    <h2>Grids</h2>
    <br>

    <style scoped="scoped">
        .ui_section_grids_example .mw-flex-row{
            margin:10px auto;
        }

        .ui_section_grids_example [class*="mw-flex-col"] [class*="box-"],
        .ui_section_grids_example [class*="mw-flex-col"] .box{
            background: #DADADA;
            min-height: 33px;
            line-height: 33px;
            text-align: center;
            color: white;
            width: 100%;
            padding: 10px;
        }
        .ui_section_grids_example [class*="mw-flex-col"] [class*="box-"] [class*="box-"],
        .ui_section_grids_example [class*="mw-flex-col"] .box [class*="box-"],
        .ui_section_grids_example [class*="mw-flex-col"] .box .box,
        .ui_section_grids_example [class*="mw-flex-col"] [class*="box-"] .box{
            background: #A8A8A8;
            padding: 0;
        }

        .box-large{
            height: 120px;
        }

    </style>

    <div class="ui_section_grids_example">
        <section class="page-section">
                <h2>Flex Grid (based on flexboxgrid.com)</h2>
                <p>Responsive modifiers enable specifying different column sizes, offsets, alignment and distribution at xs, sm, md &amp; lg viewport widths.</p>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-12 mw-flex-col-sm-3 mw-flex-col-md-2 mw-flex-col-lg-1">
                                <div class="box-row"></div>
                        </div>
                        <div class="mw-flex-col-xs-6 mw-flex-col-sm-6 mw-flex-col-md-8 mw-flex-col-lg-10">
                                <div class="box-row"></div>
                        </div>
                        <div class="mw-flex-col-xs-6 mw-flex-col-sm-3 mw-flex-col-md-2 mw-flex-col-lg-1">
                                <div class="box-row"></div>
                        </div>
                </div>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-12 mw-flex-col-sm-3 mw-flex-col-md-2 mw-flex-col-lg-1">
                                <div class="box-row"></div>
                        </div>
                        <div class="mw-flex-col-xs-12 mw-flex-col-sm-9 mw-flex-col-md-10 mw-flex-col-lg-11">
                                <div class="box-row"></div>
                        </div>
                </div>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-10 mw-flex-col-sm-6 mw-flex-col-md-8 mw-flex-col-lg-10">
                                <div class="box-row"></div>
                        </div>
                        <div class="mw-flex-col-xs-2 mw-flex-col-sm-6 mw-flex-col-md-4 mw-flex-col-lg-2">
                                <div class="box-row"></div>
                        </div>
                </div>
                <pre><code>&lt;div class="mw-flex-row"&gt;
        &lt;div class="mw-flex-col-xs-12
                                mw-flex-col-sm-8
                                mw-flex-col-md-6
                                mw-flex-col-lg-4"&gt;
                &lt;div class="box"&gt;Responsive&lt;/div&gt;
        &lt;/div&gt;
&lt;/div&gt;</code></pre>
        </section><a name="fluid"></a>
        <section class="page-section"><br>
                <h2>Fluid</h2>
                <p>Percent based widths allow fluid resizing of columns and rows.</p>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-12">
                                <div class="box-row"></div>
                        </div>
                </div>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-1">
                                <div class="box-row"></div>
                        </div>
                        <div class="mw-flex-col-xs-11">
                                <div class="box-row"></div>
                        </div>
                </div>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-2">
                                <div class="box-row"></div>
                        </div>
                        <div class="mw-flex-col-xs-10">
                                <div class="box-row"></div>
                        </div>
                </div>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-3">
                                <div class="box-row"></div>
                        </div>
                        <div class="mw-flex-col-xs-9">
                                <div class="box-row"></div>
                        </div>
                </div>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-4">
                                <div class="box-row"></div>
                        </div>
                        <div class="mw-flex-col-xs-8">
                                <div class="box-row"></div>
                        </div>
                </div>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-5">
                                <div class="box-row"></div>
                        </div>
                        <div class="mw-flex-col-xs-7">
                                <div class="box-row"></div>
                        </div>
                </div>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-6">
                                <div class="box-row"></div>
                        </div>
                        <div class="mw-flex-col-xs-6">
                                <div class="box-row"></div>
                        </div>
                </div>
                <pre><code>.col-xs-6 {
    flex-basis: 50%;
}</code></pre>
        </section><a name="syntax"></a>
        <section class="page-section">
                <h2>Simple Syntax</h2>
                <p>All you need to remember is row, column, content.</p>
                <pre><code>&lt;div class="mw-flex-row"&gt;
        &lt;div class="mw-flex-col-xs-12"&gt;
                &lt;div class="box"&gt;12&lt;/div&gt;
        &lt;/div&gt;
&lt;/div&gt;</code></pre>
        </section><a name="offsets"></a>
        <section class="page-section">
                <h2>Offsets</h2>
                <p>Offset a column</p>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-offset-11 mw-flex-col-xs-1">
                                <div class="box-row"></div>
                        </div>
                </div>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-offset-10 mw-flex-col-xs-2">
                                <div class="box-row"></div>
                        </div>
                </div>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-offset-9 mw-flex-col-xs-3">
                                <div class="box-row"></div>
                        </div>
                </div>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-offset-8 mw-flex-col-xs-4">
                                <div class="box-row"></div>
                        </div>
                </div>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-offset-7 mw-flex-col-xs-5">
                                <div class="box-row"></div>
                        </div>
                </div>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-offset-6 mw-flex-col-xs-6">
                                <div class="box-row"></div>
                        </div>
                </div>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-offset-5 mw-flex-col-xs-7">
                                <div class="box-row"></div>
                        </div>
                </div>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-offset-4 mw-flex-col-xs-8">
                                <div class="box-row"></div>
                        </div>
                </div>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-offset-3 mw-flex-col-xs-9">
                                <div class="box-row"></div>
                        </div>
                </div>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-offset-2 mw-flex-col-xs-10">
                                <div class="box-row"></div>
                        </div>
                </div>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-offset-1 mw-flex-col-xs-11">
                                <div class="box-row"></div>
                        </div>
                </div>
                <pre><code>&lt;div class="mw-flex-row"&gt;
        &lt;div class="mw-flex-col-xs-offset-3 mw-flex-col-xs-9"&gt;
                &lt;div class="box"&gt;offset&lt;/div&gt;
        &lt;/div&gt;
&lt;/div&gt;</code></pre>
        </section><a name="auto"></a>
        <section class="page-section">
                <h2>Auto Width</h2>
                <p>Add any number of auto sizing columns to a row. Let the grid figure it out.</p>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs">
                                <div class="box-row"></div>
                        </div>
                        <div class="mw-flex-col-xs">
                                <div class="box-row"></div>
                        </div>
                </div>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs">
                                <div class="box-row"></div>
                        </div>
                        <div class="mw-flex-col-xs">
                                <div class="box-row"></div>
                        </div>
                        <div class="mw-flex-col-xs">
                                <div class="box-row"></div>
                        </div>
                </div>
                <pre><code>&lt;div class="mw-flex-row"&gt;
        &lt;div class="mw-flex-col-xs"&gt;
                &lt;div class="box"&gt;auto&lt;/div&gt;
        &lt;/div&gt;
&lt;/div&gt;</code></pre>
        </section><a name="nested"></a>
        <section class="page-section">
                <h2>Nested Grids</h2>
                <p>Nest grids inside grids inside grids.</p>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-7">
                                <div class="box box-container">
                                        <div class="mw-flex-row">
                                                <div class="mw-flex-col-xs-9">
                                                        <div class="box-first box-container">
                                                                <div class="mw-flex-row">
                                                                        <div class="mw-flex-col-xs-4">
                                                                                <div class="box-nested"></div>
                                                                        </div>
                                                                        <div class="mw-flex-col-xs-8">
                                                                                <div class="box-nested"></div>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                                <div class="mw-flex-col-xs-3">
                                                        <div class="box-first box-container">
                                                                <div class="mw-flex-row">
                                                                        <div class="mw-flex-col-xs">
                                                                                <div class="box-nested"></div>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                        </div>
                        <div class="mw-flex-col-xs-5">
                                <div class="box box-container">
                                        <div class="mw-flex-row">
                                                <div class="mw-flex-col-xs-12">
                                                        <div class="box-first box-container">
                                                                <div class="mw-flex-row">
                                                                        <div class="mw-flex-col-xs-6">
                                                                                <div class="box-nested"></div>
                                                                        </div>
                                                                        <div class="mw-flex-col-xs-6">
                                                                                <div class="box-nested"></div>
                                                                        </div>
                                                                </div>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                        </div>
                </div>
                <pre><code>&lt;div class="mw-flex-row"&gt;
        &lt;div class="mw-flex-col-xs"&gt;
                &lt;div class="box"&gt;
                        &lt;div class="mw-flex-row"&gt;
                                &lt;div class="mw-flex-col-xs"&gt;
                                        &lt;div class="box"&gt;auto&lt;/div&gt;
                                &lt;/div&gt;
                        &lt;/div&gt;
                &lt;/div&gt;
        &lt;/div&gt;
&lt;/div&gt;</code></pre>
        </section><a name="alignment"></a>
        <section class="page-section">
                <h2>Alignment</h2>
                <p>Add classes to align elements to the start or end of a row as well as the top, bottom, or center of a column</p>
                <h3><code>.start-</code></h3>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-12">
                                <div class="box box-container">
                                        <div class="mw-flex-row start-xs">
                                                <div class="mw-flex-col-xs-6">
                                                        <div class="box-nested"></div>
                                                </div>
                                        </div>
                                </div>
                        </div>
                </div>
                <pre><code>&lt;div class="mw-flex-row start-xs"&gt;
        &lt;div class="mw-flex-col-xs-6"&gt;
                &lt;div class="box"&gt;
                        start
                &lt;/div&gt;
        &lt;/div&gt;
&lt;/div&gt;
</code></pre>
                <h3><code>.center-</code></h3>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-12">
                                <div class="box box-container">
                                        <div class="mw-flex-row center-xs">
                                                <div class="mw-flex-col-xs-6">
                                                        <div class="box-nested"></div>
                                                </div>
                                        </div>
                                </div>
                        </div>
                </div>
                <pre><code>&lt;div class="mw-flex-row center-xs"&gt;
        &lt;div class="mw-flex-col-xs-6"&gt;
                &lt;div class="box"&gt;
                        start
                &lt;/div&gt;
        &lt;/div&gt;
&lt;/div&gt;
</code></pre>
                <h3><code>.end-</code></h3>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-12">
                                <div class="box box-container">
                                        <div class="mw-flex-row end-xs">
                                                <div class="mw-flex-col-xs-6">
                                                        <div class="box-nested"></div>
                                                </div>
                                        </div>
                                </div>
                        </div>
                </div>
                <pre><code>&lt;div class="mw-flex-row end-xs"&gt;
        &lt;div class="mw-flex-col-xs-6"&gt;
                &lt;div class="box"&gt;
                        end
                &lt;/div&gt;
        &lt;/div&gt;
&lt;/div&gt;
</code></pre>
                <p>Here is an example of using the modifiers in conjunction to acheive different alignment at different viewport sizes.</p>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-12">
                                <div class="box box-container">
                                        <div class="mw-flex-row center-xs end-sm start-lg">
                                                <div class="mw-flex-col-xs-6">
                                                        <div class="box-nested"></div>
                                                </div>
                                        </div>
                                </div>
                        </div>
                </div>Example
                <pre><code>&lt;div class="mw-flex-row center-xs end-sm start-lg"&gt;
        &lt;div class="mw-flex-col-xs-6"&gt;
                &lt;div class="box"&gt;
                        All together now
                &lt;/div&gt;
        &lt;/div&gt;
&lt;/div&gt;
</code></pre>
                <h3><code>.top-</code></h3>
                <div class="mw-flex-row top-xs">
                        <div class="mw-flex-col-xs-6">
                                <div class="box-large"></div>
                        </div>
                        <div class="mw-flex-col-xs-6">
                                <div class="box"></div>
                        </div>
                </div>
                <pre><code>&lt;div class="mw-flex-row top-xs"&gt;
        &lt;div class="mw-flex-col-xs-6"&gt;
                &lt;div class="box"&gt;
                        top
                &lt;/div&gt;
        &lt;/div&gt;
&lt;/div&gt;
</code></pre>
                <h3><code>.middle-</code></h3>
                <div class="mw-flex-row middle-xs">
                        <div class="mw-flex-col-xs-6">
                                <div class="box-large"></div>
                        </div>
                        <div class="mw-flex-col-xs-6">
                                <div class="box"></div>
                        </div>
                </div>
                <pre><code>&lt;div class="mw-flex-row middle-xs"&gt;
        &lt;div class="mw-flex-col-xs-6"&gt;
                &lt;div class="box"&gt;
                        center
                &lt;/div&gt;
        &lt;/div&gt;
&lt;/div&gt;
</code></pre>
                <h3><code>.bottom-</code></h3>
                <div class="mw-flex-row bottom-xs">
                        <div class="mw-flex-col-xs-6">
                                <div class="box-large"></div>
                        </div>
                        <div class="mw-flex-col-xs-6">
                                <div class="box"></div>
                        </div>
                </div>
                <pre><code>&lt;div class="mw-flex-row bottom-xs"&gt;
        &lt;div class="mw-flex-col-xs-6"&gt;
                &lt;div class="box"&gt;
                        bottom
                &lt;/div&gt;
        &lt;/div&gt;
&lt;/div&gt;
</code></pre>
        </section><a name="distribution"></a>
        <section class="page-section">
                <h2>Distribution</h2>
                <p>Add classes to distribute the contents of a row or column.</p>
                <h3><code>.around-</code></h3>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-12">
                                <div class="box box-container">
                                        <div class="mw-flex-row around-xs">
                                                <div class="mw-flex-col-xs-2">
                                                        <div class="box-nested"></div>
                                                </div>
                                                <div class="mw-flex-col-xs-2">
                                                        <div class="box-nested"></div>
                                                </div>
                                                <div class="mw-flex-col-xs-2">
                                                        <div class="box-nested"></div>
                                                </div>
                                        </div>
                                </div>
                        </div>
                </div>
                <pre><code>&lt;div class="mw-flex-row around-xs"&gt;
        &lt;div class="mw-flex-col-xs-2"&gt;
                &lt;div class="box"&gt;
                        around
                &lt;/div&gt;
        &lt;/div&gt;
        &lt;div class="mw-flex-col-xs-2"&gt;
                &lt;div class="box"&gt;
                        around
                &lt;/div&gt;
        &lt;/div&gt;
        &lt;div class="mw-flex-col-xs-2"&gt;
                &lt;div class="box"&gt;
                        around
                &lt;/div&gt;
        &lt;/div&gt;
&lt;/div&gt;
</code></pre>
                <h3><code>.between-</code></h3>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-12">
                                <div class="box box-container">
                                        <div class="mw-flex-row between-xs">
                                                <div class="mw-flex-col-xs-2">
                                                        <div class="box-nested"></div>
                                                </div>
                                                <div class="mw-flex-col-xs-2">
                                                        <div class="box-nested"></div>
                                                </div>
                                                <div class="mw-flex-col-xs-2">
                                                        <div class="box-nested"></div>
                                                </div>
                                        </div>
                                </div>
                        </div>
                </div>
                <pre><code>&lt;div class="mw-flex-row between-xs"&gt;
        &lt;div class="mw-flex-col-xs-2"&gt;
                &lt;div class="box"&gt;
                        between
                &lt;/div&gt;
        &lt;/div&gt;
        &lt;div class="mw-flex-col-xs-2"&gt;
                &lt;div class="box"&gt;
                        between
                &lt;/div&gt;
        &lt;/div&gt;
        &lt;div class="mw-flex-col-xs-2"&gt;
                &lt;div class="box"&gt;
                        between
                &lt;/div&gt;
        &lt;/div&gt;
&lt;/div&gt;
</code></pre>
        </section><a name="reordering"></a>
        <section class="page-section">
                <h2>Reordering</h2>
                <p>Add classes to reorder columns.</p>
                <h3><code>.first-</code></h3>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-12">
                                <div class="box box-container">
                                        <div class="mw-flex-row">
                                                <div class="mw-flex-col-xs-2">
                                                        <div class="box-first">1</div>
                                                </div>
                                                <div class="mw-flex-col-xs-2">
                                                        <div class="box-first">2</div>
                                                </div>
                                                <div class="mw-flex-col-xs-2">
                                                        <div class="box-first">3</div>
                                                </div>
                                                <div class="mw-flex-col-xs-2">
                                                        <div class="box-first">4</div>
                                                </div>
                                                <div class="mw-flex-col-xs-2">
                                                        <div class="box-first">5</div>
                                                </div>
                                                <div class="mw-flex-col-xs-2 first-xs">
                                                        <div class="box-nested">6</div>
                                                </div>
                                        </div>
                                </div>
                        </div>
                </div>
                <pre><code>&lt;div class="mw-flex-row"&gt;
        &lt;div class="mw-flex-col-xs-2"&gt;
                &lt;div class="box"&gt;
                        1
                &lt;/div&gt;
        &lt;/div&gt;
        &lt;div class="mw-flex-col-xs-2"&gt;
                &lt;div class="box"&gt;
                        2
                &lt;/div&gt;
        &lt;/div&gt;
        &lt;div class="mw-flex-col-xs-2 first-xs"&gt;
                &lt;div class="box"&gt;
                        3
                &lt;/div&gt;
        &lt;/div&gt;
&lt;/div&gt;
</code></pre>
                <h3><code>.last-</code></h3>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-12">
                                <div class="box box-container">
                                        <div class="mw-flex-row">
                                                <div class="mw-flex-col-xs-2 last-xs">
                                                        <div class="box-nested">1</div>
                                                </div>
                                                <div class="mw-flex-col-xs-2">
                                                        <div class="box-first">2</div>
                                                </div>
                                                <div class="mw-flex-col-xs-2">
                                                        <div class="box-first">3</div>
                                                </div>
                                                <div class="mw-flex-col-xs-2">
                                                        <div class="box-first">4</div>
                                                </div>
                                                <div class="mw-flex-col-xs-2">
                                                        <div class="box-first">5</div>
                                                </div>
                                                <div class="mw-flex-col-xs-2">
                                                        <div class="box-first">6</div>
                                                </div>
                                        </div>
                                </div>
                        </div>
                </div>
                <pre><code>&lt;div class="mw-flex-row"&gt;
        &lt;div class="mw-flex-col-xs-2 last-xs"&gt;
                &lt;div class="box"&gt;
                        1
                &lt;/div&gt;
        &lt;/div&gt;
        &lt;div class="mw-flex-col-xs-2"&gt;
                &lt;div class="box"&gt;
                        2
                &lt;/div&gt;
        &lt;/div&gt;
        &lt;div class="mw-flex-col-xs-2"&gt;
                &lt;div class="box"&gt;
                        3
                &lt;/div&gt;
        &lt;/div&gt;
&lt;/div&gt;
</code></pre>
        </section><a name="reversing"></a>
        <section class="page-section">
                <h2>Reversing</h2>
                <h3><code>.reverse</code></h3>
                <div class="mw-flex-row">
                        <div class="mw-flex-col-xs-12">
                                <div class="box box-container">
                                        <div class="mw-flex-row reverse">
                                                <div class="mw-flex-col-xs-2">
                                                        <div class="box-nested">1</div>
                                                </div>
                                                <div class="mw-flex-col-xs-2">
                                                        <div class="box-nested">2</div>
                                                </div>
                                                <div class="mw-flex-col-xs-2">
                                                        <div class="box-nested">3</div>
                                                </div>
                                                <div class="mw-flex-col-xs-2">
                                                        <div class="box-nested">4</div>
                                                </div>
                                                <div class="mw-flex-col-xs-2">
                                                        <div class="box-nested">5</div>
                                                </div>
                                                <div class="mw-flex-col-xs-2">
                                                        <div class="box-nested">6</div>
                                                </div>
                                        </div>
                                </div>
                        </div>
                </div>
                <pre><code>&lt;div class="mw-flex-row reverse"&gt;
        &lt;div class="mw-flex-col-xs-2"&gt;
                &lt;div class="box"&gt;
                        1
                &lt;/div&gt;
        &lt;/div&gt;
        &lt;div class="mw-flex-col-xs-2"&gt;
                &lt;div class="box"&gt;
                        2
                &lt;/div&gt;
        &lt;/div&gt;
        &lt;div class="mw-flex-col-xs-2"&gt;
                &lt;div class="box"&gt;
                        3
                &lt;/div&gt;
        &lt;/div&gt;
&lt;/div&gt;
</code></pre>
        </section>

</div>

</div>