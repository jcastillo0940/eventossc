<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Digitador - Eventos</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; background: #0f172a; color: white; }
        .glass { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
        input[type="number"] { background: rgba(30, 41, 59, 1); border: 1px solid #334155; color: white; }
        select { background: rgba(30, 41, 59, 1); border: 1px solid #334155; color: white; }
        input:disabled, select:disabled { opacity: 0.5; cursor: not-allowed; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen p-4" x-data="digitizerApp()">
    <!-- Navigation or Header -->
    <header class="flex justify-between items-center mb-8 max-w-lg mx-auto">
        <h1 class="text-2xl font-bold bg-gradient-to-r from-sky-400 to-indigo-500 bg-clip-text text-transparent italic">Digitador Pro</h1>
        <div class="flex items-center gap-3">
             <button @click="resetForm()" class="text-xs font-black uppercase tracking-widest text-slate-500 hover:text-white transition">Reiniciar</button>
             <div class="bg-sky-500/10 p-2 rounded-xl border border-sky-500/20">
                <svg class="w-6 h-6 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 17h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            </div>
        </div>
    </header>

    {{-- GLOBAL TOAST SYSTEM --}}
    <div x-data="toastManager()" x-init="init()" class="fixed top-6 right-6 z-[9999] flex flex-col gap-3 w-80 pointer-events-none">
        <template x-for="toast in toasts" :key="toast.id">
            <div class="pointer-events-auto flex items-start gap-4 px-5 py-4 rounded-2xl shadow-2xl border bg-slate-900/95 backdrop-blur-sm"
                :class="{ 'border-emerald-500/50': toast.type === 'success', 'border-red-500/50': toast.type === 'error', 'border-sky-500/50': toast.type === 'info' }">
                <div class="flex-none w-8 h-8 rounded-lg flex items-center justify-center mt-0.5" :class="toast.type === 'success' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400'">
                    <svg x-show="toast.type === 'success'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <svg x-show="toast.type === 'error'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    <svg x-show="toast.type === 'info'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="flex-1">
                    <p class="text-[11px] font-black uppercase tracking-widest text-white" x-text="toast.title"></p>
                    <p class="text-[10px] text-slate-400 font-bold mt-1" x-text="toast.message"></p>
                </div>
            </div>
        </template>
    </div>

    <main class="space-y-6 max-w-lg mx-auto pb-20">
        
        <!-- SUCCESS VIEW -->
        <template x-if="successData">
            <div class="glass p-10 rounded-[3rem] text-center space-y-6 animate-bounce-in">
                <div class="w-24 h-24 bg-emerald-500 rounded-full flex items-center justify-center mx-auto shadow-2xl shadow-emerald-500/20">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                </div>
                <div class="space-y-2">
                    <h2 class="text-2xl font-black uppercase italic tracking-tighter text-white">¡Registro Exitoso!</h2>
                    <p class="text-slate-400 font-bold text-xs uppercase tracking-widest px-8" x-text="successData.message"></p>
                </div>
                <div class="bg-slate-900/50 p-6 rounded-2xl border border-slate-800">
                    <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest mb-1">Referencia única</p>
                    <p class="text-xl font-mono font-black text-sky-400 tracking-tighter" x-text="successData.id"></p>
                </div>
                <button @click="resetForm()" class="w-full bg-slate-800 hover:bg-slate-700 text-white py-4 rounded-2xl font-black text-xs uppercase tracking-widest transition-all">Siguiente Papeleta</button>
            </div>
        </template>

        <div x-show="!successData" class="space-y-6">
            <!-- QR Mode vs Manual -->
            <div class="flex p-1 bg-slate-800/50 rounded-2xl border border-slate-700">
                <button @click="mode = 'qr'" :class="mode === 'qr' ? 'bg-sky-500 shadow-lg text-white' : 'hover:bg-slate-700/50 text-slate-400'" class="flex-1 py-3 rounded-xl font-bold uppercase text-[10px] tracking-widest transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 17h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    ESCANEO QR
                </button>
                <button @click="mode = 'manual'" :class="mode === 'manual' ? 'bg-indigo-500 shadow-lg text-white' : 'hover:bg-slate-700/50 text-slate-400'" class="flex-1 py-3 rounded-xl font-bold uppercase text-[10px] tracking-widest transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    FORMULARIO
                </button>
            </div>

            <!-- Preload Overlay -->
            <template x-if="dataFound">
                <div class="p-4 rounded-2xl flex items-center justify-between" :class="isExisting ? 'bg-red-500/10 border border-red-500/20' : 'bg-emerald-500/10 border border-emerald-500/20'">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full animate-pulse" :class="isExisting ? 'bg-red-500' : 'bg-emerald-500'"></div>
                        <span class="text-[10px] font-black uppercase tracking-widest" :class="isExisting ? 'text-red-400' : 'text-emerald-400'" x-text="isExisting ? 'Registro Ya Existente (Bloqueado)' : 'Papeleta Detectada'"></span>
                    </div>
                    <button @click="resetForm()" class="text-[9px] font-black text-slate-500 uppercase hover:text-white">Cambiar</button>
                </div>
            </template>

            <!-- QR Scanner Container -->
            <div x-show="mode === 'qr' && !dataFound" x-cloak class="glass overflow-hidden rounded-[2.5rem] relative border-slate-700">
                <div id="reader" style="width: 100%; height: 350px; border: none;"></div>
                <div class="p-8 text-center space-y-2 border-t border-slate-800">
                    <p class="text-slate-300 font-bold uppercase text-[11px] tracking-widest">Apunta a la papeleta</p>
                    <p class="text-slate-500 text-[10px] font-medium leading-relaxed italic italic">El sistema detectará automáticamente el Juez, Categoría y Participante.</p>
                </div>
            </div>

            <!-- Manual form / Loaded Data form -->
            <div x-show="mode === 'manual' || dataFound" x-cloak class="glass p-8 rounded-[2.5rem] space-y-8 shadow-2xl border-slate-800">
                <div class="space-y-6">
                    <div class="grid grid-cols-1 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest px-1">Evento Activo</label>
                            <select x-model="form.event_id" @change="loadDetails()" :disabled="isExisting" class="w-full h-14 rounded-2xl px-6 font-bold text-slate-200 focus:ring-2 focus:ring-sky-500 focus:outline-none appearance-none bg-slate-900 border-slate-800">
                                <option value="">-- Seleccionar --</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}">{{ $event->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest px-1">Categoría</label>
                                <select x-model="form.category_id" @change="loadCriteria()" :disabled="isExisting" class="w-full h-14 rounded-2xl px-6 font-bold text-slate-200 focus:ring-2 focus:ring-sky-500 appearance-none bg-slate-900 border-slate-800">
                                    <option value="">--</option>
                                    <template x-for="cat in details.categories" :key="cat.id">
                                        <option :value="cat.id" x-text="cat.name" :selected="cat.id == form.category_id"></option>
                                    </template>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest px-1">Competidor</label>
                                <select x-model="form.participant_id" @change="checkExisting()" :disabled="isExisting" class="w-full h-14 rounded-2xl px-6 font-bold text-slate-200 focus:ring-2 focus:ring-sky-500 appearance-none bg-slate-900 border-slate-800">
                                    <option value="">--</option>
                                    <template x-for="p in details.participants" :key="p.id">
                                        <option :value="p.id" x-text="p.name" :selected="p.id == form.participant_id"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-slate-500 tracking-widest px-1">Juez de Mesa</label>
                            <select x-model="form.judge_id" @change="checkExisting()" :disabled="isExisting" class="w-full h-14 rounded-2xl px-6 font-bold text-slate-200 focus:ring-2 focus:ring-sky-500 appearance-none bg-slate-900 border-slate-800">
                                <option value="">-- Seleccionar Juez --</option>
                                <template x-for="j in details.judges" :key="j.id">
                                    <option :value="j.id" x-text="j.name" :selected="j.id == form.judge_id"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <!-- Score Grid -->
                    <div x-show="criteria.length > 0" x-cloak class="pt-8 border-t border-slate-800 space-y-8">
                        <h3 class="text-xl font-black text-white italic italic uppercase tracking-tighter">Evaluación <span class="text-sky-400">Técnica</span></h3>
                        
                        <template x-for="(c, index) in criteria" :key="c.id">
                            <div class="space-y-3 bg-slate-900/50 p-6 rounded-3xl border border-slate-800/50">
                                <div class="flex justify-between items-center px-1">
                                    <span x-text="c.name" class="font-black text-xs uppercase tracking-widest text-slate-200"></span>
                                    <span class="bg-white/5 px-3 py-1 rounded-full text-[9px] font-black text-slate-500 uppercase tracking-widest">Puntaje Máximo: <span class="text-slate-200">5.0</span></span>
                                </div>
                                <div class="relative">
                                    <input type="number" 
                                           x-model="scores[c.id]" 
                                           :readonly="isExisting" 
                                           step="0.1" 
                                           min="0" 
                                           max="5"
                                           @input="validateScore($event, c.id)"
                                           placeholder="0" 
                                           class="w-full h-20 text-4xl font-black rounded-2xl px-8 text-center focus:ring-2 focus:ring-sky-500 border-none bg-slate-950 transition-all text-white placeholder-slate-800">
                                    <div class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-800 font-black text-xl italic italic">PTS</div>
                                </div>
                            </div>
                        </template>

                        <button x-show="!isExisting" @click="submitScores" :disabled="submitting" class="w-full bg-sky-500 hover:bg-sky-600 text-white h-20 rounded-3xl font-black text-lg shadow-2xl transform active:scale-95 transition-all flex items-center justify-center gap-4 uppercase tracking-widest">
                            <template x-if="submitting">
                                <div class="w-6 h-6 border-4 border-white/20 border-t-white rounded-full animate-spin"></div>
                            </template>
                            <template x-if="!submitting">
                                <div class="flex items-center gap-3">
                                    <span>Registrar Papeleta</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </div>
                            </template>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function toastManager() {
            return {
                toasts: [],
                _id: 0,
                init() {
                    window.$toast = {
                        success: (title, message = '') => this.add('success', title, message),
                        error:   (title, message = '') => this.add('error', title, message),
                        info:    (title, message = '') => this.add('info', title, message),
                    };
                },
                add(type, title, message) {
                    const id = ++this._id;
                    this.toasts.push({ id, type, title, message });
                    setTimeout(() => { this.toasts = this.toasts.filter(t => t.id !== id) }, 4000);
                }
            }
        }

        function digitizerApp() {
            return {
                mode: 'qr',
                dataFound: false,
                isExisting: false,
                submitting: false,
                successData: null,
                form: {
                    event_id: '{{ $preload["event_id"] ?? "" }}',
                    category_id: '{{ $preload["category_id"] ?? "" }}',
                    participant_id: '{{ $preload["participant_id"] ?? "" }}',
                    judge_id: '{{ $preload["judge_id"] ?? "" }}'
                },
                details: { categories: [], participants: [], judges: [] },
                criteria: [],
                scores: {},

                async init() {
                    this.startScanner();
                    if (this.form.event_id) {
                        this.dataFound = true;
                        this.mode = 'manual';
                        await this.loadDetails();
                        await this.loadCriteria();
                        await this.checkExisting();
                    }
                },

                resetForm() {
                    this.form = { event_id: '', category_id: '', participant_id: '', judge_id: '' };
                    this.dataFound = false;
                    this.isExisting = false;
                    this.successData = null;
                    this.criteria = [];
                    this.scores = {};
                    this.mode = 'qr';
                    this.startScanner();
                },

                validateScore(event, criterionId) {
                    let val = parseFloat(event.target.value);
                    if (val > 5) {
                        this.scores[criterionId] = 5;
                        window.$toast?.error("PUNTAJE MÁXIMO", "El puntaje permitido es de 0 a 5.");
                    }
                },

                startScanner() {
                    let html5QrCode = new Html5Qrcode("reader");
                    const config = { fps: 15, qrbox: { width: 280, height: 280 } };

                    html5QrCode.start({ facingMode: "environment" }, config, (decodedText) => {
                        try {
                            let payload = decodedText;
                            if (payload.includes('?data=')) {
                                const url = new URL(payload);
                                payload = atob(url.searchParams.get('data'));
                            }
                            const data = JSON.parse(payload);
                            this.form.event_id = data.event_id;
                            this.form.category_id = data.category_id;
                            this.form.participant_id = data.participant_id;
                            this.form.judge_id = data.judge_id;
                            this.dataFound = true;
                            this.mode = 'manual';
                            
                            this.loadDetails().then(() => this.loadCriteria().then(() => this.checkExisting()));
                            html5QrCode.stop();
                        } catch (e) {
                            window.$toast?.error("QR INVÁLIDO", "No se reconoce el formato.");
                        }
                    }).catch(err => { console.warn("Scanner error", err); });
                },

                async loadDetails() {
                    if (!this.form.event_id) return;
                    const res = await fetch(`/digitizer/details?event_id=${this.form.event_id}`);
                    this.details = await res.json();
                },

                async loadCriteria() {
                    if (!this.form.category_id) return;
                    const res = await fetch(`/digitizer/categories/${this.form.category_id}/criteria`);
                    this.criteria = await res.json();
                    if (!this.isExisting) {
                        this.scores = {};
                        this.criteria.forEach(c => { this.scores[c.id] = ''; });
                    }
                },

                async checkExisting() {
                    if (!this.form.event_id || !this.form.category_id || !this.form.participant_id || !this.form.judge_id) return;
                    const res = await fetch(`/digitizer/check-existing?event_id=${this.form.event_id}&category_id=${this.form.category_id}&participant_id=${this.form.participant_id}&judge_id=${this.form.judge_id}`);
                    const data = await res.json();
                    if (data.exists) {
                        this.isExisting = true;
                        this.scores = data.scores;
                        window.$toast?.info("RE-ESCANEO DETECTADO", "Esta papeleta ya fue registrada. Los datos están bloqueados.");
                    } else {
                        this.isExisting = false;
                    }
                },

                async submitScores() {
                    if (!this.form.judge_id || !this.form.participant_id || !this.form.category_id) {
                        window.$toast?.error("FALTAN DATOS", "Complete todos los campos.");
                        return;
                    }
                    const empty = this.criteria.filter(c => this.scores[c.id] === '');
                    if (empty.length > 0) {
                        window.$toast?.error("PUNTAJES INCOMPLETOS", "Debe llenar todos los criterios.");
                        return;
                    }

                    this.submitting = true;
                    const formattedScores = Object.entries(this.scores).map(([id, val]) => ({
                        criterion_id: id,
                        score: val
                    }));

                    try {
                        const res = await fetch('/digitizer', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ ...this.form, scores: formattedScores })
                        });
                        const result = await res.json();
                        if (res.ok) {
                            this.successData = result;
                        } else {
                            window.$toast?.error("ERROR", result.error || "No se pudo guardar");
                        }
                    } catch (e) {
                        window.$toast?.error("CONEXIÓN", "Error de red.");
                    } finally {
                        this.submitting = false;
                    }
                }
            }
        }
    </script>
</body>
</html>
