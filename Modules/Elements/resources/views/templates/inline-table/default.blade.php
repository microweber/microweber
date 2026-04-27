<div class="element">
    {{--
        Wrap in `.table-responsive` so wide tables (lots of columns,
        long cell content) get a horizontal scroll inside the parent
        column instead of overflowing the entire mobile viewport.
        Bootstrap 5's `.table-responsive` adds `overflow-x: auto`
        plus `-webkit-overflow-scrolling: touch` for momentum.
    --}}
    <div class="table-responsive">
        <table class="mw-wysiwyg-table table">
            <tbody>
                <tr>
                    <td>Lorem Ipsum</td>
                    <td>Lorem Ipsum</td>
                </tr>
                <tr>
                    <td>Lorem Ipsum</td>
                    <td>Lorem Ipsum</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
