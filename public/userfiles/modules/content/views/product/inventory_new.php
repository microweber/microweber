<?php
/*<style>
    .js-track-quantity {
         display: none;
     }

    div.dropdown-menu.open {
        max-height: 314px !important;
        overflow: hidden;
    }

    ul.dropdown-menu.inner {
        max-height: 260px !important;
        overflow-y: auto;
    }

    .dropdown.bootstrap-select {
        width:100% !important;
    }
</style>*/

?>

<script>
    $(document).ready(function() {
        $('.js-track-quantity-check').click(function() {
            mw.toggle_inventory_forms_fields();
        });

        <?php if ($contentData['track_quantity'] != 0) : ?>
            mw.toggle_inventory_forms_fields();
            enableTrackQuantityFields();
        <?php else : ?>
            disableTrackQuantityFields();
        <?php endif; ?>

    });


    mw.toggle_inventory_forms_fields = function() {

        $('.js-track-quantity').toggle();

        if ($('.js-track-quantity-check').prop('checked')) {
            enableTrackQuantityFields();
        } else {
            disableTrackQuantityFields();
        }
    }

    function disableTrackQuantityFields() {
        $("input,select", '.js-track-quantity').prop("disabled", true);
        $("input,select", '.js-track-quantity').attr("readonly", 'readonly');

    }

    function enableTrackQuantityFields() {
        $("input,select", '.js-track-quantity').prop("disabled", false);
        $("input,select", '.js-track-quantity').removeAttr("readonly");


    }

    function contentDataQtyChange(instance) {
        if ($(instance).val() == '') {
            $(instance).val('nolimit');
        }
    }
</script>

