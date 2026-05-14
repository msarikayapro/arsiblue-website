import './bootstrap';
import Alpine from 'alpinejs';

import sectionEditor from './admin/section-editor.js';
import campaignForm from './admin/campaign-form.js';
import './site/tracking.js';

window.Alpine = Alpine;

// Admin Alpine components
Alpine.data('sectionEditor', sectionEditor);
Alpine.data('campaignForm', campaignForm);

Alpine.start();
