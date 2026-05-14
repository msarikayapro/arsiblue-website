/**
 * Admin > Kampanyalar formu — Alpine.js komponenti.
 *
 * Sorumluluklar:
 *  - Tüm form alanlarını tek state'te toplar
 *  - Edit modu: 3 sn debounce auto-save (PUT JSON)
 *  - Create modu: auto-save yok, sadece manual submit
 *  - Hero/gallery image upload (FormData → set field → save)
 */
export default function campaignForm({ url, isNew, uploadUrl, data }) {
    return {
        url,
        isNew,
        uploadUrl,
        data,
        _initial: JSON.stringify(data),
        error: null,

        toggleLanding(slug) {
            const idx = this.data.visible_landings.indexOf(slug);
            if (idx >= 0) {
                this.data.visible_landings.splice(idx, 1);
            } else {
                this.data.visible_landings.push(slug);
            }
        },

        async autoSave() {
            if (this.isNew) return;
            if (JSON.stringify(this.data) === this._initial) return;

            window.dispatchEvent(new CustomEvent('campaign-saving'));

            try {
                const res = await window.axios.put(this.url, this.data, {
                    headers: { Accept: 'application/json' },
                });
                if (res.data?.ok) {
                    this._initial = JSON.stringify(this.data);
                    window.dispatchEvent(new CustomEvent('campaign-saved', { detail: res.data }));
                }
            } catch (e) {
                this.error = e.response?.data?.message ?? 'Sunucu hatası';
                window.dispatchEvent(new CustomEvent('campaign-failed', { detail: { error: this.error } }));
            }
        },

        async manualSave() {
            // create modu — POST, sonra redirect
            // edit modu — PUT, sonra session flash'lı redirect
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = this.url;
            form.style.display = 'none';

            // CSRF
            const csrf = document.querySelector('meta[name="csrf-token"]').content;
            this._appendField(form, '_token', csrf);

            // PUT method spoofing for edit mode
            if (!this.isNew) {
                this._appendField(form, '_method', 'PUT');
            }

            // Tüm data alanları
            this._serializeInto(form, this.data);

            document.body.appendChild(form);
            form.submit();
        },

        async uploadImage(event, kind = 'hero') {
            const file = event.target.files?.[0];
            if (!file || !this.uploadUrl) return;

            const formData = new FormData();
            formData.append('image', file);
            formData.append('kind', kind);

            window.dispatchEvent(new CustomEvent('campaign-saving'));

            try {
                const res = await window.axios.post(this.uploadUrl, formData);
                if (res.data?.ok) {
                    if (kind === 'hero') {
                        this.data.hero_image = res.data.filename;
                    } else {
                        this.data.gallery_images.push(res.data.filename);
                    }
                    await this.autoSave();
                }
            } catch (e) {
                this.error = e.response?.data?.message ?? 'Yükleme hatası';
                window.dispatchEvent(new CustomEvent('campaign-failed', { detail: { error: this.error } }));
            } finally {
                event.target.value = '';
            }
        },

        imageUrl(filename) {
            if (!filename) return '';
            if (filename.startsWith('http://') || filename.startsWith('https://') || filename.startsWith('/')) {
                return filename;
            }
            return '/storage/uploads/campaigns/' + filename;
        },

        _appendField(form, name, value) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            input.value = value ?? '';
            form.appendChild(input);
        },

        _serializeInto(form, obj, prefix = '') {
            for (const [k, v] of Object.entries(obj)) {
                const name = prefix ? `${prefix}[${k}]` : k;
                if (Array.isArray(v)) {
                    if (v.length === 0) {
                        // Boş array — backend default false/[] yorumlasın
                        this._appendField(form, `${name}[]`, '');
                    } else {
                        v.forEach(item => this._appendField(form, `${name}[]`, item));
                    }
                } else if (typeof v === 'boolean') {
                    this._appendField(form, name, v ? '1' : '0');
                } else {
                    this._appendField(form, name, v == null ? '' : String(v));
                }
            }
        },
    };
}
