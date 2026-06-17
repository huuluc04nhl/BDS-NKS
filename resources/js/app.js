import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

Alpine.plugin(collapse);

window.Alpine = Alpine;

// Dispatch alpine:init event so inline script listeners register their components before start
document.dispatchEvent(new CustomEvent('alpine:init'));

Alpine.start();

