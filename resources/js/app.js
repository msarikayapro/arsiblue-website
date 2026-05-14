import './bootstrap';
import Alpine from 'alpinejs';

import sectionEditor from './admin/section-editor.js';
import campaignForm from './admin/campaign-form.js';

window.Alpine = Alpine;

// Global Alpine components
Alpine.data('sectionEditor', sectionEditor);
Alpine.data('campaignForm', campaignForm);

Alpine.start();
