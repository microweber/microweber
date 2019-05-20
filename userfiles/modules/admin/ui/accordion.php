

<div class="ui_section">
    <h2>Accordion</h2>
    <br>
    <div class="mw-accordion">
        <div class="mw-accordion-item">
            <div class="mw-ui-box-header mw-accordion-title">
                <div class="header-holder">
                    <i class="mai-setting2"></i> Another Example1
                </div>
            </div>
            <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                Lorem Ipsum1
            </div>
        </div>
        <div class="mw-accordion-item">
            <div class="mw-ui-box-header mw-accordion-title">
                <div class="header-holder">
                    <i class="mai-setting2"></i> Another Example2
                </div>
            </div>
            <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                Lorem Ipsum2
            </div>
        </div>
    </div>
    <br>
    <h3>Nested Accordions</h3>

    <div class="mw-accordion">
        <div class="mw-accordion-item">
            <div class="mw-ui-box-header mw-accordion-title">
                <div class="header-holder">
                    <i class="mai-setting2"></i> Level 1
                </div>
            </div>
            <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                Level 2
                <div class="mw-ui-box mw-ui-box-content">
                    <div class="mw-accordion">
                        <div class="mw-accordion-item">
                            <div class="mw-ui-box-header mw-accordion-title">
                                <div class="header-holder">
                                    <i class="mai-setting2"></i> Another Example1
                                </div>
                            </div>
                            <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                                <div class="mw-ui-box mw-ui-box-content">
                                    <div class="mw-accordion">
                                        <div class="mw-accordion-item">
                                            <div class="mw-ui-box-header mw-accordion-title">
                                                <div class="header-holder">
                                                    <i class="mai-setting2"></i> Level 3
                                                </div>
                                            </div>
                                            <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                                                Lorem Ipsum1
                                            </div>
                                        </div>
                                        <div class="mw-accordion-item">
                                            <div class="mw-ui-box-header mw-accordion-title">
                                                <div class="header-holder">
                                                    <i class="mai-setting2"></i> Another Example2
                                                </div>
                                            </div>
                                            <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                                                Lorem Ipsum2
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mw-accordion-item">
                            <div class="mw-ui-box-header mw-accordion-title">
                                <div class="header-holder">
                                    <i class="mai-setting2"></i> Another Example2
                                </div>
                            </div>
                            <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                                Lorem Ipsum2
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="mw-accordion-item">
            <div class="mw-ui-box-header mw-accordion-title">
                <i class="mai-setting2"></i> Another Example2
            </div>
            <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                Lorem Ipsum2
            </div>
        </div>
    </div>

    <br>
    <h3>Basic Accordion</h3>
<div class="mw-accordion">
    <div class="mw-accordion-title">
        Basic 1
    </div>
    <div class="mw-accordion-content">
        Lorem Ipsum 1
    </div>
    <div class="mw-accordion-title">
        Basic 2
    </div>
    <div class="mw-accordion-content">
        Lorem Ipsum 2
    </div>
</div>
</div>

<hr>

<div class="ui_section">
    <h2>Tabs Accordion</h2>
    <br>
    <div class="mw-tab-accordion">
        <div class="mw-accordion-item">
            <div class="mw-ui-box-header mw-accordion-title">
                <i class="mai-setting2"></i> Another Example1
            </div>
            <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                Lorem Ipsum1
            </div>
        </div>
        <div class="mw-accordion-item">
            <div class="mw-ui-box-header mw-accordion-title">
                <i class="mai-setting2"></i> Another Example2
            </div>
            <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                Lorem Ipsum2
            </div>
        </div>
    </div>
    <br><br>
    <div class="mw-tab-accordion" data-options="tabsSize: big, tabsColor: info, breakPoint: 1200">
        <div class="mw-accordion-item">
            <div class="mw-ui-box-header mw-accordion-title">
                <i class="mai-setting2"></i> Another Example1
            </div>
            <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                Lorem Ipsum1
            </div>
        </div>
        <div class="mw-accordion-item">
            <div class="mw-ui-box-header mw-accordion-title">
                <i class="mai-setting2"></i> Another Example2
            </div>
            <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
                Lorem Ipsum2
            </div>
        </div>
    </div>

