//Vendor

import './src/autosize';
import './src/input-mask';
import './src/dropdown';
import './src/tooltip';
import './src/popover';
import './src/switch-icon';
import './src/tab';
import './src/toast';
import * as bootstrap from 'bootstrap';
import * as tabler from './src/tabler';
import  TomSelect from 'tom-select';

globalThis.bootstrap = bootstrap;
globalThis.tabler = tabler;
window.TomSelect = TomSelect;
