import './bootstrap';
import Alpine from 'alpinejs';
import toastr from 'toastr';
import Inputmask from 'inputmask';
import './customCharts';

window.Alpine = Alpine;

window.toastr = toastr;

window.Inputmask = Inputmask;

Alpine.start();
