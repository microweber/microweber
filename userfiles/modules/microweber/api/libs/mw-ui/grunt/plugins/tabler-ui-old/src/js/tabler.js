//Vendor

import './src/autosize';
import './src/input-mask';
import './src/dropdown';
import './src/tooltip';
import './src/popover';
import './src/switch-icon';
import './src/tab';
import './src/toast';
import TomSelect from "tom-select/dist/js/tom-select.complete.min.js";

import * as bootstrap from 'bootstrap';
import * as tabler from './src/tabler';

globalThis.bootstrap = bootstrap;
globalThis.tabler = tabler;

window.TomSelect = TomSelect;


