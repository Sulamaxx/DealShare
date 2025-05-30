@extends('layouts.app')

@section('title', 'Buyme Bargains')

@section('content')

    <main id="tt-pageContent" class="tt-offset-small">
        <div class="container">
            <div class="tt-tab-wrapper">
                <div class="tt-wrapper-inner">
                    <ul class="nav nav-tabs pt-tabs-default tabs-mobile" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link nav-link-after nav-link-mobile" data-toggle="tab" href="#about"
                                role="tab"><span class="span-mobile">About</span></a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link nav-link-after nav-link-mobile" data-toggle="tab" href="#guidelines"
                                role="tab"><span class="span-mobile">Guidelines</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-after nav-link-mobile" data-toggle="tab" href="#faq"
                                role="tab"><span class="span-mobile">FAQ</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-after nav-link-mobile" data-toggle="tab" href="#term_and_services"
                                role="tab"><span class="span-mobile">Terms of
                                    Service</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-after nav-link-mobile" data-toggle="tab" href="#privacy"
                                role="tab"><span class="span-mobile">Privacy</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-after nav-link-mobile" data-toggle="tab" href="#contact"
                                role="tab"><span class="span-mobile">Contact
                                    Us</span></a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane" id="about" role="tabpanel">
                        {!! $aboutContent ?? '' !!}
                    </div>

                    <div class="tab-pane " id="guidelines" role="tabpanel">
                        {!! $guidelinesContent ?? '' !!}
                    </div>

                    <div class="tab-pane" id="faq" role="tabpanel">
                        {!! $faqContent ?? '' !!}
                    </div>

                    <div class="tab-pane" id="term_and_services" role="tabpanel">
                        {!! $termsContent ?? '' !!}
                    </div>

                    <div class="tab-pane" id="privacy" role="tabpanel">
                        {!! $privacyContent ?? '' !!}
                    </div>
                    <div class="tab-pane" id="contact" role="tabpanel">
                        {!! $contactContent ?? '' !!}
                    </div>
                </div>

            </div>
        </div>
    </main>

    <style>
        .nav-link-after::after {
            background: none;
        }

        @media(max-width:426px) {
            .nav-item-custom {
                width: 100%;
                padding-right: 0px !important;
            }

            .nav-item-button-custom {
                width: 100%;
            }

            .nav-link-mobile {
                font-size: small;
            }

            .span-mobile {
                padding-block: 0px !important;
            }

            .tabs-mobile {
                justify-content: space-around !important;
            }

        }

        @media(max-width:767px) {
            .setting-btn-custom {
                width: fit-content !important;
                margin-left: auto;
            }



        }

        @media(min-width:426px) {
            .post-container-custom {
                min-height: 70vh;
            }


        }
    </style>

@endsection



<script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const targetTabId = urlParams.get('tab');

        if (targetTabId) {
            // Find the corresponding nav link and tab pane
            const targetLink = document.querySelector(`.nav-link[href="#${targetTabId}"]`);
            const targetPane = document.getElementById(targetTabId);

            // Deactivate all tabs and panes
            document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('show', 'active'));

            // Activate the correct link and pane
            if (targetLink && targetPane) {
                targetLink.classList.add('active');
                targetPane.classList.add('show', 'active');
            }
        } else {
            // If no tab param, activate the first tab by default
            const defaultLink = document.querySelector('.nav-link');
            const defaultPaneId = defaultLink?.getAttribute('href')?.substring(1);
            const defaultPane = document.getElementById(defaultPaneId);

            if (defaultLink && defaultPane) {
                defaultLink.classList.add('active');
                defaultPane.classList.add('show', 'active');
            }
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);

        if (urlParams.has('tab')) {
            const targetTab = urlParams.get('tab');
            const targetLink = document.querySelector(`.nav-link[href="#${targetTab}"]`);
            const allTabs = document.querySelectorAll('.tab-pane');
            const allLinks = document.querySelectorAll('.nav-link');

            allTabs.forEach(tab => tab.classList.remove('show', 'active'));
            allLinks.forEach(link => link.classList.remove('active'));

            if (targetLink) {
                targetLink.classList.add('active');
                const targetContent = document.querySelector(`#${targetTab}`);
                if (targetContent) targetContent.classList.add('show', 'active');
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('#activityTabs button');
        const panes = document.querySelectorAll('#activityTabsContent .tab-pane');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Deactivate all tabs and panes
                tabs.forEach(t => t.classList.remove('active'));
                panes.forEach(p => p.classList.remove('show', 'active'));

                // Activate the clicked tab and its corresponding pane
                const targetPaneId = this.dataset.bsTarget || this.getAttribute('data-target');
                const targetPane = document.querySelector(targetPaneId);

                this.classList.add('active');
                if (targetPane) {
                    targetPane.classList.add('show', 'active');
                }
            });
        });

        const urlParams = new URLSearchParams(window.location.search);
        let activeTabId = 'commented-tab'; // Default tab

        if (urlParams.has('voted_page')) {
            activeTabId = 'voted-tab';
        } else if (urlParams.has('subscribed_page')) {
            activeTabId = 'subscribed-tab';
        }

        const initialTabButton = document.getElementById(activeTabId);
        if (initialTabButton) {
            initialTabButton.click(); // Simulate a click on the initial tab button
        }
    });
</script>
