<!-- Page Sidebar Start-->
<div class="sidebar-wrapper" data-sidebar-layout="stroke-svg">
    <div>
        <div class="logo-wrapper"><a href="{{ route('admin.dashboard') }}"><img class="img-fluid for-light"
                    src="{{ asset('assets/images/logo/main-logo.png') }}" alt="logo" style="max-width: 60%; max-height: auto;"><img class="img-fluid for-dark"
                    src="{{ asset('assets/images/logo/main_logo.png') }}" alt=""></a>
            <div class="back-btn"><i class="fa-solid fa-angle-left"></i></div>
            <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid">
                </i></div>
        </div>
        <div class="logo-icon-wrapper"><a href="{{ route('admin.dashboard') }}"><img class="img-fluid"
                    src="{{ asset('assets/images/logo/logo-icon.png') }}" alt=""></a></div>
        <nav class="sidebar-main">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                    <li class="back-btn"><a href="{{ route('admin.dashboard') }}"><img class="img-fluid"
                                src="{{ asset('assets/images/logo/logo-icon.png') }}" alt=""></a>
                        <div class="mobile-back text-end"><span>Back</span><i class="fa-solid fa-angle-right ps-2"
                                aria-hidden="true"></i></div>
                    </li>
                    <li class="pin-title sidebar-main-title">
                        <div>
                            <h6>Pinned</h6>
                        </div>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h6 class="lan-1">General</h6>
                        </div>
                    </li>
                    {{-- Pinned functionality <i class="fa-solid fa-thumbtack"> --}}
                   
                    <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav" href="{{ route('admin.dashboard') }}"><svg
                            class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                        </svg><svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-home') }}"></use>
                        </svg> <span>{{ __('Dashboard') }}</span></a>
                    </li>
                    
                    <li class="sidebar-list"><a
                        class="sidebar-link sidebar-title link-nav" href="{{ route('admin.list_patients') }}"><svg
                            class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-user') }}"></use>
                        </svg><svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-user') }}"></use>
                        </svg> <span>{{ __('Patients') }}</span></a>
                    </li>
                    
                    <li class="sidebar-list"><a
                            class="sidebar-link sidebar-title link-nav" href="{{ route('admin.departments') }}"><svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-sitemap') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-sitemap') }}"></use>
                            </svg>
                            <span>{{ __('Departments') }}</span></a>
                    </li>

                    <li class="sidebar-list"><a
                        class="sidebar-link sidebar-title link-nav" href="{{ route('admin.documents.index') }}"><svg
                            class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-file') }}"></use>
                        </svg><svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-file') }}"></use>
                        </svg> <span>{{ __('Document') }}</span></a>
                    </li>

                     <li class="sidebar-list"><a
                        class="sidebar-link sidebar-title link-nav" href="{{ route('admin.faq.index') }}"><svg
                            class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-faq') }}"></use>
                        </svg><svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#fill-faq') }}"></use>
                        </svg> <span>{{ __('FAQ') }}</span></a>
                    </li>


                      {{-- <li class="sidebar-list"></i><a
                            class="sidebar-link sidebar-title link-nav" href="{{ route('admin.documents.index') }}"><svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#ai-file') }}"></use>
                            </svg>
                            <svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#pdf-file') }}"></use>
                            </svg>
                            <span>{{ __('Document') }}</span></a>
                    </li> --}}
                    

                    
                    <li class="sidebar-list"></i><a
                            class="sidebar-link sidebar-title" href="javascript:void(0)"><svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-sample-page') }}"></use>
                            </svg><svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-sample-page') }}"></use>
                            </svg><span>{{ __('Questions') }}</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('admin.questions.index', ['module' => 'general']) }}">{{__('General Questions')}}</a></li>
                            <li><a href="{{ route('admin.questions.index', ['module' => 'bellscale']) }}">{{__('Bell Scale Questions')}}</a></li>
                            <li><a href="{{ route('admin.questions.index', ['module' => 'intake']) }}">{{__('Intake Questions')}}</a></li>
                            <li>
                                {{-- <label class="badge badge-light-success">New</label> --}}
                                <a href="{{ route('admin.questions.index', ['module' => 'monthly']) }}">{{__('Monthly Questions')}}</a></li>
                                
                            <li><a href="{{ route('admin.questions.index', ['module' => 'survey']) }}">{{__('Survey Questions')}}</a></li>
                        </ul>
                    </li>
                    
                    <li class="sidebar-list"></i><a
                        class="sidebar-link sidebar-title link-nav" href="{{ route('admin.survey.index') }}"><svg
                            class="stroke-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#c-invoice') }}"></use>
                        </svg><svg class="fill-icon">
                            <use href="{{ asset('assets/svg/icon-sprite.svg#c-invoice') }}"></use>
                        </svg> <span>{{ __('Surveys') }}</span></a>
                    </li>

                    
                    <li class="sidebar-list">
                        {{-- <label class="badge badge-light-success">New</label> --}}
                        {{-- <i class="fa-solid fa-thumbtack"></i> --}}
                        <a class="sidebar-link sidebar-title"
                            href="javascript:void(0)"><svg class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-reports') }}"></use>
                            </svg><svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-reports') }}"></use>
                            </svg><span>{{__("Reports")}}</span></a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('admin.reports.getSurveyReport') }}">{{__("Survey Report")}}</a></li>
                            <li><a href="{{ route('admin.reports.questionReport') }}">{{__("Question Report")}}</a></li>
                            <li><a href="{{ route('admin.reports.bellscaleReport') }}">{{__("Bellscale Report")}}</a></li>
                            <li><a href="{{route('admin.reports.chartReport')}}">{{__("Overall Report")}}</a></li>
                            <li><a href="{{route('admin.reports.surveyChartReport.survey')}}">{{__("Survey Chart Report")}}</a></li>
                        </ul>
                    </li>
                
                    
                     {{-- <li class="sidebar-main-title">
                        <div>
                            <h6>Components</h6>
                        </div>
                    </li> --}}
                  
         
                    {{-- <li class="sidebar-list"></i><a
                            class="sidebar-link sidebar-title link-nav" href="{{ route('admin.support_ticket') }}"><svg
                                class="stroke-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-support-tickets') }}"></use>
                            </svg><svg class="fill-icon">
                                <use href="{{ asset('assets/svg/icon-sprite.svg#fill-support-tickets') }}"></use>
                            </svg><span>Support Ticket</span></a></li> --}}
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </nav>
    </div>
</div>
<!-- Page Sidebar Ends-->