</div>


<div class="ui_section">
    <h2>Color picker</h2>
    <br>

    <input class="mw-color-picker">


</div>

<div class="ui_section">
    <h2>Tree</h2>
    <br>
    <input type="text" placeholder="tree search" id="tree-search">
    <div id="tree_demo"></div>

 <script>


     xtree_demo_data  = [
         {"id":1,"type":"category","parent_id":0,"parent_type":"page","title":"cat1","subtype":"static"},
         {"id":2,"type":"page","parent_id":1,"parent_type":"page","title":"sub for page","subtype":"static"},
         {"id":3,"type":"page","parent_id":1,"parent_type":"category","title":"sub for cat","subtype":"static"},
         {"id":1,"type":"page","parent_id":0,"parent_type":"page","title":"page1","subtype":"static"}
     ];
     var tree1 = new mw.tree({
         element:'#tree_demo',
         data: tree_demo_data,
         selectable:true,
         singleSelect:true,
         cantSelectTypes: ['page'],
         searchInput: '#tree-search'
     });


 </script>



</div>

<div class="ui_section">
    <h2>Fields</h2>
    <br>

    <div class="mw-field" size="small">
        <input type="text" placeholder="Small">
    </div>
    <br>
    <br>
    <div class="mw-field" size="medium">
        <input type="text" placeholder="Medium">
    </div>
    <br>
    <br>
    <div class="mw-field">
        <input type="text" placeholder="Default">
    </div>
    <br>
    <br>
    <div class="mw-field" size="large">
        <input type="text" placeholder="Large">
    </div>
    <br>
    <br>

    <div class="mw-field" data-before="Distance">
        <input type="text" placeholder="Distance">
    </div>
    <br>
    <br>
    <div class="mw-field" data-after="km">
        <input type="text" placeholder="Distance">
    </div>
    <br>
    <br>
    <div class="mw-field" data-before="$" data-after=".00">
        <input type="text" placeholder="Price">
    </div>
    <br>
    <br>
    <div class="mw-ui-btn-nav">
        <a href="javascript:;" class="mw-ui-btn"><span class="mw-icon-magnify"></span></a>
        <input type="text" class="mw-ui-field" placeholder="Search...">
        <a href="javascript:;" class="mw-ui-btn"><span class="mw-icon-gear"></span></a>
    </div>
    <br>
    <br>
    <div class="mw-ui-btn-nav">
        <a href="javascript:;" class="mw-ui-btn mw-ui-btn-outline"><span class="mw-icon-magnify"></span></a>
        <input type="text" class="mw-ui-field" placeholder="Search...">
        <div class="mw-dropdown mw-dropdown-default">
            <span class="mw-dropdown-value mw-ui-btn mw-dropdown-val">Choose</span>
            <div class="mw-dropdown-content">
                <ul>
                    <li value="1">Option 1</li>
                    <li value="2">Option 2 !!!</li>
                    <li value="3">Option 3</li>
                </ul>
            </div>
        </div>
    </div>
    <br>
    <br>
    <div class="mw-ui-btn-nav">
        <div class="mw-dropdown mw-dropdown-default">
            <span class="mw-dropdown-value mw-ui-btn mw-dropdown-val">Choose</span>
            <div class="mw-dropdown-content">
                <ul>
                    <li value="1">Option 1</li>
                    <li value="2">Option 2 !!!</li>
                    <li value="3">Option 3</li>
                </ul>
            </div>
        </div>
        <input type="text" class="mw-ui-field" placeholder="Search...">
    </div>
    <div class="mw-field" data-before="Native Select">
        <select>
            <option>Choose</option>
            <option>Option 1</option>
            <option>Option 2</option>
        </select>
    </div>
</div>
