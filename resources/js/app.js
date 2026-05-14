import './bootstrap';
import Alpine from 'alpinejs';

import sectionEditor from './admin/section-editor.js';

window.Alpine = Alpine;

// Global Alpine components
Alpine.data('sectionEditor', sectionEditor);

Alpine.start();
