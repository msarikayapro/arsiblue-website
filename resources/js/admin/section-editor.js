/**
 * Admin > Sayfa İçerikleri editöründeki her section card için Alpine.js komponenti.
 *
 * Sorumluluklar:
 *  - text/html/json içerik için debounced auto-save
 *  - image upload (FormData POST) → filename'i value'ya yazıp save()
 *  - JSON validation hatasında save'i durdur, kullanıcıyı uyar
 *  - Global custom event'ler ile topbar'daki "Kaydedildi" indicator'ını besle
 */
export default function sectionEditor({ url, uploadUrl, type, initial }) {
    return {
        type,
        url,
        uploadUrl,
        value: initial ?? '',
        dirty: false,
        error: null,
        _initial: initial ?? '',

        async save() {
            // initial ile aynıysa kayıtsız geç (yan etki üretme)
            if (this.value === this._initial) {
                this.dirty = false;
                return;
            }

            this.dirty = true;
            this.error = null;
            window.dispatchEvent(new CustomEvent('section-saving'));

            try {
                const res = await window.axios.post(this.url, { value: this.value });
                if (res.data?.ok) {
                    this._initial = this.value;
                    this.dirty = false;
                    window.dispatchEvent(new CustomEvent('section-saved', { detail: res.data }));
                } else {
                    throw new Error('Saved=false');
                }
            } catch (e) {
                this.error = e.response?.data?.error ?? 'Bilinmeyen hata';
                window.dispatchEvent(new CustomEvent('section-failed', { detail: { error: this.error } }));
            }
        },

        async uploadImage(event) {
            const file = event.target.files?.[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('image', file);

            this.dirty = true;
            window.dispatchEvent(new CustomEvent('section-saving'));

            try {
                const res = await window.axios.post(this.uploadUrl, formData, {
                    headers: { 'Content-Type': 'multipart/form-data' },
                });
                if (res.data?.ok) {
                    this.value = res.data.filename;
                    await this.save();
                }
            } catch (e) {
                this.error = e.response?.data?.message ?? 'Yükleme hatası';
                window.dispatchEvent(new CustomEvent('section-failed', { detail: { error: this.error } }));
            } finally {
                event.target.value = ''; // input reset, aynı dosyayı tekrar seçebilsin
            }
        },

        prettify() {
            if (this.type !== 'json' || !this.value) return;
            try {
                const obj = JSON.parse(this.value);
                this.value = JSON.stringify(obj, null, 2);
                this.error = null;
            } catch (e) {
                this.error = 'JSON parse edilemedi: ' + e.message;
            }
        },

        imageUrl(filename) {
            if (!filename) return '';
            // Already a full URL
            if (filename.startsWith('http://') || filename.startsWith('https://') || filename.startsWith('/')) {
                return filename;
            }
            return '/storage/uploads/pages/' + filename;
        },
    };
}