<div class="card-body mb-3">
    <div class="card-header no-border">
        <h6><strong>Inventory</strong></h6>
    </div>

    <div class=" ">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group mb-3">
                    <label>SKU (Stock Keeping Unit)</label>
                    <input type="text" name="content_data[sku]" class="form-control js-invertory-sku" value="<?php echo $contentData['sku']; ?>">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Barcode (ISBN, UPC, GTIN, etc.)</label>
                    <input type="text" name="content_data[barcode]" class="form-control js-invertory-barcode" value="<?php echo $contentData['barcode']; ?>">
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <div class="custom-control custom-checkbox my-2">
                        <input type="checkbox" name="content_data[track_quantity]" class="form-check-input js-track-quantity-check" value="1" <?php if ($contentData['track_quantity'] == 1) : ?>checked="checked" <?php endif; ?> id="customCheck2">
                        <label class="custom-control-label" for="customCheck2">Track quantity</label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox my-2">
                        <input type="checkbox" class="form-check-input js-invertory-sell-oos" id="customCheck3" name="content_data[sell_oos]" value="1" <?php if ($contentData['sell_oos'] == 1) : ?>checked="checked" <?php endif; ?>>
                        <label class="custom-control-label" for="customCheck3">Continue selling when out of stock</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="js-track-quantity">

            <hr class="thin no-padding" />

            <h6><strong>Quantity</strong></h6>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Available quantity</label>
                        <select name="data_qty" class="form-select js-invertory-quantity">
                            <option selected="selected" value="nolimit">∞ No Limit</option>
                            <option value="0" title="This item is out of stock and cannot be ordered.">Out of stock</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="25">25</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>
                            <option value="30">30</option>
                            <option value="31">31</option>
                            <option value="32">32</option>
                            <option value="33">33</option>
                            <option value="34">34</option>
                            <option value="35">35</option>
                            <option value="36">36</option>
                            <option value="37">37</option>
                            <option value="38">38</option>
                            <option value="39">39</option>
                            <option value="40">40</option>
                            <option value="41">41</option>
                            <option value="42">42</option>
                            <option value="43">43</option>
                            <option value="44">44</option>
                            <option value="45">45</option>
                            <option value="46">46</option>
                            <option value="47">47</option>
                            <option value="48">48</option>
                            <option value="49">49</option>
                            <option value="50">50</option>
                            <option value="51">51</option>
                            <option value="52">52</option>
                            <option value="53">53</option>
                            <option value="54">54</option>
                            <option value="55">55</option>
                            <option value="56">56</option>
                            <option value="57">57</option>
                            <option value="58">58</option>
                            <option value="59">59</option>
                            <option value="60">60</option>
                            <option value="61">61</option>
                            <option value="62">62</option>
                            <option value="63">63</option>
                            <option value="64">64</option>
                            <option value="65">65</option>
                            <option value="66">66</option>
                            <option value="67">67</option>
                            <option value="68">68</option>
                            <option value="69">69</option>
                            <option value="70">70</option>
                            <option value="71">71</option>
                            <option value="72">72</option>
                            <option value="73">73</option>
                            <option value="74">74</option>
                            <option value="75">75</option>
                            <option value="76">76</option>
                            <option value="77">77</option>
                            <option value="78">78</option>
                            <option value="79">79</option>
                            <option value="80">80</option>
                            <option value="81">81</option>
                            <option value="82">82</option>
                            <option value="83">83</option>
                            <option value="84">84</option>
                            <option value="85">85</option>
                            <option value="86">86</option>
                            <option value="87">87</option>
                            <option value="88">88</option>
                            <option value="89">89</option>
                            <option value="90">90</option>
                            <option value="91">91</option>
                            <option value="92">92</option>
                            <option value="93">93</option>
                            <option value="94">94</option>
                            <option value="95">95</option>
                            <option value="96">96</option>
                            <option value="97">97</option>
                            <option value="98">98</option>
                            <option value="99">99</option>
                            <option value="100">100</option>
                        </select>

                        <small class="text-muted d-flex">How many products you have</small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Max quantity per order</label>
                        <select name="data_qty" class="form-select js-invertory-max-quantity-per-order">
                            <option selected="selected" value="nolimit">∞ No Limit</option>
                            <option value="0" title="This item is out of stock and cannot be ordered.">Out of stock</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                            <option value="25">25</option>
                            <option value="26">26</option>
                            <option value="27">27</option>
                            <option value="28">28</option>
                            <option value="29">29</option>
                            <option value="30">30</option>
                            <option value="31">31</option>
                            <option value="32">32</option>
                            <option value="33">33</option>
                            <option value="34">34</option>
                            <option value="35">35</option>
                            <option value="36">36</option>
                            <option value="37">37</option>
                            <option value="38">38</option>
                            <option value="39">39</option>
                            <option value="40">40</option>
                            <option value="41">41</option>
                            <option value="42">42</option>
                            <option value="43">43</option>
                            <option value="44">44</option>
                            <option value="45">45</option>
                            <option value="46">46</option>
                            <option value="47">47</option>
                            <option value="48">48</option>
                            <option value="49">49</option>
                            <option value="50">50</option>
                            <option value="51">51</option>
                            <option value="52">52</option>
                            <option value="53">53</option>
                            <option value="54">54</option>
                            <option value="55">55</option>
                            <option value="56">56</option>
                            <option value="57">57</option>
                            <option value="58">58</option>
                            <option value="59">59</option>
                            <option value="60">60</option>
                            <option value="61">61</option>
                            <option value="62">62</option>
                            <option value="63">63</option>
                            <option value="64">64</option>
                            <option value="65">65</option>
                            <option value="66">66</option>
                            <option value="67">67</option>
                            <option value="68">68</option>
                            <option value="69">69</option>
                            <option value="70">70</option>
                            <option value="71">71</option>
                            <option value="72">72</option>
                            <option value="73">73</option>
                            <option value="74">74</option>
                            <option value="75">75</option>
                            <option value="76">76</option>
                            <option value="77">77</option>
                            <option value="78">78</option>
                            <option value="79">79</option>
                            <option value="80">80</option>
                            <option value="81">81</option>
                            <option value="82">82</option>
                            <option value="83">83</option>
                            <option value="84">84</option>
                            <option value="85">85</option>
                            <option value="86">86</option>
                            <option value="87">87</option>
                            <option value="88">88</option>
                            <option value="89">89</option>
                            <option value="90">90</option>
                            <option value="91">91</option>
                            <option value="92">92</option>
                            <option value="93">93</option>
                            <option value="94">94</option>
                            <option value="95">95</option>
                            <option value="96">96</option>
                            <option value="97">97</option>
                            <option value="98">98</option>
                            <option value="99">99</option>
                            <option value="100">100</option>
                        </select>

                        <small class="text-muted d-flex">How many products you have</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
