        <!-- Page Header Start-->
        <div class="page-header">
            <div class="header-wrapper row m-0">
                <form class="form-inline search-full col" action="#" method="get">
                    <div class="form-group w-100">
                        <div class="Typeahead Typeahead--twitterUsers">
                            <div class="u-posRelative"><input
                                    class="demo-input Typeahead-input form-control-plaintext w-100" type="text"
                                    placeholder="Search Anything Here..." name="q" title="" autofocus>
                                <div class="spinner-border Typeahead-spinner" role="status"><span
                                        class="sr-only">Loading...</span></div><i class="close-search"
                                    data-feather="x"></i>
                            </div>
                            <div class="Typeahead-menu"></div>
                        </div>
                    </div>
                </form>
                <div class="header-logo-wrapper col-auto p-0">
                    <div class="logo-wrapper"><a href="{{ route('admin.dashboard') }}"><img class="img-fluid for-light"
                                src="{{ asset('assets/images/logo/logo.png') }}" alt=""><img
                                class="img-fluid for-dark" src="{{ asset('assets/images/logo/logo_dark.png') }}"
                                alt=""></a></div>
                    <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle"
                            data-feather="align-center"></i></div>
                </div>
               {{-- <div class="left-header col-xxl-5 col-xl-6 col-lg-5 col-md-4 col-sm-3 p-0">
                    <div class="notification-slider">
                        <div class="d-flex h-100"> <img src="{{ asset('assets/images/giftools.gif') }}" alt="gif">
                            <h6 class="mb-0 f-w-400"><span class="font-primary">Don't Miss Out! </span><span
                                    class="f-light"> Out new update has been release.</span></h6><i
                                class="icon-arrow-top-right f-light"></i>
                        </div>
                        <div class="d-flex h-100"><img src="{{ asset('assets/images/giftools.gif') }}" alt="gif">
                            <h6 class="mb-0 f-w-400"><span class="f-light">Something you love is now on sale! </span>
                            </h6><a class="ms-1" href="https://1.envato.market/3GVzd" target="_blank">Buy now !</a>
                        </div>
                    </div>
                </div>  --}}
                <div class="nav-right col-xxl-7 col-xl-6 col-md-7 col-8 pull-right right-header p-0 ms-auto">
                    <ul class="nav-menus">
                        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#labelMoadl"
                    data-whatever="@getbootstrap">Label
                </button>
             <li class="language-nav">
                <div class="translate_wrapper">
                    <div class="current_lang">
                        <div class="lang">
                            {{-- Dynamic Flag based on locale --}}
                            <i class="flag-icon flag-icon-{{ app()->getLocale() == 'en' ? 'us' : 'nl' }}"></i>
                            <span class="lang-txt">
                                {{ strtoupper(app()->getLocale()) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="more_lang">
                        {{-- Add 'selected' class dynamically to the dropdown items --}}
                        <div class="lang {{ app()->getLocale() == 'en' ? 'selected' : '' }}" data-value="en">
                            <i class="flag-icon flag-icon-us"></i>
                            <a href="{{ route('lang.switch', 'en') }}">
                                <span class="lang-txt">English<span> (US)</span></span>
                            </a>
                        </div>
                        
                        <div class="lang {{ app()->getLocale() == 'nl' ? 'selected' : '' }}" data-value="nl">
                            <i class="flag-icon flag-icon-nl"></i>
                            <a href="{{ route('lang.switch', 'nl') }}">
                                <span class="lang-txt">Dutch</span>
                            </a>
                        </div>
                    </div>
                </div>
            </li>
                        {{-- <li class="language-nav">
                            <div class="translate_wrapper">
                                <div class="current_lang">
                                    <div class="lang"><i class="flag-icon flag-icon-us"></i><span class="lang-txt">EN
                                        </span></div>
                                </div>
                                <div class="more_lang">
                                    <div class="lang selected" data-value="en"><i
                                            class="flag-icon flag-icon-us"></i>
                                            <a href="{{ route('lang.switch', 'en') }}" class="lang-txt">English <span>
                                                (US)</span></a>
                                            <span class="lang-txt">English<span> (US)</span> </span>
                                            </div>
                                    <div class="lang" data-value="de"><i class="flag-icon flag-icon-nl"></i>
                                        <a href="{{ route('lang.switch', 'nl') }}" class="lang-txt">Deutsch</a>
                                        <span class="lang-txt">Deutsch</span>
                                    </div>
                                </div>
                            </div>
                        </li> --}}
                        <li class="fullscreen-body"> <span><svg id="maximize-screen">
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#full-screen') }}"></use>
                                </svg></span></li>
                       
                        <li>
                            <div class="mode"><svg>
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#moon') }}"></use>
                                </svg></div>
                        </li>

                        <li class="onhover-dropdown d-none">
                            <div class="notification-box"><svg>
                                    <use href="{{ asset('assets/svg/icon-sprite.svg#notification') }}"></use>
                                </svg><span class="badge rounded-pill badge-success">4 </span></div>
                            <div class="onhover-show-div notification-dropdown">
                                <h6 class="f-18 mb-0 dropdown-title">Notifications </h6>
                                <ul>
                                    <li class="b-l-primary border-4 toast default-show-toast align-items-center text-light border-0 fade show"
                                        aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                        <div class="d-flex justify-content-between">
                                            <div class="toast-body">
                                                <p>Delivery processing</p>
                                            </div><button class="btn-close btn-close-white me-2 m-auto" type="button"
                                                data-bs-dismiss="toast" aria-label="Close"></button>
                                        </div>
                                    </li>
                                    <li class="b-l-success border-4 toast default-show-toast align-items-center text-light border-0 fade show"
                                        aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                        <div class="d-flex justify-content-between">
                                            <div class="toast-body">
                                                <p>Order Complete</p>
                                            </div><button class="btn-close btn-close-white me-2 m-auto" type="button"
                                                data-bs-dismiss="toast" aria-label="Close"></button>
                                        </div>
                                    </li>
                                    <li class="b-l-secondary border-4 toast default-show-toast align-items-center text-light border-0 fade show"
                                        aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                        <div class="d-flex justify-content-between">
                                            <div class="toast-body">
                                                <p>Tickets Generated</p>
                                            </div><button class="btn-close btn-close-white me-2 m-auto" type="button"
                                                data-bs-dismiss="toast" aria-label="Close"></button>
                                        </div>
                                    </li>
                                    <li class="b-l-warning border-4 toast default-show-toast align-items-center text-light border-0 fade show"
                                        aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
                                        <div class="d-flex justify-content-between">
                                            <div class="toast-body">
                                                <p>Delivery Complete</p>
                                            </div><button class="btn-close btn-close-white me-2 m-auto" type="button"
                                                data-bs-dismiss="toast" aria-label="Close"></button>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="profile-nav onhover-dropdown pe-0 py-0">
                            <div class="d-flex profile-media"><img class="b-r-10 rounded-circle" width="35" height="35"
                                    src="{{ asset('assets/images/dashboard/profile.png') }}" alt="">
                                <div class="flex-grow-1"><span>{{ ucfirst(auth()?->user('admin')?->name) }}</span>
                                    <p class="mb-0">{{ auth()?->user()->role->name }} <i class="middle fa-solid fa-angle-down"></i></p>
                                </div>
                            </div>
                            <ul class="profile-dropdown onhover-show-div">
                                <li><a href="{{route('admin.settings.index')}}"><i data-feather="settings"></i><span>Settings</span></a>
                                </li>
                                <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i data-feather="log-in"> </i><span>Log out</span></a></li>
                                <form action="{{route('logout')}}" method="POST" class="d-none" id="logout-form">
                                    @csrf
                                </form>
                            </ul>
                        </li>
                    </ul>
                </div>
                <script class="result-template" type="text/x-handlebars-template"><div class="ProfileCard u-cf">                        
<div class="ProfileCard-avatar"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-airplay m-0"><path d="M5 17H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-1"></path><polygon points="12 15 17 21 7 21 12 15"></polygon></svg></div>
<div class="ProfileCard-details">
<div class="ProfileCard-realName">name</div>
</div>
</div></script>
                <script class="empty-template"
                    type="text/x-handlebars-template"><div class="EmptyMessage">Your search turned up 0 results. This most likely means the backend is down, yikes!</div></script>
            </div>
        </div>


        <div x-data="LabelComponent()">
        <div class="modal fade" id="labelMoadl" tabindex="-1" role="dialog"
            aria-labelledby="labelMoadl" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-toggle-wrapper social-profile text-start dark-sign-up">
                        <div class="modal-body">
                            <!-- Treatment Labels Form -->
                            <div class="mb-5">
                                <h5 class="modal-header justify-content-left border-0 mb-3">{{__('Treatment Label')}}</h5>
                                <form class="row g-3 needs-validation" novalidate @submit.prevent="submitTreatmentLabels">
                                    <!-- Parent Row -->
                                    <div class="row g-2">
                                        <template x-for="(field, index) in treatmentLabels" :key="index">
                                            <!-- Each label takes half width -->
                                            <div class="col-md-6" x-transition:enter="transition ease-out duration-500"
                                                x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                                                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                                x-transition:leave="transition ease-in duration-400"
                                                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                                x-transition:leave-end="opacity-0 translate-y-4 scale-95">
                                                <div class="row align-items-end g-2">
                                                    <!-- Label -->
                                                    <div class="col-6">
                                                        <label class="form-label">Label</label>
                                                        <input class="form-control" type="text" :placeholder="'Label ' + (index + 1)"
                                                            x-model="field.label" :style="'background-color:' + field.color + '; color:white;'"
                                                            required>
                                                    </div>

                                                    <!-- Color -->
                                                    <div class="col-1">
                                                        <label class="form-label">Color</label>
                                                        <input class="form-control p-0"
                                                            type="color"
                                                            x-model="field.color"
                                                            style="height:38px;">
                                                    </div>

                                                    <!-- Actions -->
                                                    <div class="col-3">
                                                        <label class="form-label d-block">&nbsp;</label>
                                                        <div class="d-flex gap-1">
                                                            <button type="button"
                                                                    class="btn btn-outline-primary btn-sm"
                                                                    @click="duplicateTreatmentField(index)">
                                                                <i class="fa-solid fa-plus"></i>
                                                            </button>

                                                            <button type="button"
                                                                    class="btn btn-outline-danger btn-sm"
                                                                    @click="removeTreatmentField(index)"
                                                                    x-show="index > 0">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Add New Field Button -->
                                    <div class="col-md-12">
                                        <button type="button" 
                                                class="btn btn-outline-secondary btn-sm mb-3"
                                                @click="addNewTreatmentField">
                                            <i class="fa-solid fa-plus me-2"></i>Add Another Label
                                        </button>
                                    </div>
                                    
                                    <!-- Submit Button -->
                                    <div class="col-md-12">
                                        <button class="btn btn-primary" type="submit" :disabled="isLoadingTreatment">
                                            <span x-show="isLoadingTreatment" class="spinner-border spinner-border-sm me-2" 
                                                role="status" aria-hidden="true">
                                            </span>
                                            <span x-text="isLoadingTreatment ? 'Saving...' : 'Save Treatment Labels'"></span>
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Question Labels Form -->
                            <div>
                                <h5 class="modal-header justify-content-left border-0 mb-3">{{__('Question Category')}}</h5>
                                <form class="row g-3 needs-validation" novalidate @submit.prevent="submitQuestionLabels">
                                    <!-- Parent Row -->
                                    <div class="row g-2">
                                        <template x-for="(field, index) in questionLabels" :key="index">
                                            <!-- Each label takes half width -->
                                            <div class="col-md-6" x-transition:enter="transition ease-out duration-500"
                                                x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                                                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                                x-transition:leave="transition ease-in duration-400"
                                                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                                x-transition:leave-end="opacity-0 translate-y-4 scale-95">
                                                <div class="row align-items-end g-2">
                                                    <!-- Label -->
                                                    <div class="col-6">
                                                        <label class="form-label">Label</label>
                                                        <input class="form-control" type="text" :placeholder="'Label ' + (index + 1)"
                                                            x-model="field.label" :style="'background-color:' + field.color + '; color:white;'"
                                                            required>
                                                    </div>

                                                    <!-- Color -->
                                                    <div class="col-1">
                                                        <label class="form-label">Color</label>
                                                        <input class="form-control p-0"
                                                            type="color"
                                                            x-model="field.color"
                                                            style="height:38px;">
                                                    </div>

                                                    <!-- Actions -->
                                                    <div class="col-2">
                                                        <label class="form-label d-block">&nbsp;</label>
                                                        <div class="d-flex gap-1">
                                                            <button type="button"
                                                                    class="btn btn-outline-primary btn-sm"
                                                                    @click="duplicateQuestionField(index)">
                                                                <i class="fa-solid fa-plus"></i>
                                                            </button>

                                                            <button type="button"
                                                                    class="btn btn-outline-danger btn-sm"
                                                                    @click="removeQuestionField(index)"
                                                                    x-show="index > 0" style="width: 50px">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Add New Field Button -->
                                    <div class="col-md-12">
                                        <button type="button" 
                                                class="btn btn-outline-secondary btn-sm mb-3"
                                                @click="addNewQuestionField">
                                            <i class="fa-solid fa-plus me-2"></i>Add Another Label
                                        </button>
                                    </div>
                                    
                                    <!-- Submit Button -->
                                    <div class="col-md-12">
                                        <button class="btn btn-primary" type="submit" :disabled="isLoadingQuestion">
                                            <span x-show="isLoadingQuestion" class="spinner-border spinner-border-sm me-2" 
                                                role="status" aria-hidden="true">
                                            </span>
                                            <span x-text="isLoadingQuestion ? 'Saving...' : 'Save Question Labels'"></span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        {{-- End Label Modal --}}
        <!-- Page Header Ends -->


        

<script>
    document.addEventListener('alpine:init', () => {
        window.existingLabels = @json($labels ?? []);
        
        Alpine.data('LabelComponent', () => ({

            treatmentLabels: window.existingLabels && window.existingLabels.length > 0 
            ? window.existingLabels
                .filter(l => l.category === 'treatment')
                .map(l => ({
                    id: l.id,
                    label: l.label,
                    color: l.color,
                    category: 'treatment'
                }))
            : [{ id: null, label: '', color: '#7366FF', category: 'treatment' }],

        questionLabels: window.existingLabels && window.existingLabels.length > 0
            ? window.existingLabels
                .filter(l => l.category === 'question')
                .map(l => ({
                    id: l.id,
                    label: l.label,
                    color: l.color,
                    category: 'question'
                }))
            : [{ id: null, label: '', color: '#28C76F', category: 'question' }],

        isLoadingTreatment: false,
        isLoadingQuestion: false,

        isLoading: false,
        isSavingLabels: false,
        allPatients: [],
        dataTable: null,
        table:null,
            
            shouldUseDarkText(hexColor) {
                hexColor = hexColor.replace('#', '');
                const r = parseInt(hexColor.substr(0, 2), 16);
                const g = parseInt(hexColor.substr(2, 2), 16);
                const b = parseInt(hexColor.substr(4, 2), 16);
                const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
                return luminance > 0.5;
            },

            // Patient Form State
            isEdit: false,
            patientId: null,
            isSaving: false,
            isLoadingEdit: false,
            formData: {
                patient_id: '',
                email: '',
                date_of_birth: '',
                user_type: 'patient',
                labels: [''],
                departments: [''],
                opt_for_daily: '0'
            },
            
            modalInstance: null,
           
   
            addNewTreatmentField() {
                this.treatmentLabels.push({ id: null, label: '', color: '#7366FF', category: 'treatment' });
            },

            addNewTreatmentField() {
                this.treatmentLabels.push({ 
                    id: null, 
                    label: '', 
                    color: this.getRandomColor(), 
                    category: 'treatment' 
                });
            },

            removeTreatmentField(index) {
                if (this.treatmentLabels.length > 1) {
                    this.treatmentLabels.splice(index, 1);
                }
            },

            addNewQuestionField() {
                this.questionLabels.push({ 
                    id: null, 
                    label: '', 
                    color: this.getRandomColor(), 
                    category: 'question' 
                });
            },

              getRandomColor() {
                const colors = [
                    '#7366FF', '#28C76F', '#EA5455', '#FF9F43', 
                    '#1E9FF2', '#FFC085', '#A8AAAE', '#00CFE8'
                ];
                return colors[Math.floor(Math.random() * colors.length)];
            },

            duplicateQuestionField(index) {
                const field = { ...this.questionLabels[index], id: null };
                this.questionLabels.splice(index + 1, 0, field);
            },

            removeQuestionField(index) {
                if (this.questionLabels.length > 1) {
                    this.questionLabels.splice(index, 1);
                }
            },

            addNewQuestionField() {
                this.questionLabels.push({ 
                    id: null, 
                    label: '', 
                    color: this.getRandomColor(), 
                    category: 'question' 
                });
            },

         

            // async submitLabel() {
            //     this.isLoading = true;
            //     const payload = {
            //         labels: [
            //             ...this.treatmentLabels,
            //             ...this.questionLabels
            //         ]
            //     };
            //     console.log(payload);
            //     try {
            //         const response = await fetch('/label', {
            //             method: 'POST',
            //             headers: {
            //                 'Content-Type': 'application/json',
            //                 'Accept': 'application/json',
            //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            //             },
            //             body: JSON.stringify(payload)
            //         });

            //         if (!response.ok) {
            //             const errorData = await response.json();
            //             throw new Error(errorData.message || 'Server Error');
            //         }

            //         const result = await response.json();
            //         notify('success', 'Successfully saved labels.');
            //         //alert('Successfully saved labels.');
                    
            //     } catch (error) {
            //         console.error('Submission Error:', error);
            //         notify('error', 'Failed to save labels.');
            //         //alert('Failed to save: ' + error.message);
            //     } finally {
            //         this.isLoading = false;
            //     }
            // }


             async submitTreatmentLabels() {
                this.isLoadingTreatment = true;
                
                try {
                    const response = await fetch('/label', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            labels: this.treatmentLabels.map(label => ({
                                ...label,
                                category: 'treatment'
                            }))
                        })
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || 'Server Error');
                    }

                    const result = await response.json();
                    notify('success', 'Treatment labels saved successfully!');
                    
                    // Refresh the labels from server if needed
                    if (result.labels) {
                        const treatmentLabels = result.labels.filter(l => l.category === 'treatment');
                        if (treatmentLabels.length > 0) {
                            this.treatmentLabels = treatmentLabels.map(l => ({
                                id: l.id,
                                label: l.label,
                                color: l.color,
                                category: 'treatment'
                            }));
                        }
                    }
                    
                } catch (error) {
                    console.error('Submission Error:', error);
                    notify('error', 'Failed to save treatment labels: ' + error.message);
                } finally {
                    this.isLoadingTreatment = false;
                }
            },

            async submitQuestionLabels() {
                this.isLoadingQuestion = true;
                //console.log(this.questionLabels);
                //return;
                
                try {
                    const response = await fetch('/label', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            labels: this.questionLabels.map(label => ({
                                ...label,
                                category: 'question'
                            }))
                        })
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || 'Server Error');
                    }

                    const result = await response.json();
                    notify('success', 'Question labels saved successfully!');
                    
                    // Refresh the labels from server if needed
                    if (result.labels) {
                        const questionLabels = result.labels.filter(l => l.category === 'question');
                        if (questionLabels.length > 0) {
                            this.questionLabels = questionLabels.map(l => ({
                                id: l.id,
                                label: l.label,
                                color: l.color,
                                category: 'question'
                            }));
                        }
                    }
                    
                } catch (error) {
                    console.error('Submission Error:', error);
                    notify('error', 'Failed to save question labels: ' + error.message);
                } finally {
                    this.isLoadingQuestion = false;
                }
            },
        }));
    });



</script>

